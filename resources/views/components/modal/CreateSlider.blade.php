<div class="modal fade" id="modalCreateSliders" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalCreateSlidersLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_slides"></h5>
                <ul class="nav nav-pills flex-column flex-md-row" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#lang_id_slide" aria-controls="lang_id_slide"
                            aria-selected="true">ID</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#lang_en_slide" aria-controls="lang_en_slide"
                            aria-selected="true">EN</button>
                    </li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCreateSliders" class="row g-3" enctype="multipart/form-data">
                    <input type="hidden" name="slider_type" id="slider_type">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade active show" id="lang_id_slide" role="tabpanel">
                            <div class="col-12 col-md-12">
                                <label for="name_id" class="form-label">Name (ID)</label>
                                <input type="text" class="form-control" id="name_id" name="name_id"
                                    placeholder="Title (ID)">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="description_id">Description (ID)</label>
                                <textarea class="form-control" id="description_id" name="description_id" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="lang_en_slide" role="tabpanel">
                            <div class="col-12 col-md-12">
                                <label for="name_en" class="form-label">Name (EN)</label>
                                <input type="text" class="form-control" id="name_en" name="name_en"
                                    placeholder="Title (EN)">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="description_en">Description (EN)</label>
                                <textarea class="form-control" id="description_en" name="description_en" rows="3"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="col-12 col-md-12 p-0">
                        <label for="url_button" id="label_button" class="form-label">Button Link</label>
                        <input type="text" class="form-control" id="link_button" name="link_button"
                            placeholder="Link" value="#">
                    </div>
                    <div class="col-12 p-0">
                        <label class="form-label" for="image_banner">Image</label>
                        <div class="row mb-2">
                            <div class="col-auto">
                                <img class="img-fluid img-thumbnail" id="modal-previews"
                                    src="https://dummyimage.com/1100x345/005e9d/fff.png">
                                <br><br>
                                <div class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                    <button class="btn-upload">Change Image</button>
                                    <input id="image_banner_input" type="file" name="image_banner"
                                        accept="image/*" onchange="readURL(this, '#modal-previews');">
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
