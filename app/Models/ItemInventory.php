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
        'location_id',    
        'quantity',
        'description'         
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
