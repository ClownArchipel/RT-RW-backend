<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function payment_handler(Request $request){
        $json = json_decode($request->getContent());
        $signature_key = hash('sha512',$json->order_id . $json->status_code . $json->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if($signature_key != $json->signature_key){
            return abort(404);
        }

        $order = Order::where('order_id', $json->order_id)->first();
        $voc = Voucher::get();
        foreach($voc as $voc){
         if($voc->status != "used" && $order->duration ==$voc->duration){
            $order->update(['status'=>$json->transaction_status]);
            $order->update(['payment_type'=>$json->payment_type]);
            $order->update(['code'=>$voc->code]);
            $voc->update(['status'=>"used"]);

            return $order;
         };
        };

        return abort(404);

        // // status berhasil
        // $order->update(['status'=>$json->transaction_status]);
        // $order->update(['payment_type'=>$json->payment_type]);
        // // $order->update(['code'=>$json->payment_type]);
        // return $json;
    }

}
