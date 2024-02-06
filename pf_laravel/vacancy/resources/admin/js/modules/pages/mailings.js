import axios from 'axios'
import {notify} from "../components/Notify";
import {nodeFromString} from "../tools/NodeFromString";

export default function mailings() {
    let container = document.getElementById('mailing-container')
    if (container) {
        function initSend() {
            container.querySelector('#send-test-mailing')?.addEventListener('click', e => {
                e.preventDefault()
                let data = new FormData(document.getElementById('mailing-form'));
                //**form to json
                // let mailingForm = {};
                // let formData = document.querySelector('#mailing-form').querySelectorAll('.form-control')
                // formData.forEach((element) => mailingForm[element.getAttribute("name")] = element.value);
                //**
                axios.post(`/admin/mailings/send-test`, data)
                    .then(result => {
                        if (result.data.success) {
                            notify('Успешно', 'success', 'Сообщение успешно отправлено')
                        } else {
                            notify('Ошибка отправки', 'danger', result.data.error)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            for (const key of Object.keys(error.response.data.errors)) {
                                notify('Error', 'danger', error.response.data.errors[key][0])
                            }
                        } else {
                            notify('Error', 'danger', error)
                        }
                    })
            })
        }

        initSend()
    }
    let containerButtons = document.getElementById('mailing-buttons-container')
    if (containerButtons) {
        let count = containerButtons.querySelectorAll('.mailing-button').length
        containerButtons.querySelector('#add-mailing-button')?.addEventListener('click', e => {
            e.preventDefault()
            let buttonType = containerButtons.querySelector('#buttonType').value
            let buttonList = containerButtons.querySelector('.mailing-button-list')
            axios.get(`/admin/mailings/get-button`,
                {
                    params: {
                        action: buttonType,
                        index: ++count
                    }
                }
            )
                .then(result => {
                    if (result.data.success) {
                        let item = nodeFromString(result.data.data)
                        initItem(item)
                        buttonList.append(item)
                        notify('Успешно', 'success', 'Кнопка добавлена! Напишите название кнопки')
                    } else {
                        notify('Ошибка добавления', 'danger', result.data.error)
                    }
                })
                .catch(error => {
                    notify('Error', 'danger', error)
                })
        })

        function initItem(item) {
            item.querySelector('.mailing-button-remove')?.addEventListener('click', e => {
                e.preventDefault()
                item.remove()
            })
        }

        containerButtons.querySelectorAll('.mailing-button').forEach(initItem)
    }
}

