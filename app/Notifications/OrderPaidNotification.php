<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * 这里只需要邮件通知，所以只需要一个mail即可，真是环境中如果你需要其他请自行添加
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('订单支付成功')// 邮件标题
                    ->greeting($this->order->user->name.'您好：') // 欢迎词
                    ->line('您于 '.$this->order->created_at->format('m-d H:i').' 创建的订单已经支付成功。')// 邮件内容
                    ->action('查看订单', route('orders.show', [$this->order->id]))// 邮件中的按钮及对应链接
                    ->success(); // 按钮的色调
    }

}
