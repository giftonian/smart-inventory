<?php

namespace App\Models;

use App\Models\ItemImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';

    protected $fillable = [        
        'name',       
        'small_description',
        'description',
        'original_price',
        'selling_price',
        'quantity',        
        'status'       
        
    ];

    public function itemImages()
    {
        return $this->hasMany(ItemImage::class, 'item_id', 'id'); // 1 to *
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id'); // 1 to 1
    }

    // Define many-to-many relationship with Category
    public function itemCategories()
    {        
        return $this->belongsToMany(Category::class,'item_categories'); // * to *
    }
}
