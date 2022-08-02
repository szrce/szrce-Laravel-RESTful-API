<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomersModels extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $fillable = [
      'id',
      'name',
      'since',
      'revenue'
    ];


    public function getdata()
    {
        return $this->hasMany(OrdersModels::class,'customerId');
    }
}
