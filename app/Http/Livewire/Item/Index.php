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
    protected $categories, $paginationTheme = 'tailwind';
    protected $listeners = ['delCat' => 'destroyCategory'];
    //public Category $category;

    public  $name, $small_description, $description, 
    $original_price, $selling_price, $quantity, $status, $catId,
    $item_cats, $cat_ids, 
    $addItem = false, $updateItem = false, $pageSize = 5;
    
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
        $this->resetFields();
        $this->addItem = true;
        $this->updateItem = false;
        //return back()->with('status', "You are going to create a new Category!");
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
            $item = Item::create([            
                'name'              => $this->name,
                'status'            => $this->status,              
                //'name' => $this->cat_ids = NULL;
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
        

        // Category::create([            
        //     'name' => $this->name,
        //     'description' => $this->description,
        //     'status' => $this->status == true ? 1: 0
        // ]);
        
        // session()->flash('message', 'Category Added Successfully!');
        // $this->resetFields();
        // $this->dispatchBrowserEvent('close-modal');
        
        
        //$this->emit('categorySaved');
        
    }

        /**
     * show existing category data in edit category form
     * @param mixed $id
     * @return void
     */
    public function editCategory($id){
        try {
            $category = Category::findOrFail($id);
            if( !$category) {
                session()->flash('error','Category not found');
            } else {
                $this->name = $category->name;
                $this->description = $category->description;
                $this->status = $category->status;
                $this->catId = $category->id;
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
        $this->validate();
        try {
            Category::findOrFail($this->catId)->update([ // findOrFail or whereId
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status
            ]);
            //session()->flash('success','Category Updated Successfully!!');
            $this->resetFields();
            $this->updateItem = false;
            return back()->with('status', "Category has been updated successfully!");
        } catch (\Exception $ex) {
            session()->flash('success','Something goes wrong!!');
        }
    }

   
    public function destroyCategory($catId)
    {
        $this->catId = $catId;        
        $category = Category::findOrFail($this->catId);        
        $category->delete();

        // session()->flash('message', 'Brand Deleted Successfully!');
        // $this->dispatchBrowserEvent('close-modal');
        $this->resetFields();
        return back()->with('status', "Category has been deleted successfully!");
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

    public function deleteCategory($catId)
    {    
        $this->catId = $catId; 
        dd('all set '.$this->catId);
        // $this->resetFields();       
        // $this->dispatchBrowserEvent('open-delete-modal');  

        // $this->cancelItem();
              
    }

    public function render()
    {
        $this->categories = Category::orderBy('id', 'DESC')->paginate($this->pageSize);
        $this->item_cats = Category::where('status', 1)->orderBy('id', 'DESC')->get();
        //->get();//->paginate(2);
        return view('livewire.item.index', [            
            'categories' => $this->categories,
            'item_cats' => $this->item_cats
        ]);
    }

    public function mount() // temp code
    {
        $this->user = auth()->user();
    }    

    
}
