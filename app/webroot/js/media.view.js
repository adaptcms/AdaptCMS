$(document).ready(function() {
    $(".fancybox").fancybox({
        prevEffect: 'fade',
        nextEffect: 'fade',
        helpers: {
            title: {
                type: 'outside'
            },
            thumbs: {
                width: 50,
                height: 50
            }
        }
    });
});