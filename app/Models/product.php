<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $table= 'products';
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'rating',
    ];
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity');
    }
    public function orderItems(){
        return $this->hasMany(order_item::class);
    }


}
