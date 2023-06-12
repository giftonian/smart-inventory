<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'item_locations';

    protected $fillable = [
        'name',        
        'description',
        'image',        
        'status'
    ];

    public function inventories()
    {
        return $this->hasMany(ItemInventory::class, 'location_id', 'id');
    }

    // public function locations()
    // {
    //     return $this->hasMany(ItemInventory::class, 'location_id', 'id'); // 1 to *
    // }

    public function locationItems()
    {        
        return $this->belongsToMany(ItemInventory::class,'item_inventories'); // * to *
    }
}
