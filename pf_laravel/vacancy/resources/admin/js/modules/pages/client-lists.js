import axios from 'axios'
import {notify} from "../components/Notify";
import {nodeFromString, nodesFromString} from "../tools/NodeFromString";

export default function clientLists() {
    let container = document.getElementById('client-list-container')
    if (container) {
        let list = document.getElementById('client-lists-table'),
            count = container.querySelectorAll('.client-item').length

        function initItem(item) {
            item.querySelector('.client-remove')?.addEventListener('click', e => {
                e.preventDefault()
                item.remove()
                // resolveOrderPrice()
            })
            /*
            item.querySelector('.select-product-count')?.addEventListener('change', e => {
                if (e.target.value.length === 0) {
                    e.target.value = 0
                }
                resolveOrderPrice()
            })
            item.querySelector('.select-product')?.addEventListener('change', e => {
                e.preventDefault()
                item.querySelector('.select-product-price').value = e.target.selectedOptions[0].dataset.price
                resolveOrderPrice()
            })*/
        }

        /**
         * Description Заполнение номеров строк таблицы
         */
        function fillTable(){
            let table = container.querySelector("#client-list-table")
            let arr = Array.from( table.rows );
            arr = arr.slice(1);
            arr.sort( (a, b) => {
                let str  = a.cells[1].textContent;
                let str2 = b.cells[1].textContent;
                return str.localeCompare(str2);
            } );
            table.tBodies[0].append(...arr);
            let headings = document.querySelectorAll('.js-row-number');

            headings.forEach(changeDate);

            function changeDate(heading, index) {
                heading.innerText = index + 1
            }
        }
        /** Перерасчет итоговой стоимости букета */

        /*function resolveOrderPrice() {
            let products = container.querySelectorAll('.order-product')
            let orderPrice = 0
            Array.from(products).forEach(item => {
                let price = item.querySelector('.select-product-price').value
                let count = item.querySelector('.select-product-count').value
                item.querySelector('.select-product-result').innerHTML = (parseFloat(price) * parseInt(count)).toString()
                orderPrice += parseFloat(price) * parseInt(count);
            })
            let orderPriceElement = document.getElementById("order-price")
            orderPriceElement.innerHTML = orderPrice.toString()
        }*/
        function initAdd() {
            container.querySelector('.client-add')?.addEventListener('click', e => {
                e.preventDefault()
                addClient()
            })
            $("#client-selector")?.on('change', e => {
                e.preventDefault()
                addClient()
            })
        }
        function addClient() {
            let clientSelector = document.getElementById('client-selector')
            if (clientSelector.value) {
                if (container.querySelectorAll(`[data-client-id='${clientSelector.value}']`).length > 0) {
                    notify('Ошибка', 'danger', 'Участник уже в списке')
                    return;
                }
                axios.get(`/admin/client-lists/client-add/${clientSelector.value}?index=${++count}`)
                    .then(result => {
                        let item = nodeFromString(result.data.data, 'tr', false)
                        initItem(item)
                        list.append(item)
                        item.classList.add('client-item')
                        item.setAttribute('data-client-id', clientSelector.value)
                        fillTable()
                    })
                    .catch(error => {
                        notify('Error', 'danger', error)
                    })
            } else {
                notify('Ошибка', 'danger', 'Для добавления выберите участника')
            }
        }
        initAdd()
        container.querySelectorAll('.client-item').forEach(initItem)
        fillTable()
    }
}

