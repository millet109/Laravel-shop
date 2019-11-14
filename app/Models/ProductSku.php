<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InternalException;

class ProductSku extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'stock'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('减库存不可小于0');
        }

        /**
         * 用 decrement() 方法来减少字段的值，该方法会返回影响的行数
         * 最终执行的 SQL 类似于 update product_skus set stock = stock - $amount where id = $id and stock >= $amount，
         * 这样可以保证不会出现执行之后 stock 值为负数的情况，也就避免了超卖的问题
         * 还可以通过检查 decrement() 方法返回的影响行数来判断减库存操作是否成功，如果不成功说明商品库存不足
         */
        return $this->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);
    }

    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('加库存不可小于0');
        }
        $this->increment('stock', $amount);
    }
}
