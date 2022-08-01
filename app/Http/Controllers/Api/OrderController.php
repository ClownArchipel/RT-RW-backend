<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'number'=>'uniqe',
            'user_id'=>'required',
            'product_id'=>'required',
        ]);

        $p_order= new Order;
        $p_order->status = 'pending';
        $p_order->transaction_id = "RTRW-".strtoupper(uniqid());
        $p_order->order_id = random_int(00000000,99999999);
        if($request->number != null){
            $p_order->number = $request->number;
        }else{
            $p_order->number = 0;
        }
        $p_order->quantity = 1;
        $p_order->user_id = $request->user_id;
        $p_order->product_id=$request->product_id;
        $p_order->payment_type = $request->payment_type;
        $p_order->code = $request->code;
        $p_order->pdf_url = isset($request->pdf_url) ? $request->pdf_url : null;

        $product = Product::find($request->product_id);
        $p_order->duration=$product->duration;

        $total = $product->price * $p_order->quantity;
        $p_order->gross_amount = $total;

        $p_order->save();

        return response()->json($p_order);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $snapToken = $order->snap_token;
        if (is_null($snapToken)) {
            // Jika snap token masih NULL, buat token snap dan simpan ke database

            $midtrans = new CreateSnapTokenService($order);
            $snapToken = $midtrans->getSnapToken();

            $order->snap_token = $snapToken;
            $order->save();
        }

        return response()->json($order);
    }


    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request,$id)
    {
        $findorder = Order::findOrFail($id);
        $findorder->update($request->all());

        return response()->json($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::find($id)->delete();
		return response()->json([], 204);
    }

    //custom
    public function getorder($id)
    {
        $findorder = Order::where("user_id",$id)->get();
        return response()->json($findorder);
    }
}
