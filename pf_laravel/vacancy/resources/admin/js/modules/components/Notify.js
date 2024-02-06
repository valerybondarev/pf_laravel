function notify(title, type, message) {
    $.notify(
        {
            icon: 'ni ni-bell-55',
            title: title,
            message: message,
        },
        {
            element: 'body',
            type: type,
            allow_dismiss: true,
            showProgressbar: true,
            offset: {
                x: 15,
                y: 15
            },
            animate: {
                enter: 'animated fadeIn',
                exit: 'animated fadeOut'
            },
            spacing: 10,
            z_index: 1080,
            delay: 2500,
            timer: 25000,
            placement: {
                from: 'bottom',
                align: 'right'
            },
            url_target: '_blank',
            mouse_over: false,
            template: `
                        <div data-notify="container" class="alert alert-dismissible alert-{0} alert-notify" role="alert">
                            <span class="alert-icon" data-notify="icon"></span>
                            <div class="alert-text"</div>
                            <span class="alert-title" data-notify="title">{1}</span>
                            <span data-notify="message">{2}</span>
                            </div>
                                <div class="progress" data-notify="progressbar">
                                <div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <a href="{3}" target="{4}" data-notify="url"></a>
                            <button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>`
        }
    );
}

export {notify}
