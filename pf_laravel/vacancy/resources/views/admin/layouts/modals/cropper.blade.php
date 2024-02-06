<div class="modal fade" id="cropper-modal" tabindex="-1" role="dialog" aria-hidden="true" cropper-modal>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default"> {{ __('admin.crop.title') }}</h6>
            </div>

            <div class="modal-body">

                <div class="container-fluid">
                    <div class="d-flex justify-content-center" style="max-height: 500px">
                        <img cropper-modal-image style="object-fit: contain; height: 100%; width: 100%;" src="" alt="">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link ml-auto" data-dismiss="modal" cropper-modal-cancel>{{ __('admin.crop.cancel') }}</button>
                <button type="button" class="btn btn-primary" cropper-modal-save>{{ __('admin.crop.submit') }}</button>
            </div>

        </div>
    </div>
</div>
