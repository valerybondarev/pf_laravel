import {store as storeImage} from './../api/images/store'

class ImageSaver {
    run(file) {
        return storeImage(file)
    }
}

window.imageSaver = new ImageSaver();
