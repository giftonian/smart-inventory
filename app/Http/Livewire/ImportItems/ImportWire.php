<?php

namespace App\Http\Livewire\ImportItems;

use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Bus;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

use App\Imports\UsersImport;
use App\Imports\ItemsImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportWire extends Component
{
    use WithPagination, WithFileUploads;
    public User $user;
    protected $imports, $paginationTheme = 'tailwind';
    protected $listeners = ['delCat' => 'destroyCategory'];
    //public Category $category;

    public  $import_file,
    $name, $description, $status, $catId, 
    $addImport = false, $updateImport = false, $pageSize = 5,
    $submitButtonText = "Submit.";

    // CSV import vars
    public $fileHasHeader = true;
    public $csv_file;
    public $csv_data = [];
    public $db_fields = [];
    public $csv_header_cols = [];
    public $match_fields;
    public $data;
    public $failed = [];
    public $imported = false;

    public $rules = [
        'csv_file' => 'required|mimes:csv,txt|max:102400', // (100MB max, and only csv, txt files.)
       
    ];
    
    // public function rules()
    // {
    //     return $this->rules;//['csv_file' => 'required|file|mimes:csv,txt'];
    // }

    function parseFile()
    {
        $cols = Schema::getColumnListing('items');

        // $this->db_fields = array_diff($cols, ['name', 'small_description', 'description', 
        // 'original_price', 'selling_price', 'status']);
        
        $this->db_fields = ['name', 'small_description', 'description', 
        'original_price', 'selling_price', 'status'];
        

        array_unshift($this->db_fields,'Skip');
        

        $path = $this->csv_file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $this->data = $data;

        if (count($data) > 0) {

            $this->csv_header_cols = [];

            if ($this->fileHasHeader) {
                foreach ($data[0] as $key => $value) {
                    $this->csv_header_cols[] = $key;
                }
                $this->csv_data = array_slice($data, 0, 2);
            } else {
                $this->csv_data = array_slice($data, 0, 1);
            }
        } else {
            //$this->emit('error', 'No data found in your file');
            session()->flash('error','No data found in the import file!');
        }
        
        $this->match_fields = [];
    }

    function processImport()
    {
        if (empty($this->match_fields) || count($this->match_fields) < count($this->csv_header_cols)) {
            //$this->emit('error', __("All columns must be matched"));
            
            session()->flash('error','All columns must be matched!');
            //dd('error');
            //return;
        } else {
            //dd('no error');
        }

        $errors = [];

        foreach ($this->data as $key => $row) {
            if ($this->fileHasHeader && $key == 0) continue;
            $item = new Item();
            if (empty($this->csv_header_cols)) {
                foreach ($this->match_fields as $k => $mf) {
                    $this->csv_header_cols[$mf] = $k;
                }
            }

            foreach ($this->csv_header_cols as $header_col) {

                $field = $this->match_fields[$header_col]??null;
                if(is_null($field)) continue;

                $value = $row[$header_col];
                if ($field == "Skip") continue;
                // if ($field == 'email' || $field == 'phone' || $field == 'address' || $field == 'website') {
                //     $value = json_encode([[
                //         $field  => $value,
                //         'label' => 'home'
                //     ]]);
                // }
                // if ($field == 'birthday') {
                //     try {
                //         $value = Carbon::parse($value)->format('Y-m-d');
                //     } catch (\Exception $e) {
                //         $value = null;
                //     }
                // }
                if (empty($field)) continue; //skip headings

                $item->$field = $value;
            }
            try {
                //$item->user_id = auth()->id();
                
               
                $item->save();
                //return back()->with('status', "Items imported successfully!");                
            } catch (\Exception $e) {
                $errors[] = $row;
                session()->flash('error','Exception'.$e->getMessage());
                return;
            }            
        }
        if (empty($errors)) {

            $this->csv_file = null;
            $this->csv_data = [];
            $this->db_fields = [];
            $this->csv_header_cols = [];
            $this->match_fields = null;
            $this->data = null;
            $this->failed = [];
            //$this->imported = true;
            //$this->emit('success', __('Items imported'));
            $this->resetFields();
            $this->addImport = false;
            return back()->with('status', "Items imported successfully!");
            
        } else {
            $this->failed = $errors;
            //$this->emit('error', 'Error saving some records');
            session()->flash('error','Error saving some records!');
        }
    }

    // ./ end CSV import

    // Excel Import Example
    function excelImport()
    {  
        //$this->validate();
        $validatedData = $this->validate([
            'csv_file' => 'required|mimes:csv,txt|max:102400', // 100MB
        ]);

        $path = $this->csv_file->getRealPath();

        $file = file($path);
        $data = $file; // array_slice($file, 1); // in case file has headers

        $parts = (array_chunk($data, 2000)); // returns chunks of specified number
        
        foreach ($parts as $index => $part) {
            $fileName = resource_path('pending-files/'.date('y-m-d-H-i-s').'-'.$index.'.csv');  
            
            file_put_contents($fileName, $part);
            //break;
        }

        // db entries
        $objItems = new Item();
        $objItems->importToDB();
       


        //

        session()->flash('status','All good - Data Queued for Import!');

        return redirect('import-excel-job');
        //return back()->with('status', 'All good - Data Queued for Import!');
        // dd('ok hai');
        // $path = $this->csv_file->getRealPath();
        // //$data = array_map('str_getcsv', file($path));
        
        // //Excel::import(new UsersImport, $path);
        // Excel::import(new ItemsImport, $path);

        // $this->resetFields();
        // $this->addImport = false;
        
        return back()->with('status', 'All good - Excel file imported!');
    }
    // ./Excel Import Example

       // Massive CSV Package Import Example
       function massiveImport()
       {  
           //$this->validate();
           $validatedData = $this->validate([
               'csv_file' => 'required|mimes:csv,txt|max:102400', // 100MB
           ]);
   
           $path = $this->csv_file->getRealPath();
           dd($path);
   
        //    $file = file($path);
        //    $data = $file; // array_slice($file, 1); // in case file has headers
   
        //    $parts = (array_chunk($data, 2000)); // returns chunks of specified number
           
        //    foreach ($parts as $index => $part) {
        //        $fileName = resource_path('pending-files/'.date('y-m-d-H-i-s').'-'.$index.'.csv');  
               
        //        file_put_contents($fileName, $part);
        //        //break;
        //    }
   
        //    // db entries
        //    $objItems = new Item();
        //    $objItems->importToDB();
           //
   
           session()->flash('status','All good - Data Queued for Import!');
   
           return redirect('import-excel-job');
          
       }
       // ./// Massive CSV Package Import Example

    /**
     * Open Add Category form
     * @return void
     */
    public function addImport()
    {
        $this->resetFields();
        $this->addImport = true;
        $this->updateImport = false;
        //return back()->with('status', "You are going to create a new Category!");
    }

    /**
     * Cancel Add/Edit form and redirect to category listing page
     * @return void
     */
    public function cancelImport()
    {
        $this->addImport = false;
        $this->updateImport = false;
        $this->resetFields();
    }

    public function resetFields()
    {        
        $this->name = NULL;
        $this->description = NULL;
        $this->status = NULL;        
    }

    public function storeImport(Request $request)
    {
        dd('importing');
        $this->validate();

        try {
             Category::create([            
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status == true ? 1: 0
            ]);
            //session()->flash('success','Category Created Successfully!!');
            $this->resetFields();
            $this->addImport = false;
            return back()->with('status', "Category has been saved successfully!");
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
        
    }

    public function upload_csv_records(Request $request)
    {
        if( $request->has('csv') ) {
            $csv    = file($request->csv);
            $chunks = array_chunk($csv,1000);
            $header = [];
            $batch  = Bus::batch([])->dispatch();

            foreach ($chunks as $key => $chunk) {
            $data = array_map('str_getcsv', $chunk);
                if($key == 0){
                    $header = $data[0];
                    unset($data[0]);
                }
                $batch->add(new ProcessCSVData($data, $header));
            }
            return $batch;
        }
        return "please upload csv file";
    }

    public function render()
    {
        $this->imports = [];//Category::orderBy('id', 'DESC')->paginate($this->pageSize);
        //->get();//->paginate(2);
        return view('livewire.import-items.import-wire-index', [            
            'imports' => $this->imports
        ]);
    }

    public function mount() // temp code
    {
        $this->user = auth()->user();
    }    

    
}
