<div class="modal fade" id="modalCreateCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalCreateCategoryLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titles-modal"></h5>
                <ul class="nav nav-pills flex-column flex-md-row" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#lang_id" aria-controls="lang_id" aria-selected="true">ID</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#lang_en" aria-controls="lang_en" aria-selected="true">EN</button>
                    </li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCreateCategory" class="row g-3" enctype="multipart/form-data">
                    <input type="hidden" name="category_type" id="category_type">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade active show" id="lang_id" role="tabpanel">
                            <div class="col-12 col-md-12">
                                <label for="name_id" class="form-label">Name (ID)</label>
                                <input type="text" class="form-control" id="name_id" name="name_id"
                                    placeholder="Category Name (ID)">
                            </div>
                            <div class="col-12 col-md-12">
                                <label for="slug_id" class="form-label">Slug (ID)</label>
                                <input type="text" class="form-control" id="slug_id" name="slug_id"
                                    placeholder="Category Slug (ID)">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="description_id">Description (ID)</label>
                                <textarea class="form-control" id="description_id" name="description_id" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="lang_en" role="tabpanel">
                            <div class="col-12 col-md-12">
                                <label for="name_en" class="form-label">Name (EN)</label>
                                <input type="text" class="form-control" id="name_en" name="name_en"
                                    placeholder="Category Name (EN)">
                            </div>
                            <div class="col-12 col-md-12">
                                <label for="slug_en" class="form-label">Slug (EN)</label>
                                <input type="text" class="form-control" id="slug_en" name="slug_en"
                                    placeholder="Category Slug (EN)">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="description_en">Description (EN)</label>
                                <textarea class="form-control" id="description_en" name="description_en" rows="3"></textarea>
                            </div>
                        </div>

                    </div>
                    {{-- <div class="col-12 p-0" id="section_image_div" style="display: none;">
                        <label class="form-label" for="section_image">Image</label>
                        <div class="row mb-2">
                            <div class="col-auto">
                                <img class="img-fluid img-thumbnail" id="modal-preview"
                                    src="https://dummyimage.com/1100x345/005e9d/fff.png">
                                <br><br>
                                <div class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                    <button class="btn-upload">Change Image</button>
                                    <input id="section_image_input" type="file" name="section_image"
                                        accept="image/*" onchange="readURL(this, '#modal-preview');">
                                </div>
                                <input type="hidden" name="hidden_image" id="hidden_image">
                            </div>
                        </div>
                    </div> --}}

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
            const nameIdInput = document.getElementById('name_id');
            const nameEnInput = document.getElementById('name_en');
            const slugIdInput = document.getElementById('slug_id');
            const slugEnInput = document.getElementById('slug_en');
            const formCreateCategory = document.getElementById('formCreateCategory');
            const tabIdLink = document.querySelector('a[data-bs-target="#lang_id"]');
            const tabEnLink = document.querySelector('a[data-bs-target="#lang_en"]');

            function updateSlug(inputValue, slugInput) {
                const slug = inputValue.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
                slugInput.value = slug;
            }

            nameIdInput.addEventListener('input', function() {
                updateSlug(this.value, slugIdInput);
            });

            nameEnInput.addEventListener('input', function() {
                updateSlug(this.value, slugEnInput);
            });
        });
    </script>
@endpush
