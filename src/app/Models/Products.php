<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    protected $fillable = [
      'id',
      'name',
      'category',
      'price',
      'stock'
    ];
    public static function StockUpdate(){
      foreach(OrderProductModels::all() as $Orders){
          $new_stock = (Products::where('id', $Orders->ProductId)->first()->stock) -  ($Orders->Quantity);
           Products::where('id',$Orders->ProductId)->update(['stock' => $new_stock]);
      }
    }
}
