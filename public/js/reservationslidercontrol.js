$(document).ready(function(){

    $('.reservationslider').bxSlider({
        mode: 'fade',
        keyboardEnabled: true,
        controls: true,
        nextSelector: 'a.next',
        prevSelector: 'a.prev',
        nextText: "<img src='/css/images/RightArrow.png'>", // slaidu pārslēgšanas pogas
        prevText: "<img src='/css/images/LeftArrow.png'>",
        infiniteLoop: false,
        hideControlOnEnd: true,
        startSlide: 0,
        adaptiveHeight: true,
        touchEnabled: false,
        pagerCustom: '.slider-reservation'
    });

});