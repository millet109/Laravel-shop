<?php
namespace App\Services;
/**
 * Created by PhpStorm.
 * User: 0375
 * Date: 2019/11/15
 * Time: 9:44
 */
use Auth;
use App\Models\CartItem;

class CartService
{
    public function get()
    {
        /**
         * with(['productSku.product']) 方法用来预加载购物车里的商品和 SKU 信息。如果这里没有进行预加载而是在渲染模板时通过 $item->productSku->product 这种懒加载的方式，就会出现购物车中的每一项都要执行多次商品信息的 SQL 查询，导致单个页面执行的 SQL 数量过多，加载性能差的问题。使用了预加载之后，Laravel 会通过类似 select * from product_skus where id in (xxxx) 的方式把原本需要多条 SQL 查询的数据用一条 SQL 就查到了，大大提升了执行效率。同时 Laravel 还支持通过 . 的方式加载多层级的关联关系，这里就通过 . 提前加载了与商品 SKU 关联的商品
         */
        return Auth::user()->cartItems()->with(['productSku.product'])->get();
    }

    public function add($skuId, $amount)
    {
        $user = Auth::user();
        // 从数据库中查询该商品是否已经在购物车中
        if ($item = $user->cartItems()->where('product_sku_id', $skuId)->first()) {
            // 如果存在则直接叠加商品数量
            $item->update([
                'amount' => $item->amount + $amount,
            ]);
        } else {
            // 否则创建一个新的购物车记录
            $item = new CartItem(['amount' => $amount]);
            $item->user()->associate($user);
            $item->productSku()->associate($skuId);
            $item->save();
        }

        return $item;
    }

    public function remove($skuIds)
    {
        // 可以传单个 ID，也可以传 ID 数组
        if (!is_array($skuIds)) {
            $skuIds = [$skuIds];
        }
        Auth::user()->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
    }
}