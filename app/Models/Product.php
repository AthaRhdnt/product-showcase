<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // use InteractsWithMedia;

    protected $table = 'products';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'price', 
        'category_id', 
        'thumbnail',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
