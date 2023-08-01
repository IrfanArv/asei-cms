<div class="modal fade" id="modalEditCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalEditCategoryLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titles-modal-edit"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUpdateCategory" class="row g-3">
                    <input type="hidden" name="cat_id" id="cat_id_edit">
                    <input type="hidden" name="category_type" id="category_type_edit">

                    <div class="col-12 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="cat_name"
                            placeholder="Category Name">
                    </div>
                    <div class="col-12 col-md-12">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="slug" name="cat_slug"
                            placeholder="Category Slug">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="cat_description" rows="3"></textarea>
                    </div>

                    <div class="col-12 text-center">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id="btn-save"
                            value="create">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            function updateSlug(inputValue, slugInput) {
                const slug = inputValue.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
                slugInput.value = slug;
            }

            nameInput.addEventListener('input', function() {
                updateSlug(this.value, slugInput);
            });
        });
    </script>
@endpush
