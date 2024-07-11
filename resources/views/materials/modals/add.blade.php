<!-- Add Material Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMaterialModalLabel">Add Material Data</h5>
            </div>
            <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="mat_name">Material Name</label>
                        <input type="text" class="form-control" id="mat_name" name="mat_name" required>
                    </div><br>
                    <div class="form-group">
                        <label for="images">Upload Images</label>
                        <input type="file" class="form-control-file" id="images" name="images[]" accept=".jpg, .jpeg, .png" multiple required>
                    </div>
                    <p class="text-muted">Minimum one image required. Accepted formats: jpg, jpeg, png.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmButton">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
