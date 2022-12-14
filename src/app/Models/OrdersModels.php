<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersModels extends Model
{
    use HasFactory;

    protected $table = 'Orders';
    protected $primaryKey = 'OrderId';

    protected $fillable = [
    ];

    public function items()
    {
      return $this->hasMany(OrderProductModels::class,'OrderId');
    }

    public static function OrderUpdate(){
      foreach(OrdersModels::all() as $Orders){
          $order_totalPrice = OrderProductModels::where('OrderId', $Orders->OrderId)->sum('Total');
          OrdersModels::where('OrderId',$Orders->OrderId)->update(['Total' => $order_totalPrice]);
      }
    }
}
