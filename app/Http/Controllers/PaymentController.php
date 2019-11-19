<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function payByAlipay(Order $order,Request $request)
    {
        //判断订单是否属于当前用户
        $this->authorize('own',$order);
        //订单已完成支付或者已经超时自动关闭
        if($order->paid_at || $order->closed){
            throw new InvalidRequestException('订单状态不正确');
        }

        //调用支付宝的 **网页支付**
        return app('alipay')->web([
            'out_trade_no' => $order->no, // 订单编号，需保证在商户端不重复
            'total_amount' => $order->total_amount, // 订单金额，单位元，支持小数点后两位
            'subject'      => '支付 Laravel Shop 的订单：'.$order->no, // 订单标题
        ]);
    }

    /**
     *前端回调 是指当用户支付成功之后支付宝会让用户浏览器跳转回项目页面并带上支付成功的参数，
     * 也就是说前端回调依赖于用户浏览器，如果用户在跳转之前关闭浏览器，将无法收到前端回调
     *
     * app('alipay')->verify() 用于校验提交的参数是否合法，支付宝的前端跳转会带有数据签名，
     * 通过校验数据签名可以判断参数是否被恶意用户篡改。同时该方法还会返回解析后的参数
     */
    public function alipayReturn()
    {
//        $data = app('alipay')->verify();
//        dd($data);
        try {
            app('alipay')->verify();
        } catch (\Exception $e) {
            return view('pages.error', ['msg' => '数据不正确']);
        }

        return view('pages.success', ['msg' => '付款成功']);
    }

    /**
     *服务器回调 是指支付成功之后支付宝的服务器会用订单相关数据作为参数请求项目的接口，不依赖用户浏览器
     */
    public function alipayNotify()
    {
//        $data = app('alipay')->verify();
//        file_put_contents('a.txt',$data->all());
//        \Log::debug('Alipay notify', $data->all());

        // 校验输入参数
        $data  = app('alipay')->verify();
        // 如果订单状态不是成功或者结束，则不走后续的逻辑
        // 所有交易状态：https://docs.open.alipay.com/59/103672
        if(!in_array($data->trade_status, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
            return app('alipay')->success();
        }
        // $data->out_trade_no 拿到订单流水号，并在数据库中查询
        $order = Order::where('no', $data->out_trade_no)->first();
        // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
        if (!$order) {
            return 'fail';
        }
        // 如果这笔订单的状态已经是已支付
        if ($order->paid_at) {
            // 返回数据给支付宝
            return app('alipay')->success();
        }

        $order->update([
            'paid_at'        => Carbon::now(), // 支付时间
            'payment_method' => 'alipay', // 支付方式
            'payment_no'     => $data->trade_no, // 支付宝订单号
        ]);

        $this->afterPaid($order);
        return app('alipay')->success();
    }
}
