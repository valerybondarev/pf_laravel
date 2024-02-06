import Swal from "sweetalert2";

function ask(question, confirmText, cancelText = null, icon = null, title = null) {
    return new Promise((resolve, reject) => {
        Swal
            .fire({
                title: title,
                text: question,
                icon: icon, // success, info, warning, error, question
                showCancelButton: !!cancelText,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
            })
            .then(result => {
                result.isConfirmed ? resolve() : reject()
            })
            .catch(() => reject && reject())
    })
}

export {ask}
