<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    /**
     * $fillable属性里面的字段被填上，说明这个字段是可以赋值的，其他的所有属性不能被赋值
     * $guarded属性里面的字段被填上，说明这个字段不可以赋值，其他的所有属性都能被赋值
     * @var array
     */
    protected $fillable = [
        'province',
        'city',
        'district',
        'address',
        'zip',
        'contact_name',
        'contact_phone',
        'last_used_at',
    ];

    /**
     * 表示last_used_at字段后面跟着carbon类时间操作的任何方法，例如一个模型
     * @var array
     */
    protected $dates = ['last_used_at'];

    /**
     * 定义和User模型的反向关联，地址=》用户为1对1关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *定义一个完整地址访问器：参照文档Eloquent ORM-修改器章节
     * 通过已有的属性值，使用访问器返回新的计算值
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }
}
