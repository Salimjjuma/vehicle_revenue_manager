Chart.defaults.font.family = 'Nunito';

// ajax call to trigger the nprogress to fire.
$(document).ajaxStart(() => {
    NProgress.start();
});

$(document).ajaxComplete(() => {
    NProgress.done();
});