<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}