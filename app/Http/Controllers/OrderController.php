<?php

namespace App\Http\Controllers;

use App\Models\BillModel;
use App\Models\OrderModel;
use App\Models\PriceModel;
use App\Models\StatusModel;
use App\Models\DomainModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;
        $dataOrder = DB::select("SELECT  
        trans_h_orders.*,
        m_price.name AS price_name,
        m_status.name AS status_name,
        m_domain.name AS domain_name,
        user.fullname AS customer_name
        FROM trans_h_orders 
        LEFT JOIN m_price ON m_price.id = trans_h_orders.id_price
        LEFT JOIN m_domain ON m_domain.id = trans_h_orders.id_domain
        LEFT JOIN m_bills ON trans_h_orders.id = m_bills.id_h_orders
        LEFT JOIN m_status ON m_status.id = m_bills.id_status
        LEFT JOIN user ON user.id = trans_h_orders.id_customers where trans_h_orders.project_name LIKE '%" . $keyword . "%'");
        return view('order.index', [
            'data' => $dataOrder,
            'keyword' => $keyword,
        ]);
    }
    public function delete($id)
    {
        $dataOrder = OrderModel::find($id);
        $dataOrder->delete();
        return redirect('/order');
    }
    public function create()
    {
        // $dataPrice = PriceModel::all();
        // $dataStatus = StatusModel::all();
        // $dataDomain = DomainModel::all();
        // $dataUser = UserModel::all();
        // return view(
        //     'order.create',

        //     [
        //         'dataPrice' => $dataPrice,
        //         'dataStatus' => $dataStatus,
        //         'dataDomain' => $dataDomain,
        //         'dataUser' => $dataUser

        //     ]
        // );
    }
    public function store(Request $r)
    {

        // return "coba";
        // return $r->all();

        //protected $fillable = ["name"];
        // StatusModel::create($r->all());

        $data = new OrderModel();
        $data->project_name = $r->project_name;
        $data->id_price = $r->id_price;
        $data->id_status = $r->id_status;
        $data->lama_p = $r->lama_p;
        $data->mulai_p = $r->mulai_p;
        $data->selesai_p = $r->selesai_p;
        $data->total_harga = $r->total_harga;
        $data->id_domain = $r->id_domain;
        $data->id_customers = $r->id_customers;
        $data->save();

        return redirect('/order');
    }

    public function edit($id)
    {
        $dataOrder = OrderModel::find($id);

        $dataPrice = PriceModel::all();
        $dataStatus = StatusModel::all();
        $dataDomain = DomainModel::all();
        $dataUser = UserModel::all();
        return view(
            'order.edit',

            [
                'dataPrice' => $dataPrice,
                'dataStatus' => $dataStatus,
                'dataDomain' => $dataDomain,
                'dataUser' => $dataUser,
                'dataOrder' => $dataOrder,

            ]
        );
    }

    public function update(Request $r)
    {
        $data = OrderModel::find($r->id);

        $data->id_customers = $r->id_customers;
        $data->project_name = $r->project_name;
        $data->id_price = $r->id_price;
        $data->id_domain = $r->id_domain;
        $data->id_status = $r->id_status;
        $data->lama_p = $r->lama_p;
        $data->mulai_p = $r->mulai_p;
        $data->selesai_p = $r->selesai_p;
        $data->save();

        return redirect('/order');
    }
}
