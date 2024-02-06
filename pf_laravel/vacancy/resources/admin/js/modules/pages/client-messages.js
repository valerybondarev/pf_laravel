import {notify} from "../components/Notify";
import axios from "axios";
;(function (window, document) {
    let messages = document.getElementById("messages");
    let sendButton = document.getElementById("send-message");
    let inputText = document.getElementById("text-message");

    if (sendButton) {
        sendButton.addEventListener("click", sendMessageEvent, false);
    }
    if (inputText) {
        inputText.addEventListener("input", inputTextEvent, false);
    }

    function sendMessageEvent() {
        event.preventDefault()
        sendButton.setAttribute('disabled', true)

        let body = {
            text: inputText.value
        }
        new Promise((resolve, reject) => {
            axios.post(inputText.getAttribute('data-url'), body)
                .then(result => {
                    let response = result.data;
                    if (response.success) {
                        inputText.value = ''
                        // console.log(wrapper)
                        console.log('asd')
                        console.log(response.messageBlock)
                        var wrapper = document.createElement('div')
                        wrapper.innerHTML = response.messageBlock

                        messages.append(wrapper)
                        messages.scrollTo(0, messages.scrollHeight)
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

    function inputTextEvent() {
        if (inputText.value.length > 0) {
            sendButton.removeAttribute('disabled')
        } else {
            sendButton.setAttribute('disabled', '')
        }
    }
    if (messages) {
        messages.scrollTo(0, messages.scrollHeight)
    }
})(window, document);
