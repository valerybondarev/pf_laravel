import axios from "axios";
import {notify} from "../../components/Notify";

export function crop(image, width, height, x, y) {
    return new Promise((resolve, reject) => {
        let body = new FormData()
        body.append('image', image)
        body.append('width', width)
        body.append('height', height)
        body.append('x', x)
        body.append('y', y)

        axios.post('/admin/tools/images/crop', body)
            .then(result => {
                let response = result.data;
                if (response.success) {
                    let file = response.data

                    resolve({
                        preview: file.url,
                        value: file.id
                    })
                } else {
                    reject()
                }
            }, error => {
                notify('Error', 'danger', error.message)
                reject()
            })
    })

}

