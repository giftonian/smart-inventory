<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ItemInventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',       
        'quantity',
        'description'         
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
