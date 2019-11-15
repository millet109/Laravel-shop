<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            //使用with方法实现预加载
            ->with(['items.product', 'items.productSku'])
            ->where('user_id',$request->user()->id)
            ->orderBy('created_at','desc')
            ->paginate();

        return view('orders.index',['orders' => $orders]);
    }

    public function store(OrderRequest $request)
    {
        $user = $request->user();

        // 使用数据库事务
        $order = \DB::transaction(function () use ($user,$request) {
            $address = UserAddress::find($request->input('address_id'));
            //更新当前使用地址的最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);
            //创建订单
            //在事务里先创建了一个订单，把当前用户设为订单的用户，然后把传入的地址数据快照进 address 字段
            $order = new Order([
                'address' => [// 将地址信息放入订单中
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $request->input('remark'),
                'total_amount' => 0,
            ]);
            //订单关联到当前用户
            $order->user()->associate($user);
            //存储
            $order->save();

            $totalAmount = 0;
            $items = $request->input('items');
            //遍历用户提交的所有sku
            foreach ($items as $data){
                $sku = ProductSku::find($data['sku_id']);
                //创建一个OrderItem并直接与当前订单关联
                /**
                 * $order->items()->make() 方法可以新建一个关联关系的对象（也就是 OrderItem）但不保存到数据库，
                 * 这个方法等同于 $item = new OrderItem(); $item->order()->associate($order);
                 */
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    /**
                     * 如果减库存失败则抛出异常，这块代码是在 DB::transaction() 中执行的，
                     * 因此抛出异常时会触发事务的回滚，之前创建的 orders 和 order_items 记录都会被撤销
                     */
                    throw new InvalidRequestException('该商品库存不足');
                }
            }

            // 更新订单总金额
            //根据所有的商品单价和数量求得订单的总价格，更新到刚刚创建的订单的 total_amount 字段
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            //使用 Laravel 提供的 collect() 辅助函数快速取得所有 SKU ID，然后将本次订单中的商品 SKU 从购物车中删除
            $skuIds = collect($items)->pluck('sku_id');
            $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();

            return $order;
        });

        $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
        return $order;
    }

    public function show(Order $order,Request $request)
    {
        /**
         * 只允许订单的创建者可以看到对应的订单信息
         * 通过授权策略类（Policy）来实现
         * \app\Policies\OrderPolicy.php
         */
        $this->authorize('own', $order);
        /**
         * load() 方法与 with() 预加载方法类似，叫 延迟预加载，
         * 不同 load() 是在已经查询出来的模型上调用，而 with() 则是在 ORM 查询构造器上调用
         */
        return view('orders.show',[
            'order' => $order->load(['items.productSku', 'items.product'])
        ]);
    }
}
