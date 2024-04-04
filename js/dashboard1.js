$(function () {
    "use strict";
if (localStorage.notificacion_estado =='TRUE') {
    $.toast({
            heading: localStorage.notificacion_titulo
            , text: localStorage.notificacion_cuerpo
            , position: 'top-right'
            , loaderBg: '#ff6849'
            , icon: 'info'
            , hideAfter: 3500
            , stack: 6
        })
localStorage.notificacion_estado='FALSE';
}
});
    // sparkline
    var sparklineLogin = function() {
        $('#sales1').sparkline([20, 40, 30], {
            type: 'pie',
            height: '90',
            resize: true,
            sliceColors: ['#01c0c8', '#7d5ab6', '#ffffff']
        });
        $('#sparkline2dash').sparkline([6, 10, 9, 11, 9, 10, 12], {
            type: 'bar',
            height: '154',
            barWidth: '4',
            resize: true,
            barSpacing: '10',
            barColor: '#25a6f7'
        });

    };
    var sparkResize;

        $(window).resize(function(e) {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparklineLogin, 500);
        });
        sparklineLogin();
