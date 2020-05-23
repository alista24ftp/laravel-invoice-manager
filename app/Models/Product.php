<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price',
    ];

    public $timestamps = false;

    public function scopeFilterByName($query, $name)
    {
        if(is_null($name) || $name == '') return $query;
        return $query->where('name', 'like', "%$name%");
    }

    public function scopeFilterByMinPrice($query, $min_price)
    {
        if(is_null($min_price)) return $query;
        return $query->where('price', '>=', $min_price);
    }

    public function scopeFilterByMaxPrice($query, $max_price)
    {
        if(is_null($max_price)) return $query;
        return $query->where('price', '<=', $max_price);
    }

    public function scopeFilterBetweenPrices($query, $min_price, $max_price)
    {
        if(is_null($min_price) && is_null($max_price)) return $query;
        if(is_null($min_price)) return $query->where('price', '<=', $max_price);
        if(is_null($max_price)) return $query->where('price', '>=', $min_price);
        return $query->where('price', '>=', $min_price)->where('price', '<=', $max_price);
    }

    public function scopeWithOrder($query, $order)
    {
        switch($order){
            case 1: return $query->orderBy('name');
            case 2: return $query->orderBy('name', 'desc');
            case 3: return $query->orderBy('price');
            case 4: return $query->orderBy('price', 'desc');
            default: return $query->orderBy('id');
        }
    }
}
