<?php

namespace App\Http\Livewire\Category;

use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public User $user;
    protected $categories, $paginationTheme = 'tailwind';
    protected $listeners = ['delCat' => 'destroyCategory'];
    //public Category $category;

    public  $name, $description, $status, $catId, 
    $addCategory = false, $updateCategory = false, $pageSize = 5;
    
    public function rules()
    {
        return [            
            'name' => ['required', 'string','max:40','min:3'],
            'description' => ['required', 'string'],            
            'status' => ['nullable']    
            /*
            'user.name' => 'max:40|min:3',
            'user.email' => 'email:rfc,dns',
            'user.phone' => 'max:10',
            'user.about' => 'max:200',
            'user.location' => 'min:3'
            */        
        ];
    }

    /**
     * Open Add Category form
     * @return void
     */
    public function addCategory()
    {
        $this->resetFields();
        $this->addCategory = true;
        $this->updateCategory = false;
        //return back()->with('status', "You are going to create a new Category!");
    }

    /**
     * Cancel Add/Edit form and redirect to category listing page
     * @return void
     */
    public function cancelCategory()
    {
        $this->addCategory = false;
        $this->updateCategory = false;
        $this->resetFields();
    }

    public function resetFields()
    {        
        $this->name = NULL;
        $this->description = NULL;
        $this->status = NULL;        
    }

    public function storeCategory()
    {
        $this->validate();

        try {
             Category::create([            
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status == true ? 1: 0
            ]);
            //session()->flash('success','Category Created Successfully!!');
            $this->resetFields();
            $this->addCategory = false;
            return back()->with('status', "Category has been saved successfully!");
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
                $this->updateCategory = true;
                $this->addCategory = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }

        /**
     * update the category data
     * @return void
     */
    public function updateCategory()
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
            $this->updateCategory = false;
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

        // $this->cancelCategory();
              
    }

    public function render()
    {
        $this->categories = Category::orderBy('id', 'DESC')->paginate($this->pageSize);
        //->get();//->paginate(2);
        return view('livewire.category.listing', [            
            'categories' => $this->categories
        ]);
    }

    public function mount() // temp code
    {
        $this->user = auth()->user();
    }    

    public function save()
    {
        $this->validate();

        if (env('IS_DEMO') && auth()->user()->id == 1) {
            if (auth()->user()->email == $this->user->email) {
                $this->user->save();
                return back()->with('status', "Your profile information have been successfuly saved!");
            }

            return back()->with('demo', "You are in a demo version. You are not allowed to change the email for default users.");
        }

        $this->user->save();

        return back()->with('status', "Your profile information have been successfully saved!");
    }

    
}
