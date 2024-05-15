<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('images')->get();
        return view('materials.index', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mat_name' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $material = Material::create([
            'mat_name' => str_replace(' ', '_', $request->mat_name)
        ]);

        // Create a directory based on material name in a sanitized format
        $materialDir = public_path('objects/' . $this->sanitizeFileName($material->mat_name));
        if (!file_exists($materialDir)) {
            mkdir($materialDir, 0775, true); // Make sure the directory is writable
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $this->uploadImage($material, $image, $materialDir);
            }
        }

        return redirect()->route('materials.index')->with('success', 'Material created successfully');
    }

    public function edit(Material $material)
    {
        // sanitize the material name to create a directory
        $material = Material::find($material->mat_id);
        $material->mat_name = $this->sanitizeFileName($material->mat_name);

        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'mat_name' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $oldMaterialName = $material->mat_name;

        $material->update([
            'mat_name' => $request->mat_name,
        ]);

        $newMaterialDir = public_path('objects/' . $this->sanitizeFileName($material->mat_name));
        if (!file_exists($newMaterialDir)) {
            mkdir($newMaterialDir, 0775, true);
        }

        // If mat_name has changed, move the files from the old directory to the new one
        if ($oldMaterialName !== $request->mat_name) {
            $oldMaterialDir = public_path('objects/' . $this->sanitizeFileName($oldMaterialName));
            if (file_exists($oldMaterialDir)) {
                foreach (glob($oldMaterialDir . '/*') as $oldFile) {
                    $newFile = str_replace($oldMaterialDir, $newMaterialDir, $oldFile);
                    rename($oldFile, $newFile);
                }
                // Remove the old directory
                rmdir($oldMaterialDir);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $this->uploadImage($material, $image, $newMaterialDir);
            }
        }

        return redirect()->route('materials.index')->with('success', 'Material updated successfully');
    }

    public function destroy(Material $material)
    {
        $materialDir = public_path('objects/' . $this->sanitizeFileName($material->mat_name));
        foreach ($material->images as $image) {
            $filePath = $materialDir . '/' . $image->img_name;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
        }

        // Remove the directory if it's empty
        if (is_dir($materialDir) && count(scandir($materialDir)) == 2) {
            // PHP includes . and .. as entries
            rmdir($materialDir);
        }

        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Material deleted successfully');
    }

    public function uploadImage(Material $material, $image, $materialDir)
    {
        $imageName = uniqid() . '_' . $image->getClientOriginalName();
        $image->move($materialDir, $imageName);

        $material->images()->create([
            'img_name' => $imageName,
        ]);

        $material->img_count = $material->images()->count();
        $material->ready = $material->img_count >= 50 ? true : false;
        $material->last_update = now();
        $material->save();
    }

    private function sanitizeFileName($filename)
    {
        return preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename);
    }

    public function deleteImage(Material $material, Image $image)
    {
        $materialDir = public_path('objects/' . $this->sanitizeFileName($material->mat_name));
        $filePath = $materialDir . '/' . $image->img_name;
        if (file_exists($filePath)) {
            unlink($filePath);
            Log::info('File deleted successfully.');
        } else {
            Log::info('File does not exist at path: ' . $filePath);
        }

        $image->delete();
        $material->img_count = $material->images()->count();
        $material->ready = $material->img_count >= 50;
        $material->last_update = now();
        $material->save();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
