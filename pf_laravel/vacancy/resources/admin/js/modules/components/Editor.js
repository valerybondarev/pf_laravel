import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import Paragraph from "@editorjs/paragraph";
import Quote from "@editorjs/quote";
import List from "@editorjs/list";
import Image from "@editorjs/image";
import Embed from "@editorjs/embed";
import {CsrfToken} from "../tools/CsrfToken";

export default class Editor {
    constructor(input) {
        this.input = input

        this.init()
    }

    init() {
        this.editorJs = new EditorJS({
            holder: this.getArea(),
            data: this.inputValue,
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: true,
                },
                paragraph: {
                    class: Paragraph,
                    inlineToolbar: true,
                },
                quote: {
                    class: Quote,
                    inlineToolbar: true,
                },
                list: {
                    class: List,
                    inlineToolbar: true,
                },
                image: {
                    class: Image,
                    config: {
                        endpoints: {
                            byFile: '/admin/tools/images'
                        },
                        additionalRequestData: {
                            _token: CsrfToken,
                        }
                    },
                },
                embed: {
                    class: Embed,
                    config: {
                        services: {
                            youtube: true
                        }
                    }
                }
            },
            onChange: () => this.save()
        });

        this.input.closest('form')?.addEventListener('submit', () => this.save())
    }

    get inputValue() {
        try {
            return JSON.parse(this.input.value)
        } catch (Error) {
            return null
        }
    }

    set inputValue(value) {
        this.input.value = JSON.stringify(value)
        this.input.dispatchEvent(new Event('change'))
    }

    getArea() {
        let element = document.createElement('div');
        this.input.after(element)

        return element
    }

    save() {
        this.editorJs.save().then(data => {
            this.inputValue = data
        })
    }
}
