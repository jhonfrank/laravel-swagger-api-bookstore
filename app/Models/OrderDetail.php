<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['unit_price', 'quantity', 'sub_total', 'order_id', 'book_id'];
}
