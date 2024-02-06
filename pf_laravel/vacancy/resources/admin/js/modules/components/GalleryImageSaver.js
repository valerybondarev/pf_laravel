import SingleImageCropper from "./SingleImageCropper";

export default class GalleryImageSaver extends SingleImageCropper {
    constructor(gallery, input) {
        super(input);

        this.gallery = gallery
    }

    initContainer() {
        super.initContainer()
        this.container.showClearButton()
    }

    run() {
        let file = this.getFileInputImage(),
            resolve = result => this.setResult(result),
            reject = () => !this.input.value && this.clear()

        if (file) {
            window.imageCropper.run(file).then(resolve, reject)
        }
        this.container.fileInput.value = ''
    }

    save(image, crop = true, ratio = null) {
        let resolve = result => this.setResult(result),
            reject = () => !this.input.value && this.clear()

        if (crop) {
            window.imageCropper.run(image, ratio).then(resolve, reject)
        } else {
            window.imageSaver.run(image).then(resolve, reject)
        }
    }

    clear() {
        this.container.remove()
        this.input.dispatchEvent(new Event('gallery.item.deleted'))
        delete this
    }

    setResult(result) {
        super.setResult(result);
        this.gallery.processQueue()
    }
}




