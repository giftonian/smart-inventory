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

    //protected $guarded = [];

    protected $fillable = [        
        'name',
        'item_code',       
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

    public function itemInventories()
    {        
        //return $this->belongsToMany(ItemInventory::class,'item_inventories'); // * to *
        return $this->hasMany(ItemInventory::class);
    }

    public function importToDB()
    {
        $path = resource_path('pending-files/*.csv');
        

        $g = glob($path); // getting all files from the path specified
       
        foreach (array_slice($g, 0, 4) as $file) { // getting 1 file at a time
            $data = array_map('str_getcsv', file($file));

            foreach ($data as $row) {            // you can also use updateOrCreate      
                self::create([
                    'name'     => $row[0],
                    'small_description'    => $row[1], 
                    'description' => $row[2],
                    'original_price' => $row[3],
                    'selling_price' => $row[4],
                    'status' => $row[5],                    
                ]);
            }

            unlink($file);
            
            // try {
            //     unlink($file);
            //     dd("File deleted successfully.:".$file);
            // } catch (\Throwable $e) {
            //     echo "Error deleting file: " . $e->getMessage();
            // }
            // exit;
            
        }
        
    }
}
