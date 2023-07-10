<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use App\Models\Location;
use App\Models\ItemInventory;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $total_users, $total_items, $total_categories, $total_locations,
    $bar_values = [4, 2, 8, 5, 7, 3],
    $topN = 5,
    $top_n_items = [],
    $cat_items_count = [],
    $location_items_count = [];

    public function render()
    {
        // $csv = new MassiveCsvImportFacade();
        // $csv::import('test file');
        //MassiveCsvImportFacade::import('new file.csv');

        $this->total_users      = User::count();
        $this->total_items      = Item::count();
        $this->total_categories = Category::count();
        $this->total_locations  = Location::count();

        // $mostCommonCategories = Category::withCount('items')
        //                 ->orderByDesc('items_count')
        //                 ->take(5)
        //                 ->get();
        $items = Item::orderBy('quantity', 'DESC')
                            ->take($this->topN)
                            ->get();

       
        $i=0;
        foreach ($items as $item) {
            //$this->top_n_items[$item->name] = $item->quantity;            
            $this->top_n_items['labels'][] = $item->name;
            $this->top_n_items['values'][] = $item->quantity;          
        }
        
        // category wise count
        $categoryCounts = Category::withCount('categoryItems')->get();

        foreach ($categoryCounts as $category) {
            $this->cat_items_count['labels'][] = $category->name;
            $this->cat_items_count['values'][] = $category->category_items_count; 
        }

        // fethcing data for location wise items qty chart
        $locations = Location::orderBy('id', 'asc')
        ->where('status', 1)
        ->get();
        
        foreach($locations as $location) {
            $this->location_items_count['locations'][] = $location->name;
        }
        

        //location_items_count

        $groupConcat = DB::table('item_inventories')
            ->select(DB::raw("GROUP_CONCAT(DISTINCT CONCAT(
                'SUM(CASE WHEN a.location_id = ', location_id, ' THEN a.quantity ELSE 0 END) AS \'', location_id, '\''
            )) as group_concat"))
            ->first();

        $mainQuery = "SELECT c.name AS item_name, {$groupConcat->group_concat}
        FROM item_inventories a
        JOIN item_locations b ON a.location_id = b.id
        JOIN items c ON a.item_id = c.id
        GROUP BY c.name";
        
        
        $loc_results = DB::select(DB::raw($mainQuery)); 
        
        $loc_cols_returned = [];
        if ($loc_results) {
            $skip = 1;
            foreach ($loc_results[0] as $col_name => $value) {
                if ($skip === 1) {                    
                    $skip++;
                    continue;
                } else {
                    $loc_cols_returned[] = $col_name;                                       
                }
            }
        }
        
            
        
        $i=0;
        foreach ($loc_results as $locationItem) {
            $this->location_items_count['items'][$i]['name'] = $locationItem->item_name;            
            foreach ($loc_cols_returned as $column_name) {
                $this->location_items_count['items'][$i]['data'][] = $locationItem->$column_name;
            }
            $i++;
        }       
        
        //dd($this->location_items_count);
    
        
        return view('livewire.dashboard');
    }
}
