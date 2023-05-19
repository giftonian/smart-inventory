<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',        
        'description',
        'image',        
        'status'
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id', 'id'); // 1 to *
    }

    public function categoryItems()
    {        
        return $this->belongsToMany(Item::class,'item_categories'); // * to *
    }
}
