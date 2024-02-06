import {Lang} from 'laravel-vue-lang';

require('./bootstrap');

window.Vue = require('vue');

const files = require.context('./components', true, /\.vue$/i)

import {createApp} from 'vue'
import vClickOutside from "click-outside-vue3"

const app = createApp()

files.keys().map(key => {
    app.component(key.split('/').pop().split('.')[0], files(key).default)
})

app.use(Lang, {
    locale: document.documentElement.lang,
    fallback: 'en',
});

app.use(vClickOutside)

app.mount('#app')
