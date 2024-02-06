const {nodeFromString} = require("../tools/NodeFromString");

export default class SingleImageCropper {
    constructor(input) {
        this.input = input

        this.getOptions()
        this.init()
    }

    getOptions() {
        this.options = {};

        if (this.input.hasAttribute('preview')) {
            this.options.preview = this.input.getAttribute('preview') ?? null
        }
        if (this.input.hasAttribute('ratio')) {
            this.options.ratio = this.input.getAttribute('ratio') ?? null
        }
    }

    render() {
        const renderImage = () => {
            return `<img class="crop__preview__img" src="${this.options.preview ?? ''}" alt="">`;
        }

        const renderFileInput = () => {
            return `<input type="file" hidden accept="image/*">`;
        }

        const renderInnerLabel = () => {
            return '<i class="ni ni-image"></i>';
        }


        this.html = `<div class="crop${this.options.preview ? ' cropped' : ''}">
                        <div class="crop__preview">
                            ${renderImage()}
                        </div>
                        <label class="crop__upload">
                            ${renderFileInput()}
                            ${renderInnerLabel()}
                        </label>
                        <div class="crop__buttons">
                            <button class="btn btn-danger btn-icon-only remove" type="button">
                                <i class="fa fa-trash"></i>
                            </button>

                            <button class="btn btn-primary btn-icon-only edit" type="button">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>
                    </div>`
    }

    make() {
        this.container = nodeFromString(this.html)
        this.container.fileInput = this.container.querySelector('input')
        this.container.editButton = this.container.querySelector('button.edit')
        this.container.clearButton = this.container.querySelector('button.remove')

        let previewElement = this.container.querySelector('img')

        Object.defineProperty(this.container, 'preview', {
            set: src => previewElement.setAttribute('src', src),
            get: () => previewElement.getAttribute('src')
        })
    }

    put() {
        this.input.after(this.container)
    }

    initInput() {
        this.input.hidden = true
    }

    getFileInputImage() {
        return [...this.container.fileInput.files].find(file => /^image\/[\w+]+$/.test(file.type))
    }

    run() {
        let image = this.getFileInputImage()

        if (image) {
            window.imageCropper.run(image, this.options.ratio).then(result => this.setResult(result))
        }
        this.container.fileInput.value = ''
    }

    initContainer() {

        this.container.fileInput.addEventListener('change', () => this.run())
        this.container.clearButton.addEventListener('click', () => this.clear())
        this.container.editButton.addEventListener('click', () => this.edit())
        this.container.hideClearButton = () => {
            this.container.clearButton.style.display = 'none';
        }

        this.container.showClearButton = () => {
            this.container.clearButton.style.display = '';
        }

        if (!this.options.preview) {
            this.container.hideClearButton()
        }
    }

    setResult(result) {
        this.input.value = result.value
        this.container.preview = result.preview
        this.container.classList.add('cropped')
        this.container.showClearButton()
    }

    init() {
        this.render()
        this.make()
        this.put()

        this.initInput();
        this.initContainer();
    }

    edit() {
        this.container.fileInput.click();
    }

    clear() {
        this.container.classList.remove('cropped')
        this.input.value = null
        this.container.preview = ''
        this.container.hideClearButton()
    }
}

