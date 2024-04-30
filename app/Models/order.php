<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable=[
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function order_items(){
        return $this->hasMany(order_item::class);
    }

}
