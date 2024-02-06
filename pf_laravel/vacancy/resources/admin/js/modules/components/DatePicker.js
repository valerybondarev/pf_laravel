export default function datePicker(input) {
    $(input).datepicker({
        icons: {
            time: "fa fa-clock",
            date: "fa fa-calendar-day",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
        },
        format: 'yyyy-mm-dd',
        language: 'ru'
    });
}
