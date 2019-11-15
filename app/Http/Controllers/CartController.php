<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    protected $cartService;

    /**
     * 使用了 Laravel 容器的自动解析功能，当 Laravel 初始化 Controller 类时会检查该类的构造函数参数，
     * Laravel 会自动创建一个 CartService 对象作为构造参数传入给 CartController
     * CartController constructor.
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {

        $cartItems = $this->cartService->get();

        //通常来说用户重复使用最近用过的地址概率比较大，在取地址的时候根据 last_used_at 最后一次使用时间倒序排序，这样用户体验会好一些
        $addresses = $request->user()->addresses()->orderBy('last_used_at','desc')->get();

        return view('cart.index',['cartItems' => $cartItems, 'addresses' => $addresses]);
    }

    public function add(AddCartRequest $request)
    {
        $this->cartService->add($request->input('sku_id'), $request->input('amount'));
        return [];
    }

    public function remove(ProductSku $sku,Request $request)
    {
        $this->cartService->remove($sku->id);
        return [];
    }
}
