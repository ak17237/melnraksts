var historyslider;
$(document).ready(function(){

    var months = [] // Masīvs mēneša korektai izvadei vārdos
    months[0] = 'Janvāris';
    months[1] = 'Februāris';
    months[2] = 'Marts';
    months[3] = 'Aprīlis';
    months[4] = 'Maijs';
    months[5] = 'Jūnijs';
    months[6] = 'Jūlijs';
    months[7] = 'Augusts';
    months[8] = 'Septembris';
    months[9] = 'Oktobris';
    months[10] = 'Novembris';
    months[11] = 'Decemrbis';

    var today = new Date();
    var todaymonth = today.getMonth(); // saņem šodienas mēnesi
    jQuery('.month').html(months[todaymonth]); // ievieto šodienas mēnesi slaidera galvenē
    jQuery('#historyslidermonth,#mainslidermonth').html(todaymonth); // kad pārslēdz no gaidāmā uz pagājušiem pasākumiem lai pārslēdzas slaidera headera mēnesis

    historyslider = $('.historyslider').bxSlider({ // bxSlider plugina iestatījumi
        mode: 'fade',
        keyboardEnabled: true,
        controls: true,
        nextSelector: 'a.next',
        prevSelector: 'a.prev',
        nextText: "<img class='history' src='svg/right-arrow.svg'>", // slaidu pārslēgšanas pogas
        prevText: "<img class='history' src='svg/left-arrow.svg'>",
        infiniteLoop: false,
        hideControlOnEnd: true,
        startSlide: 5,
        onSlideBefore: function($slideElement,oldIndex,newIndex) { // funkija kura pārslēdz mēnešus galvenē atkarībā no slaida
            
            var index = newIndex - 5; //newIndex ir jauna slaida index pirmais = 0,atkarībā kurš ir sākuma slaids ir jāatņem to no newIndex vērtības
            monthindex = todaymonth + index; // monthindex ir jauna slaida mēneša index ja esošais 0,ja 2 meneši pēc tad 2
            if(monthindex > 11) monthindex = monthindex - 12; // skaitlis nevar būt lielāks par 11 lai korekti izvadīt mēneša masīvu vārdos
            else if(monthindex < 0) monthindex = monthindex + 12;
            
            jQuery('.month').hide().fadeOut('fast').html(months[monthindex]).fadeIn('slow');
            jQuery('#historyslidermonth').html(monthindex); // kad pārslēdz no gaidāmā uz pagājušiem pasākumiem lai pārslēdzas slaidera headera mēnesis
        },
        pagerCustom: '.history-slider-months',
        adaptiveHeight: true,
        touchEnabled: false,
        wrapperClass: 'history-bx bx-wrapper'
    });

    function mainslideractive($element){

        $($element).siblings('button').removeClass('active');
        $($element).addClass('active');
        $('.main').show();
        $('.history').hide();
        $('.history-bx,.history-slider-months').fadeOut(200,function(){
            $('.slider-months,.main-bx').fadeIn(200);
            if($('div').hasClass('bx-wrapper')) mainslider.redrawSlider();
        });
        
        localStorage.setItem("slidertab","present");
        jQuery('.month').hide().fadeOut('fast').html(months[$('#mainslidermonth').text()]).fadeIn('slow'); // kad pārslēdz no gaidāmā uz pagājušiem pasākumiem lai pārslēdzas slaidera headera mēnesis

    }
    function historyslideractive($element){

        $($element).siblings('button').removeClass('active');
        $($element).addClass('active');
        $('.main').hide();
        $('.history').show();
        $('.main-bx,.slider-months').fadeOut(200,function(){
            $('.history-slider-months,.history-bx').fadeIn(200);
            if($('div').hasClass('bx-wrapper')) historyslider.redrawSlider();
        });
        
        localStorage.setItem("slidertab","past");
        

        jQuery('.month').hide().fadeOut('fast').html(months[$('#historyslidermonth').text()]).fadeIn('slow'); // kad pārslēdz no gaidāmā uz pagājušiem pasākumiem lai pārslēdzas slaidera headera mēnesis


    }

    if(localStorage.getItem('slidertab') == null || localStorage.getItem('slidertab') == 'present') mainslideractive($('button#slider'));
    else historyslideractive($('button#historyslider'));

    $('button#slider').click(function(){

        mainslideractive($('button#slider'));
        
    });
    $('button#historyslider').click(function(){

        historyslideractive($('button#historyslider'));

    });
    
    for(var i = -5;i <= 0;i++){

        var index = todaymonth + i;

        if(index > 11) index = index - 12;
        else if(index < 0) index = index + 12;

        jQuery('#h-month'+ i).html(months[index]);
    }

});