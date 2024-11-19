<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['number', 'total', 'user_id'];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    protected function casts(): array
    {
        return [
            'total' => 'float',
        ];
    }
}
