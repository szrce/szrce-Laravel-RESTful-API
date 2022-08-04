<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\OrdersModels;
use App\Models\OrderProductModels;
use App\Models\CustomersModels;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //show all orders
        $OrderList = OrdersModels::with('items')->get(array('OrderId','CustomerId','Total'));
        return ($OrderList);
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
      //we need customerID first and detect
      /*
      {
        "101":{
          "items":[{
             "productId": 100,
                      "quantity": 1,
                      "unitPrice": "120.75",
                      "total": "120.75"
          }]
        },
        "102":{
          "items":[{
             "productId": 100,
                      "quantity": 1,
                      "unitPrice": "120.75",
                      "total": "120.75"
          }]
        }
      }*/

          foreach($request->input() as $customerID=>$productData){
            $orderID = rand($customerID,300);
            foreach($productData['items'] as $p){
              $Product_data = Products::where('id',$p['productId'])->first(array('price','stock'));
              if(!$Product_data->stock > $p['quantity']  == false){
                  return response()->json(["this product {$p['productId']}-id not enough"],404);
              }
              $dataSet[] = [
                  'orderId'=>$orderID,
                  'productId'=> $p['productId'],
                  'quantity'=> $p['quantity'],
                  'Unitprice'=>$Product_data->price,
                  'Total'=>($Product_data->price * $p['quantity'])
                ];
            }
            $orders_table[] = [
              'OrderId'=>$orderID,
              'CustomerId'=>$customerID,
              'total'=>0
            ];
          }

          OrderProductModels::insert($dataSet);
          OrdersModels::insert($orders_table);
          OrdersModels::OrderUpdate();
          Products::StockUpdate();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      /*example data
      [{
        "ordersId": 7,
        "productId":100
      },
      {
      "ordersId": 8,
      "productId":100
      }]*/

        /*if some part empty return error*/
        $validator = Validator::make($request->all(), [
            '*.ordersId' => 'required|max:255',
            '*.productId*'=> 'required|max:255'
        ]);
        $validator_msg = $validator->errors()->messages();

        if ($validator->fails()) {
            return response()->json($validator_msg,400);
        }

        foreach($request->input() as $orderdata) {
          if(!OrdersModels::where(['id'=>$orderdata['ordersId']],
            ['productId'=>$orderdata['productId']])->delete()){
              return response()->json(['this ordersId or productId not exists orders'],404);
          }
        }
        //delete orders with equals id if not exist return not found
        return response()->json(['orders delete it'],200);
    }
}
