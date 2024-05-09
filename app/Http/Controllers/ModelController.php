<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ModelController extends Controller
{
    public function updateModelInfo()
    {
        $scriptPath = base_path('/home/osboxes/Documents/Projects/imgaug/check_version.py'); 

        // Execute the Python script
        exec("python $scriptPath", $output, $return);

        // Check if the script executed successfully
        if ($return !== 0) {
            return back()->with('error', 'Failed to execute model update script.');
        }

        // Assuming the script updates the same model_info.json file
        return $this->index();
    }

    public function index()
    {
        $modelInfoPath = storage_path('app/models/model_info.json');
    
        if (!File::exists($modelInfoPath)) {
            // Optionally create the file or handle the error appropriately
            File::put($modelInfoPath, json_encode(['version' => 0, 'files' => []]));
            return view('dashboard')->with('error', 'Model information file not found. A new file has been created.');
        }
    
        $modelInfo = json_decode(File::get($modelInfoPath), true);
    
        return view('dashboard', compact('modelInfo'));
    }
}
