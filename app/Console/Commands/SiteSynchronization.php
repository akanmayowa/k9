<?php

namespace App\Console\Commands;
use App\Services\K9Services;
use Illuminate\Http\Response;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class SiteSynchronization extends Command
{

    protected $signature = 'cronjob:sites';
    protected $description = 'Site synchronization automation';
    public $synchronizer = null;

    public function __construct(K9Services $k9DatabaseServices)
    {
        $this->synchronizer = $k9DatabaseServices;
        parent::__construct();
    }

    public function downloadJSONFile()
    {
        Log::info("Task Writing to file Started");
        $data = json_encode(['dummy data 1','dummy data 2','dummy data 3','dummy data 4','dummy data 5']);
        $file = time() .rand(). '_file.json';
        $destinationPath=public_path()."/upload/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$data);
        // return response()->download($destinationPath.$file);
        Log::info("Task Writing to file ended");
      }


      public function synchronizeSitesTable()
      {
        Log::info("Site Synchronization Started");
        $synchronization  = $this->synchronizer->synchronizeSiteTable(auth()->user());
        //  return response()->json($synchronization);
        var_dump($synchronization);
        Log::info("Site Synchronization Completed");
      }


      public function synchronizeEmployeeTable()
      {
        Log::info("Employee Synchronization Started");
        $synchronization  = $this->synchronizer->synchronizeEmployeeTable(auth()->user());
        // return response()->json($synchronization);
        var_dump($synchronization);
        Log::info("Employee Synchronization Completed");
      }


    public function handle()
    {
        return [
            
               $this->synchronizeSitesTable(),
               $this->synchronizeEmployeeTable(),
               $this->downloadJSONFile(),
        ];
    }
}
