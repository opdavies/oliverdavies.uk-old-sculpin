import 'alpinejs'
import '../css/app.pcss'
import turbolinks from 'turbolinks'

window.hljs = require('highlightjs')

let html = document.documentElement
html.classList.remove('no-js')
html.classList.add('js')

turbolinks.start()
