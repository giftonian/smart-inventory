<?php

namespace App\Http\Livewire\ItemInventory;

use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemInventory;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public User $user;
    protected $items,$items_inventory, $paginationTheme = 'tailwind';
    protected $listeners = ['delItem' => 'destroyItem'];
    //public Category $category;

    public  $item_id, $quantity, $description, $itemId,
    $addInventory = false, $updateInventory = false, $pageSize = 10;
    
    public function rules()
    {
        return [            
            'item_id' => ['required', 'integer'],           
            'quantity' => ['required', 'integer'],           
            'description' => ['nullable', 'string']                
        ];
    }

    /**
     * Open Add Category form
     * @return void
     */
    public function addInventory()
    {
        $this->resetFields();
        $this->addInventory = true;
        $this->updateInventory = false;
        //return back()->with('status', "You are going to create a new Category!");
    }

    /**
     * Cancel Add/Edit form and redirect to category listing page
     * @return void
     */
    public function cancelItem()
    {
        $this->addInventory = false;
        $this->updateInventory = false;
        $this->resetFields();
    }

    public function resetFields()
    {        
        $this->item_id = NULL;               
        $this->quantity = NULL;        
        $this->description = NULL;       
    }

    public function storeItemInventory()
    {        
        $validatedData = $this->validate();
        //dd('validation seems ok');           

        try {
            $itemInventory = ItemInventory::create([            
                'item_id'              => $this->item_id,                
                'quantity'              => $this->quantity,               
                'description'       => $this->description                  
            ]);

            // now updating total inventory in the items table
            $res = Item::where('id', $this->item_id)
                ->where('status', 1)->first();
                
            $res->updated_at    = now();
            $res->quantity      = $res->quantity + $this->quantity;
            $res->save(); 

            //$item->itemCategories()->attach($this->cat_ids);

            //session()->flash('success','Category Created Successfully!!');
            $this->resetFields();
            $this->addInventory = false;
            return back()->with('status', "ItemInventory has been saved successfully!");
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }        
        
    }

    /**
     * show existing Item data in edit item form
     * @param mixed $id
     * @return void
     */
    public function editItem($id){
        try {
            $item = Item::findOrFail($id);            
            if( !$item) {
                session()->flash('error','Item not found');
            } else {
                $this->itemId = $item->id;
                $this->name = $item->name;
                $this->status = $item->status;         
                $this->small_description = $item->small_description; 
                $this->quantity = $item->quantity;
                $this->original_price = $item->original_price;
                $this->selling_price = $item->selling_price;
                $this->description  = $item->description;
                $res = $item->itemCategories->toArray();
                foreach($res as $itemCat) {
                    $this->cat_ids[] = $itemCat['id'];
                }                
                $this->updateInventory = true;
                $this->addInventory = false;

                
                
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }

        /**
     * update the category data
     * @return void
     */
    public function updateInventory()
    {
        $validatedData = $this->validate();

        $this->cat_ids = $validatedData['cat_ids']; 

        $item = Item::findOrFail($this->itemId);

        try {
            $item->update([ // findOrFail or whereId
                'name'              => $this->name,
                'status'            => $this->status,                              
                'small_description' => $this->small_description, 
                'quantity'          => $this->quantity,
                'original_price'    => $this->original_price,
                'selling_price'     => $this->selling_price,
                'description'       => $this->description  
            ]);

            $item->itemCategories()->sync($this->cat_ids);

            //session()->flash('success','Category Updated Successfully!!');
            $this->resetFields();
            $this->updateInventory = false;
            return back()->with('status', "Item has been updated successfully!");
        } catch (\Exception $ex) {
            session()->flash('success','Something goes wrong!!');
        }
    }

   
    public function destroyItem($itemId)
    {
        $this->itemId = $itemId;        
        $item = Item::findOrFail($this->itemId);        
        $item->delete();

        // session()->flash('message', 'Brand Deleted Successfully!');
        // $this->dispatchBrowserEvent('close-modal');
        $this->resetFields();
        return back()->with('status', "Item has been deleted successfully!");
    }

    public function closeModal()
    {
        $this->resetFields();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function openModal()
    {
        $this->resetFields();
    }

    public function deleteItem($itemId)
    {    
        $this->itemId = $itemId; 
        dd('all set '.$this->itemId);
        // $this->resetFields();       
        // $this->dispatchBrowserEvent('open-delete-modal');  

        // $this->cancelItem();
              
    }

    public function render()
    {
        $this->items_inventory = ItemInventory::with('item')->orderBy('id', 'ASC')
        ->paginate($this->pageSize); // item is relationship in ItemInventory
        //ItemInventory::orderBy('id', 'DESC')->paginate($this->pageSize)
        //->with('items');

       
        // foreach ($this->items_inventory as $itemInventory) {
        //     // Access the item details for each item inventory record
        //     $item = $itemInventory->item;
        //     //dd($item->itemInventories);
            
        //     // Access the item inventory details
        //     //$inventoryDetails = $itemInventory->itemInventories;
            
        //     // Do something with the item and inventory details
        //     echo "Item Id : " . $itemInventory->item_id . "<br>";
        //     echo "Item Name : " . $item->name . "<br>";
        //     echo "Item Qty updated : " . $itemInventory->quantity . "<br>";
        //     echo "Item Total Qty : " . $item->quantity . "<br>";
        //     echo "Item Desc : " . $itemInventory->description . "<br>";
        //     echo "**************************** <br>";
        //     //echo "Inventory Quantity: " . $inventoryDetails->quantity . "<br>";
        //     // ...
        // }

        
        $this->items = Item::where('status', 1)->orderBy('id', 'DESC')->get();
        //->get();//->paginate(2);
        return view('livewire.item-inventory.index', [            
            'items' => $this->items,
            'items_inventory' => $this->items_inventory
        ]);
    }

    public function mount() // temp code
    {
        $this->user = auth()->user();
    }    

    
}

