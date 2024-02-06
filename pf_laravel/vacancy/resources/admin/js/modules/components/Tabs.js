class Tabs {
    constructor() {
        this.init()
    }

    hash = {
        get value() {
            return window.location.hash
        },

        set value(value) {
            history.replaceState(null, null, value);
        },

        is(value) {
            return this.value === value
        }
    }

    links = {
        all() {
            return document.querySelectorAll('[data-toggle="tabs"]')
        },

        find(href) {
            return document.querySelector(`[data-toggle="tabs"][href="${href}"]`)
        },

        onClick(listener) {
            this.all().forEach(link => link.addEventListener('click', listener))
        }
    }

    tabs = {
        buildPathTo(id, pieces = []) {
            let tab = document.getElementById(id)
            if (tab) {
                pieces.unshift(id)

                let parentTab = tab.parentElement.closest('[role="tabpanel"]')
                if (parentTab) {
                    return this.buildPathTo(parentTab.getAttribute('id'), pieces)
                }
            }
            return pieces.join('.')
        }
    }

    init() {
        this.links.onClick(event => {
            event.preventDefault()
            this.show(this.tabs.buildPathTo(event.target.getAttribute('href').slice(1)), true)
            return false
        })

        this.show(this.hash.value.slice(1))
    }

    show(path, changeHash = false) {
        let pieces = path.split('.')

        pieces.forEach(piece => {
            $(this.links.find(`#${piece}`)).tab('show')
        })

        if (changeHash) {
            this.hash.value = `#${path}`
        }
    }
}

window.tabs = new Tabs()
