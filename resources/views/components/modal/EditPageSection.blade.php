<div class="modal fade" id="modalEditSection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalEditSectionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titles-modal-edit-section"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUpdateSection" class="row g-3">
                    <input type="hidden" name="section_id" id="section_id">
                    <input type="hidden" name="section_type" id="section_type">

                    <div class="col-12 col-md-12" id="section_title_div">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="section_name" name="section_name"
                            placeholder="Name">
                    </div>
                    <div class="col-12" id="section_desc_div">
                        <label class="form-label" for="section_desc">Description</label>
                        <textarea class="form-control" id="section_desc" name="section_desc" rows="5"></textarea>
                    </div>
                    <div class="col-12 col-md-12" id="section_button_div" style="display: none;">
                        <label for="url_button" id="label_button" class="form-label"></label>
                        <input type="text" class="form-control" id="section_url" name="section_url"
                            placeholder="Link">
                    </div>
                    <div class="col-12" id="section_image_div" style="display: none;">
                        <label class="form-label" for="section_image">Image</label>
                        <div class="row mb-2">
                            <div class="col-auto">
                                <img class="img-fluid img-thumbnail" id="modal-preview"
                                    src="https://dummyimage.com/1100x345/005e9d/fff.png">
                                <br><br>
                                <div class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                    <button class="btn-upload">Change Image</button>
                                    <input id="section_image_input" type="file" name="section_image" accept="image/*"
                                        onchange="readURL(this, '#modal-preview');">
                                </div>
                                <input type="hidden" name="hidden_image" id="hidden_image">
                            </div>
                        </div>
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
