@extends('layout')

@section('title', 'Material List')

@section('content')
    <div class="container mt-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">Add Material Data</button>

        <table id="materialsTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Number of Images</th>
                    <th>Last Updated</th>
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
                        <td>
                            <a href="{{ route('materials.edit', $material) }}" class="btn btn-info">Edit</a>
                            <form action="{{ route('materials.destroy', $material) }}" method="POST" style="display:inline;">
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

    @include('materials.modals.add')
@endsection

@push('scripts')
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#materialsTable').DataTable({
                order: [
                    [2, 'desc']
                ]
            });
        });
    </script>
@endpush
