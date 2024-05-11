<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'mat_name' => $request->mat_name,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $this->uploadImage($material, $image);
            }
        }

        return redirect()->route('materials.index')->with('success', 'Material created successfully');
    }

    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'mat_name' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $material->update([
            'mat_name' => $request->mat_name,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $this->uploadImage($material, $image);
            }
        }

        return redirect()->route('materials.index')->with('success', 'Material updated successfully');
    }

    public function destroy(Material $material)
    {
        foreach ($material->images as $image) {
            $filePath = public_path('objects/' . $material->id . '/' . $image->img_name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
        }
    
        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Material deleted successfully');
    }
    

    public function uploadImage(Material $material, $image)
    {
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('objects/' . $material->id), $imageName);

        $material->images()->create([
            'img_name' => $imageName,
        ]);

        // update material status
        $material->img_count = $material->images()->count();
        $material->ready = $material->img_count >= 50 ? true : false;
        $material->last_update = now();
        $material->save();
    }

    public function deleteImage(Material $material, Image $image)
    {
        $filePath = public_path('objects/' . $material->id . '/' . $image->img_name);
        if (file_exists($filePath)) {
            unlink($filePath);  // Use unlink to delete the file directly
            Log::info("File deleted successfully.");
        } else {
            Log::info("File does not exist at path: " . $filePath);
        }
    
        $image->delete();
    
        // Update the material's image count and ready status
        $material->img_count = $material->images()->count();
        $material->ready = $material->img_count >= 50;
        $material->last_update = now();
        $material->save();
    
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
    
}
