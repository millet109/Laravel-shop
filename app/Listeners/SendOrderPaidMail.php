<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\OrderPaidNotification;

// implements ShouldQueue 代表异步监听器
class SendOrderPaidMail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        // 从事件对象中取出对应的订单
        $order = $event->getOrder();
        // 调用 notify 方法来发送通知
        $order->user->notify(new OrderPaidNotification($order));
    }
}
