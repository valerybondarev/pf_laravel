import {CsrfToken} from "../tools/CsrfToken";
import {nodeFromString} from "../tools/NodeFromString";
import BaseDropzone from "dropzone/dist/dropzone";

BaseDropzone.autoDiscover = false

export default class Dropzone {
    constructor(input) {
        this.input = input
        this.init()
    }

    config() {
        this.multiple = this.input.hasAttribute('multiple')
        this.inputName = this.input.getAttribute('name')
    }

    create() {
        this.dropzoneElement = nodeFromString(`
                <div class="dropzone dropzone-multiple">
                    <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                    </ul>
                    <div class="dz-default dz-message"><span class="icon-lg"><i class="ni ni-cloud-upload-96"></i></span></div>
                </div>
        `)
        this.container = this.dropzoneElement.querySelector('.dz-preview')
        this.template = `<li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="avatar">
                                        <i class="ni ni-cloud-upload-96"></i>
                                    </div>
                                </div>
                                <div class="col ml--3">
                                    <h4 class="mb-1" data-dz-name>...</h4>
                                    <p class="small text-muted mb-0" data-dz-size>...</p>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="text-danger" data-dz-remove role="button">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </li>`
    }

    createValue(value) {
        return nodeFromString(`<input type="text" hidden value="${value}" name="${this.inputName}">`)
    }

    appendExisting() {
        let files = [];
        try {
            files = JSON.parse(this.input.dataset.files)
        } catch (e) {
        }

        files.forEach(file => {
            let mockFile = {
                status: 'success',
                name: file.title,
                size: file.size,
                accepted: true,
            }
            this.dropzone.files.push(mockFile);
            this.dropzone.emit("addedfile", mockFile);
            this.dropzone.emit("complete", mockFile);
        })
    }

    init() {
        this.config()
        this.create()
        this.input.after(this.dropzoneElement)
        this.dropzone = new BaseDropzone(this.dropzoneElement, {
            url: '/admin/tools/files',
            params: {
                _token: CsrfToken
            },
            previewsContainer: this.container,
            previewTemplate: this.template,
            maxFiles: this.multiple ? null : 1,
        })
        this.dropzone.on("complete", file => {
            if (file.xhr) {
                let response = JSON.parse(file.xhr.response)
                file.previewElement.append(this.createValue(response.data.id))
            }
        })

        this.appendExisting()
    }
}
