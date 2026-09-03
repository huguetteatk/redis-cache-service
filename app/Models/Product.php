<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
    
    protected static function booted()
    {
        static::saved(function (self $product) {
            Cache::store('redis')->forget('products:all');
            Cache::store('redis')->forget("product:{$product->id}");
        });

        static::deleted(function (self $product) {
            Cache::store('redis')->forget('products:all');
            Cache::store('redis')->forget("product:{$product->id}");
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
