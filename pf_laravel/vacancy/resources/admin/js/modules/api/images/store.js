import axios from "axios";
import {notify} from "../../components/Notify";

export function store(image) {
    return new Promise((resolve, reject) => {
        let body = new FormData()
        body.append('image', image)

        axios.post('/admin/tools/images', body)
            .then(result => {
                let response = result.data;
                if (response.success) {
                    let file = response.file

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

