<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        /**
         * with(['productSku.product']) 方法用来预加载购物车里的商品和 SKU 信息。如果这里没有进行预加载而是在渲染模板时通过 $item->productSku->product 这种懒加载的方式，就会出现购物车中的每一项都要执行多次商品信息的 SQL 查询，导致单个页面执行的 SQL 数量过多，加载性能差的问题。使用了预加载之后，Laravel 会通过类似 select * from product_skus where id in (xxxx) 的方式把原本需要多条 SQL 查询的数据用一条 SQL 就查到了，大大提升了执行效率。同时 Laravel 还支持通过 . 的方式加载多层级的关联关系，这里就通过 . 提前加载了与商品 SKU 关联的商品
         */
        $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();

        return view('cart.index',['cartItems' => $cartItems]);
    }

    public function add(AddCartRequest $request)
    {
        $user = $request->user();

        $skuId = $request->input('sku_id');

        $amount = $request->input('amount');

        //从数据库中查询该商品是否已经存在于购物车中
        if($cart = $user->cartItems()->where('product_sku_id',$skuId)->first()){
            //如果商品存在则直接叠加数量，不要重新加入
            $cart->update([
                'amount' => $cart->amount + $amount,
            ]);
        }else{
            //不存在创建一个新的购物车记录
            $cart = new CartItem(['amount' => $amount]);

            $cart->user()->associate($user);

            $cart->productSku()->associate($skuId);

            $cart->save();
        }

        return [];
    }

    public function remove(ProductSku $sku,Request $request)
    {
        $request->user()->cartItems()->where('product_sku_id',$sku->id)->delete();
        return [];
    }
}
