<?php namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;


class OrderController extends Controller
{

    public array $actions = [
//        [
//            'action' => 'infoOrder',
//            'btnName' => 'Список товаров',
//            'btnStyle' => '-info',
//        ],
        [
            'action' => 'editOrder',
            'btnName' => 'Редактировать',
            'btnStyle' => '-primary',
        ],
        [
            'action' => 'deleteOrder',
            'btnName' => 'Удалить',
            'btnStyle' => '-danger',
        ],
    ];

    public function getOrderInfo(int $order_id) {
        return Order::where('id', $order_id)->get()->toJson();
        // Собачья чушь, чисто для вывода, надо сделать полный
        // Плюс проверки на существование и ошибки
    }


    public function setOrder(): string
    {
        return view('post.create');
    }

    public function deleteOrder(): string
    {
        return 'deleted';
    }


    public function DashboardList() {
        $orders = Order::all();
        foreach ($orders as $key => $order) {
            $orders[$key]['price'] = $order->getPriceOrder($order->id);
            foreach ($this->actions as $btn) {
                $orders[$key]['action'] .= '<button type=\'button\' class=\'btn btn-outline' . $btn['btnStyle'] . ' btn-sm m-1 \' onclick=\'' . $btn['action'] . '(' . $order->id . ')\'>' . $btn['btnName'] . '</button>';
            }
        }
        return datatables()->of($orders)->rawColumns(['action'])->toJson();
    }

}
