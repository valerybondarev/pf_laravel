<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'order';
    /**
     * Первичный ключ таблицы БД.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'email',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];




    // Общая информация о заказе
    public function getOrderInfo() {
        // Доработать на полный, либо вынести в отдельный метод
        return Order::where('id', $this->id)->get();
    }

    public function getOrderProducts(int $order_id) {
        return OrderProduct::where('order_id', $order_id)->get();
    }

    // Считаем сумму заказа
    public function getPriceOrder(int $order_id): float {
        $order_products = $this->getOrderProducts($order_id);
        $price = 0;
        foreach ($order_products as $key => $product){
            $price += Product::where('id', $product->product_id)->get()[0]->price * $product->count;
        }
        return $price;
    }
}
