import GalleryImageSaver from "./GalleryImageSaver";
import {nodeFromString} from "../tools/NodeFromString";
import Sortable from 'sortablejs';
import {ask} from "./Ask";

export default class Gallery {
    constructor(container) {
        this.container = container
        this.inputName = this.container.getAttribute('gallery') ?? 'gallery[]'
        this.init()
    }

    render() {
        return `<div class="col-md-5 m-2 crop__container" gallery-image>
                    <input hidden name="${this.inputName}">
                </div>`
    }

    createImage(image, crop = true) {
        let item = nodeFromString(this.render())
        this.initImage(item, image, crop)
        this.imagesContainer.append(item)
        item.imageSaver.save(image, crop)
    }

    initImage(container) {
        let input = container.querySelector('input')
        container.imageSaver = new GalleryImageSaver(this, input)
        input.addEventListener('gallery.item.deleted', () => container.remove())
    }

    initImages() {
        this.imagesContainer = this.container.querySelector('[gallery-images]')
        this.imagesContainer.querySelectorAll('[gallery-image]').forEach(container => this.initImage(container))
        Sortable.create(this.imagesContainer, {sort: true})
    }

    initUploader() {
        this.uploaderElement = this.container.querySelector('[gallery-upload]')

        let initFileInput = () => {
                let input = document.createElement('input')
                input.setAttribute('type', 'file')
                input.setAttribute('multiple', 'multiple')
                input.setAttribute('accept', 'image/*')
                input.addEventListener('change', () => this.run())
                this.fileInput = input
            },
            fileInputUploader = () => {
                this.uploaderElement.addEventListener('click', event => {
                    event.preventDefault()
                    this.fileInput.click()
                })
            },
            dragDropUploader = () => {
                let contentElement = this.uploaderElement.querySelector('.gallery__upload__content')

                contentElement.addEventListener('dragover', event => {
                    event.preventDefault()
                    contentElement.classList.add('gallery__upload__content--active')
                })

                contentElement.addEventListener('dragleave', () => {
                    event.preventDefault()
                    contentElement.classList.remove('gallery__upload__content--active')
                })

                contentElement.addEventListener('drop', event => {
                    event.preventDefault()
                    this.fileInput.files = event.dataTransfer.files
                    this.fileInput.dispatchEvent(new Event('change'))
                })
            }

        initFileInput()
        fileInputUploader()
        dragDropUploader()
    }

    init() {
        this.initImages()
        this.initUploader()
    }

    run() {
        this.initQueue()

        ask(`Обрезать ${this.queue.images.count() > 1 ? 'изображения' : 'изображение'}?`, 'Да', 'Нет', 'question')
            .then(() => this.startQueue(true))
            .catch(() => this.startQueue(false))

    }

    initQueue() {
        this.queue = {
            images: {
                items: [...this.fileInput.files].filter(file => /^image\/[\w+]+$/.test(file.type)),
                count() {
                    return this.items.length;
                },
                shift() {
                    return this.items.shift()
                }
            },
            crop: false,
        }
    }

    startQueue(crop = true) {
        this.queue.crop = crop
        this.processQueue()
    }

    processQueue() {
        if (this.queue.images.count()) {
            this.createImage(this.queue.images.shift(), this.queue.crop)
        } else {
            this.fileInput.value = ''
        }
    }
}
