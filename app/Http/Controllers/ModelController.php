<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Models\Material;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function updateModelInfo()
    {
        $pythonPath = '/home/osboxes/.pyenv/versions/3.9.0/bin/python3.9';
        $scriptPath = '/home/osboxes/Documents/Projects/imgaug/check_version.py';
        $process = new Process([$pythonPath, $scriptPath]);
        $process->setTimeout(3600);

        # test comment
        try {
            $process->run();

            // Get the output and the error output regardless of the process status
            $output = $process->getOutput();
            $errorOutput = $process->getErrorOutput();

            return redirect()
                ->route('dashboard')
                ->with([
                    'success' => 'Model information updated successfully.',
                    'output' => $output, // Pass the output to the session
                    'errorOutput' => $errorOutput, // Pass the error output to the session
                ]);
        } catch (ProcessFailedException $exception) {
            return redirect()
                ->route('dashboard')
                ->with([
                    'error' => 'Failed to execute model update script: ' . $exception->getMessage(),
                    'output' => $process->getOutput(),
                    'errorOutput' => $process->getErrorOutput(),
                ]);
        }
    }

    public function checkModelFileUpdates()
    {
        $pythonPath = '/home/osboxes/.pyenv/versions/3.9.0/bin/python3.9';
        $scriptPath = '/home/osboxes/Documents/Projects/imgaug/model_version.py';

        // Execute the Python script
        $process = new Process([$pythonPath, $scriptPath]);
        $process->run();

        // Check for errors during execution
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Redirect back to the dashboard to reload the data
        return redirect()->route('dashboard')->with('success', 'Model file updates checked successfully.');
    }

    public function index()
    {
        $modelInfoPath = storage_path('app/models/model_info.json');
        $outputFilePath = storage_path('app/models/tflite_files_last_modified.json');

        $modelInfo = ['version' => 0, 'files' => [], 'classes' => []];
        if (File::exists($modelInfoPath)) {
            $modelInfo = json_decode(File::get($modelInfoPath), true);
        }

        $jsonData = [];
        if (File::exists($outputFilePath)) {
            $jsonData = json_decode(File::get($outputFilePath), true);

            // Sort the data by last_modified_time in descending order
            usort($jsonData, function ($a, $b) {
                return strtotime($b['last_modified_time']) - strtotime($a['last_modified_time']);
            });
        }

        return view('dashboard', compact('modelInfo', 'jsonData'));
    }

    public function showTraining()
    {
        $materials = Material::all();
        return view('training', compact('materials'));
    }

    public function trainModel()
    {
        $readyMaterials = Material::where('ready', true)->get();
        $pythonPath = '/home/osboxes/.pyenv/versions/3.9.0/bin/python3.9';
        $scriptPath = '/home/osboxes/Documents/Projects/imgaug/train.py';

        // Get the list of material names
        $materialNames = $readyMaterials->pluck('mat_name')->toArray();

        // Prepare the command with each material name as a separate argument
        $command = array_merge([$pythonPath, $scriptPath], $materialNames);
        $process = new Process($command);
        $process->setTimeout(null); // Importante

        dd($process->getCommandLine());

        try {
            $process->mustRun();
            return back()->with('success', 'Training finished!');

        } catch (ProcessFailedException $exception) {
            
            return back()->withError('Training failed: ' . $exception->getMessage());
        }
    }

    public function resumeModel() 
    {
        # in case of resuming training
        $readyMaterials = Material::where('ready', true)->get();
        $pythonPath = '/home/osboxes/.pyenv/versions/3.9.0/bin/python3.9';
        $scriptPath = '/home/osboxes/Documents/Projects/imgaug/train.py';

        # check .txt files 
        $filePath = '/home/osboxes/Documents/Projects/imgaug/history/progress.txt';

        # check if file exists
        if (!File::exists($filePath)) {
            return back()->with('error', 'Progress file not found.');
        }

        # read the file
        $progress = File::get($filePath);

        # if file is empty, return "No Unfinished Training detected"
        # else, restart the training

        if (empty($progress)) {
            return back()->with('error', 'No Unfinished Training detected.');
        }

        # Get the list of material names
        $materialNames = $readyMaterials->pluck('mat_name')->toArray();

        # Prepare the command with each material name as a separate argument
        $command = array_merge([$pythonPath, $scriptPath], $materialNames);
        $process = new Process($command);
        $process->setTimeout(null); // Importante

        try {
            $process->mustRun();
            return back()->with('success', 'Training finished!');

        } catch (ProcessFailedException $exception) {
            
            return back()->withError('Training failed: ' . $exception->getMessage());
        }
    }
}
