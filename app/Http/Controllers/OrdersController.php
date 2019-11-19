<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;
use App\Services\OrderService;

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

    public function store(OrderRequest $request, OrderService $orderService)
    {
        $user    = $request->user();
        $address = UserAddress::find($request->input('address_id'));

        return $orderService->store($user, $address, $request->input('remark'), $request->input('items'));
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

    public function received(Order $order,Request $request)
    {
        //校验权限
        $this->authorize('own',$order);

        //判断订单状态是否为已发货
        if($order->ship_status !== Order::SHIP_STATUS_DELIVERED){
            throw new InvalidRequestException('发货状态不正确');
        }

        //更新物流状态为已收货
        $order->update(['ship_status' => Order::SHIP_STATUS_RECEIVED]);

        return $order;
    }
}
