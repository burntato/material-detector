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

    public function showTraining()
    {
        $materials = Material::all();
        return view('training', compact('materials'));
    }

    public function trainModel(Request $request)
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

        // dd($process->getCommandLine());

        try {
            $process->mustRun();

            return back()->with('success', 'Training finished!');
        } catch (ProcessFailedException $exception) {
            return back()->withError('Training failed: ' . $exception->getMessage());
        }
    }
}
