<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
