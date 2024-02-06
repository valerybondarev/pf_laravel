<?php

namespace App\Http\Controllers;

use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public array $actions = [
//        [
//            'action' => 'editProduct',
//            'btnName' => 'Редактировать',
//            'btnStyle' => '-primary',
//        ],
        [
            'action' => 'deleteProduct',
            'btnName' => 'Удалить',
            'btnStyle' => '-danger',
        ],
    ];

    public function createForm(): string
    {
        return view('product');
    }

    public function setProduct(Request $request)
    {

        Validator::make($request->all(), (new \App\Models\Product)->rules(), $messages = [
            'required' => 'Поле :attribute обязательно.',
            'name.min:3' => 'Поле :attribute должно содержать минимум 3 символа.',
            'name.max:255' => 'Поле :attribute должно содержать максимум 255 символов.',
            'price.gt:0' => 'Поле :attribute не заполняется отрицательным числом.',
            'price.numeric' => 'Поле :attribute должно быть числом.',
        ])->validate();

        Product::create([
            'name' => $request['name'],
            'price' => $request['price'],
        ]);

        return redirect('/product');
    }

    public function getDisabledProduct($product_id) {
        return OrderProduct::where('product_id', $product_id)->get()->count() > 0;
    }

    public function deleteProduct($product_id) {
        OrderProduct::where('product_id', $product_id)->delete();
        Product::where('id', $product_id)->delete();
        return null;
    }


    public function list($order_id = null) {

        $products = $order_id ? Product::join('order_products', 'order_products.product_id', '=', 'product.id')
            ->where('order_products.order_id', $order_id)->get() : Product::all();

        foreach ($products as $key => $product) {
            foreach ($this->actions as $btn) {
                $products[$key]['action'] .= '<button ' . ($this->getDisabledProduct($product->id) ? 'disabled' : '') . ' type=\'button\' class=\'btn btn-outline' . $btn['btnStyle'] . ' btn-sm m-1 \' onclick=\'' . $btn['action'] . '(' . $product->id . ')\'>' . $btn['btnName'] . '</button>';
            }
        }
        return datatables()->of($products)
            ->rawColumns(['action'])
            ->editColumn('created_at', function ($contact){
                return date('d.m.Y H:i', strtotime($contact->created_at) );
            })
            ->toJson();
    }
}
