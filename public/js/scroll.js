jQuery('document').ready(function(){

    if ($(this).scrollTop() <= 50) $('.scroll-top').hide();
    if($('.go-top').visible(true)) $('.scroll-top').hide();
    
    $(window).scroll(function() {

        if ($(this).scrollTop() >= 50) { // If page is scrolled more than 50px
            if($('.go-top').visible(true)) $('.scroll-top').hide(200);    
            else $('.scroll-top').show(200);    // Fade in the arrow
        }
        else {
            $('.scroll-top').hide(200);   // Else fade out the arrow
        }
    });
    $('.go-top,.scroll-top').click(function() { 
             // When arrow is clicked
        $('body,html').animate({
            scrollTop : 0                       // Scroll to top of body
        });
    });
if($('input').hasClass('is-invalid')){
    window.scrollTo({
        top: $('.is-invalid').offset().top
    
    });// Scroll to top of body
}
});