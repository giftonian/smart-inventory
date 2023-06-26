<?php

namespace App\Jobs;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class ItemsImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    public $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = array_map('str_getcsv', file($this->file));
        //dump('Processing file: '.$this->file);

        foreach ($data as $row) {            // you can also use updateOrCreate      
            Item::create([
                'name'     => $row[0],
                'small_description'    => $row[1], 
                'description' => $row[2],
                'original_price' => $row[3],
                'selling_price' => $row[4],
                'status' => $row[5],                    
            ]);
        }
            
        //dump('Done processing file: '.$this->file);

        unlink($this->file);
    }
}
