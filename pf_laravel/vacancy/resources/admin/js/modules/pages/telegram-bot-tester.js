import {notify} from "../components/Notify";
import axios from "axios";
;(function (window, document) {
    // let textBox = document.getElementById("bot-tester-text");
    let sendButtons = document.getElementsByClassName("send-message");
    // let inputText = document.getElementById("text-message");
    console.log(sendButtons.length)
    if (sendButtons.length > 0) {
        Array.from(sendButtons).forEach(function(sendButton) {
            sendButton.addEventListener("click", (e) => sendMessageEvent(e, sendButton), false);
        });
    }
    // if (inputText) {
    //     inputText.addEventListener("input", inputTextEvent, false);
    // }

    function sendMessageEvent(event, sendButton) {
        let url = sendButton.getAttribute('data-url')
        event.preventDefault()
        sendButton.getAttribute('data-url')
        let text = sendButton.parentNode.parentNode.querySelector('.tester-text').value
        let callback = sendButton.parentNode.parentNode.querySelector('.tester-callback').value

        let body = {
            text: text,
            callback: callback
        }
        new Promise((resolve, reject) => {
            axios.post(url, body)
                .then(result => {
                    let response = result.data;
                    if (response.success) {
                        notify('Отправка сообщения', 'success', 'Успешно!', 1000, 1000)
                    } else {
                        notify('Error', 'danger', response.message + response.messageBlock)
                        reject()
                    }
                }, error => {
                    // notify('Error', 'danger', error.message)
                    reject()
                })
        })
    }

})(window, document);
