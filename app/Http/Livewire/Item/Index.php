<?php

namespace App\Http\Livewire\Item;

use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use App\Models\Item;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public User $user;
    protected $items, $paginationTheme = 'tailwind';
    protected $listeners = ['delItem' => 'destroyItem'];
    //public Category $category;

    public  $name, $item_code, $small_description, $description, 
    $original_price, $selling_price, $quantity, $status, $itemId,
    $item_cats, $cat_ids, $camera="C", 
    $addItem = false, $updateItem = false, $pageSize = 30;
    
    public function rules()
    {
        return [            
            'name' => ['required', 'string','max:40','min:3'],            
            'status' => ['nullable'],
            'cat_ids' => ['required','array','min:1'],
            'small_description' => ['required', 'string'],
            'quantity' => ['required', 'integer'],
            'original_price' => ['required', 'integer'],
            'selling_price' => ['required', 'integer'],
            'description' => ['nullable', 'string']
                
        ];
    }

    /**
     * Open Add Category form
     * @return void
     */
    public function addItem()
    {
        //$item = $this->itemCodeExists('9020237308');
        //dd($item);        
        $this->resetFields();
        $this->addItem = true;
        $this->updateItem = false;
        //return back()->with('status', "You are going to create a new Category!");
    }

    public function itemCodeExists($item_code)
    {
        return Item::whereItemCode($item_code)->exists(); // ->exists() => true/false
    }

    /**
     * Cancel Add/Edit form and redirect to category listing page
     * @return void
     */
    public function cancelItem()
    {
        $this->addItem = false;
        $this->updateItem = false;
        $this->resetFields();
    }

    public function resetFields()
    {        
        $this->name = NULL;
        $this->item_code = NULL;
        $this->itemId = NULL;
        $this->status = NULL;              
        $this->cat_ids = NULL;
        $this->small_description = NULL; 
        $this->quantity = NULL;
        $this->original_price = NULL;
        $this->selling_price = NULL;
        $this->description = NULL;       
    }

    public function storeItem()
    {        
        $validatedData = $this->validate();
        $this->cat_ids = $validatedData['cat_ids'];        

        try {
            $item_code = mt_rand(1000000000, 9999999999);
            if ($this->itemCodeExists($item_code)) {
                $item_code = mt_rand(1000000000, 9999999999);                
            }
            $this->item_code = $item_code;
            $item = Item::create([            
                'name'              => $this->name,
                'item_code'         => $this->item_code,
                'status'            => $this->status,                              
                'small_description' => $this->small_description, 
                'quantity'          => $this->quantity,
                'original_price'    => $this->original_price,
                'selling_price'     => $this->selling_price,
                'description'       => $this->description                  
            ]);

            $item->itemCategories()->attach($this->cat_ids);

            //session()->flash('success','Category Created Successfully!!');
            $this->resetFields();
            $this->addItem = false;
            return back()->with('status', "Item has been saved successfully!");
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
                $this->updateItem = true;
                $this->addItem = false;

                
                
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }

        /**
     * update the category data
     * @return void
     */
    public function updateItem()
    {
        $validatedData = $this->validate();

        $this->cat_ids = $validatedData['cat_ids']; 

        $item = Item::findOrFail($this->itemId);

        try {
            $item->update([ // findOrFail or whereId
                'name'              => $this->name,
                'status'            => $this->status,                              
                'small_description' => $this->small_description, 
                //'quantity'          => $this->quantity,
                'original_price'    => $this->original_price,
                'selling_price'     => $this->selling_price,
                'description'       => $this->description  
            ]);

            $item->itemCategories()->sync($this->cat_ids);

            //session()->flash('success','Category Updated Successfully!!');
            $this->resetFields();
            $this->updateItem = false;
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
        $this->items = Item::orderBy('id', 'DESC')->paginate($this->pageSize);
        $this->item_cats = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        //->get();//->paginate(2);
        return view('livewire.item.index', [            
            'items' => $this->items,
            'item_cats' => $this->item_cats
        ]);
    }

    public function mount() // temp code
    {
        $this->user = auth()->user();
    }    

    
}
