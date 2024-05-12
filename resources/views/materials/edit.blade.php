@extends('layout')

@section('title', 'Edit Material')

@section('content')
<div class="container mt-4">
    <h3>Editing Material: {{ $material->mat_name }}</h3>
    <form action="{{ route('materials.update', $material) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="mat_name">Material Name</label>
            <input type="text" class="form-control" id="mat_name" name="mat_name" value="{{ $material->mat_name }}" required>
        </div>
        <div class="form-group">
            <label for="new_images">Add New Images</label>
            <input type="file" class="form-control-file" id="new_images" name="images[]" accept=".jpg, .jpeg, .png" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Update Material</button>
    </form>
    <hr>
    <h4>Current Images</h4>
    <div class="row">
        @foreach ($material->images as $image)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ asset('objects/' . $material->mat_name . '/' . $image->img_name) }}" class="card-img-top" alt="{{ $image->img_name }}">
                    <div class="card-body">
                        <p class="card-text">{{ $image->img_name }}</p>
                        <form action="{{ route('materials.images.delete', ['material' => $material, 'image' => $image]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

