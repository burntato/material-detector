<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ModelController extends Controller
{
    public function updateModelInfo()
    {
        $pythonPath = '/home/osboxes/.pyenv/versions/3.9.0/bin/python3.9';
        $scriptPath = '/home/osboxes/Documents/Projects/imgaug/check_version.py';
        $process = new Process([$pythonPath, $scriptPath]);
        $process->setTimeout(3600);
    
        try {
            $process->run();
    
            // Get the output and the error output regardless of the process status
            $output = $process->getOutput();
            $errorOutput = $process->getErrorOutput();
    
            return redirect()->route('dashboard')->with([
                'success' => 'Model information updated successfully.',
                'output' => $output,  // Pass the output to the session
                'errorOutput' => $errorOutput  // Pass the error output to the session
            ]);
        } catch (ProcessFailedException $exception) {
            return redirect()->route('dashboard')->with([
                'error' => 'Failed to execute model update script: ' . $exception->getMessage(),
                'output' => $process->getOutput(),
                'errorOutput' => $process->getErrorOutput()
            ]);
        }
    }

    public function index()
    {
        $modelInfoPath = storage_path('app/models/model_info.json');
    
        if (!File::exists($modelInfoPath)) {
            // Optionally create the file or handle the error appropriately
            File::put($modelInfoPath, json_encode(['version' => 0, 'files' => [], 'classes' => []]));
            return view('dashboard')->with('error', 'Model information file not found. A new file has been created.');
        }
    
        $modelInfo = json_decode(File::get($modelInfoPath), true);
    
        return view('dashboard', compact('modelInfo'));
    }
}
