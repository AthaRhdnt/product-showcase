<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name', 
        'slug',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'id');
    }
}
