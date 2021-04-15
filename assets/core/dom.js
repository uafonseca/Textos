app.dom = {
    scrollTo: function (selector) {
        $('html, body').animate({
            scrollTop: selector.offset().top
        }, 1000);
    },
    lock: function (selector) {
        $(selector).block({
            css: {
                backgroundColor: 'transparent',
                border: 'none'
            },
            message: '<div class="spinner"></div>',
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
