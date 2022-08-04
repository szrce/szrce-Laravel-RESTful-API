<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductModels extends Model
{
    use HasFactory;
    protected $table = 'OrderProduct';

    public function cars()
    {
      return $this->hasMany(Products::class,'id');
    }
    public static function OrderUpdate(){
      foreach(OrdersModels::all() as $Orders){
          $order_totalPrice = OrderProductModels::where('OrderId', $Orders->OrderId)->sum('Total');
          //$new_stock = (Products::where('id', $Orders->ProductId)->first()->stock) - (OrderProductModels::where('ProductId', $Orders->ProductId)->first()->Quantity);
          //Products::where('id',$Orders->ProductId)->update(['stock' => $new_stock]);
          OrdersModels::where('OrderId',$Orders->OrderId)->update(['Total' => $order_totalPrice]);
      }
    }
}
