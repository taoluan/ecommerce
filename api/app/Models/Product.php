<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function inventory() {
        return $this->hasMany(ProductInventory::class, 'product_id', 'id');
    }

    public function category() {
        return $this->hasOne(ProductCategory::class, 'id','category_id');
    }
}
