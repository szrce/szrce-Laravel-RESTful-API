<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\OrdersModels;
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
        //show all products
        //if exist another table data we get data.
        foreach(CustomersModels::all() as $d){
            $orderList[] = CustomersModels::find($d->id)->getdata;
        }
        //return $phone = CustomersModels::find(1)->getdata;
        return ($orderList);
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

        if ($request->isMethod('post')) {

            foreach($request->input() as $customerID=>$productData){
              if(empty(CustomersModels::where('id', $customerID)->first()->id)){
                return response()->json(["this customer {$customerID}-ID not valid"],404);
              }
              //continue;
              foreach($productData['items'] as $p){
                if(Products::where('id',$p['productId'])->first()->stock <= 0){
                  return response()->json(["this product {$p['productId']}-id not enough"],404);
                }
                if(Products::where(['id'=>$p['productId'],'price'=>$p['unitPrice']])->first() == NULL){
                  return response()->json(["this {$p['productId']} product price not valid"],404);
                }

                  $dataSet[] = [
                      'customerId'=> $customerID,
                      'productId'=> $p['productId'],
                      'quantity'=> $p['quantity'],
                      'unitPrice'=> $p['unitPrice'],
                      'ptotal'=>($p['quantity'] * $p['unitPrice'])
                  ];
                }
                $lastStock = Products::where('id', $p['productId'])->first()->stock;
                $update_stock  = $lastStock - $p['quantity'];
                if(!Products::where('id', $p['productId'])->update(['stock'=>$update_stock])){
                  return response()->json(['stock fail'],500);
                }
            }
            if(!OrdersModels::insert($dataSet)){
                return response()->json(['fail'],500);
            }
            return response()->json(['success'],200);
        }
        return response()->json(['fail'],404);
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
