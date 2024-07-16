@extends('layout')

@section('title', 'Material List')

@section('content')
    <div class="container mt-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">Add Material
            Data</button><br>
        <hr>

        <h3>Material List</h3>
        <p>A Minimum of 50 Materials is needed to qualify one material as ready for training.</p>
        <table id="materialsTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Number of Images</th>
                    <th>Last Updated</th>
                    <th>Ready</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($materials as $material)
                    <tr>
                        <td>{{ $material->mat_name }}</td>
                        <td style="{{ $material->img_count >= 50 ? 'background-color: #90EE90;' : '' }}">
                            {{ $material->img_count }}
                        </td>
                        <td>{{ $material->last_update }}</td>
                        <td>{{ $material->ready ? 'true' : 'false' }}</td>
                        <td>
                            <a href="{{ route('materials.edit', $material) }}" class="btn btn-info">Edit</a>
                            <form action="{{ route('materials.destroy', $material) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#materialsTable').DataTable({
                order: [
                    [2, 'desc']
                ],
                lengthChange: false,
                pageLength: 15
            });
        });
    </script>

    @include('materials.modals.add')
@endsection
