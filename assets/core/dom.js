app.dom = {
    scrollTo: function (selector) {
        $('html, body').animate({
            scrollTop: selector.offset().top
        }, 1000);
    },
    lock: function (selector, options) {
        $(selector).block({
            css: {
                backgroundColor: 'transparent',
                border: 'none'
            },
            message: '<div class=""> <i class="fa fa-spinner"></i> Cargando.. </div>',
            baseZ: 1500,
            overlayCSS: {
                backgroundColor: '#FFFFFF',
                opacity: 0.7,
                cursor: 'wait'
            }
        });
    },
    unlock: function (selector) {
        $(selector).unblock();
    }
};
