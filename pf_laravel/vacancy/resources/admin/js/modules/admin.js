import './components/Preloader'
import './components/Notify'
import './components/Tabs'
import './components/ImageCropper'
import './components/ImageSaver'
import './pages/client-messages'
import './pages/client-events'
import './pages/telegram-bot-tester'

import datetimePicker from "./components/DatetimePicker";
import datePicker from "./components/DatePicker";
import SingleImageCropper from './components/SingleImageCropper'
import Gallery from "./components/Gallery";
import Editor from "./components/Editor";
import Dropzone from "./components/Dropzone";
import phoneMask from "./components/PhoneMask";
import clientLists from "./pages/client-lists";
import mailings from "./pages/mailings";
document.querySelectorAll('[single-image-cropper]').forEach(input => {
    new SingleImageCropper(input)
})
document.querySelectorAll('[gallery]').forEach(input => {
    new Gallery(input)
})
document.querySelectorAll('[editor]').forEach(input => {
    new Editor(input)
})
document.querySelectorAll('[dropzone]').forEach(input => {
    new Dropzone(input)
})
document.querySelectorAll('[phone-mask]').forEach(input => {
    phoneMask(input)
})
document.querySelectorAll('[datetime-picker]').forEach(input => {
    datetimePicker(input)
})
document.querySelectorAll('[date-picker]').forEach(input => {
    datePicker(input)
})

document.addEventListener('DOMContentLoaded', () => {
    clientLists()
    mailings()
})