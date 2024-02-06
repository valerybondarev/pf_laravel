<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
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
        'name',
        'price',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];


    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:255',
            ],
            'price' => [
                'required',
                'numeric',
                'gt:0'
            ]
        ];
    }

    public function OrderLinks() {
        return $this->hasMany(OrderProduct::class, 'product_id', 'id');
    }

    public function getProductInfo() {
        return Product::where('id', $this->id)->get();
    }
}
