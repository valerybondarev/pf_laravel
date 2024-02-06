import CropperJs from 'cropperjs'
import 'cropperjs/dist/cropper.min.css'
import {crop as cropImage} from "../api/images/crop";

class ImageCropper {
    constructor() {
        this.init();
    }

    static getModal() {
        let modal = document.querySelector('[cropper-modal]');
        if (modal) {
            modal.image = document.querySelector('[cropper-modal] [cropper-modal-image]')
            modal.cancelButton = document.querySelector('[cropper-modal] [cropper-modal-cancel]')
            modal.saveButton = document.querySelector('[cropper-modal] [cropper-modal-save]')

            return modal
        }
        return null
    }

    initModal() {
        let modal = ImageCropper.getModal()

        modal.open = (opened, confirmed, closed) => {
            $(modal).one('shown.bs.modal', () => opened())
            $(modal.saveButton).one('click', () => confirmed())
            $(modal.cancelButton).one('click', () => closed())
            $(modal).modal({backdrop: 'static', keyboard: false})
        }

        modal.close = closed => {
            $(modal).one('hidden.bs.modal', closed)
            $(modal).modal('hide')
        }

        modal.disableButtons = () => {
            modal.saveButton.setAttribute('disabled', 'disabled');
        }

        modal.enableButtons = () => {
            modal.saveButton.removeAttribute('disabled');
        }

        Object.defineProperty(modal, 'src', {
            set: (src) => src ? modal.image.setAttribute('src', src) : modal.image.removeAttribute('src'),
            get: () => modal.image.getAttribute('src'),
        })

        this.modal = modal
    }

    init() {
        this.initModal()
    }

    save(file) {
        let data = this.cropper.getData()

        return cropImage(
            file,
            Math.round(data.width),
            Math.round(data.height),
            Math.round(data.x),
            Math.round(data.y)
        )
    }

    runCropper(ratio = null) {
        this.cropper = new CropperJs(this.modal.image, {
            zoomable: false,
            viewMode: 1,
            checkImageOrigin: false,
            aspectRatio: ratio,
        });
    }

    readFile(file) {
        return new Promise(function (read) {
            let fileReader = new FileReader();
            fileReader.readAsDataURL(file);
            fileReader.onload = () => read(fileReader.result)
        })
    }

    reset() {
        return new Promise(resolve => {
            this.modal.close(() => {
                this.cropper.destroy()
                this.modal.src = null
                this.modal.enableButtons()
                resolve()
            })
        })
    }

    run(file, ratio = null) {
        return new Promise((success, failure) => {
            this.readFile(file)
                .then(data => {
                    this.modal.src = data
                    let cancelled = false
                    this.modal.open(
                        () => this.runCropper(ratio),
                        () => {
                            this.modal.disableButtons()

                            this.save(file)
                                .then(result => {
                                    this.reset().then(() => {
                                        !cancelled && success(result)
                                    })
                                })
                                .catch(error => {
                                    this.reset().then(() => {
                                        failure(error)
                                    })
                                })
                        },
                        () => {
                            cancelled = true
                            this.reset().then(() => {
                                failure()
                            })
                        }
                    )
                })
        })
    }
}

if (ImageCropper.getModal()) {
    window.imageCropper = new ImageCropper();
}
