var mainslider;
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

    mainslider = $('.slider').bxSlider({ // bxSlider plugina iestatījumi
        mode: 'fade',
        keyboardEnabled: true,
        controls: true,
        nextSelector: 'a.next',
        prevSelector: 'a.prev',
        nextText: "<img class='main' src='svg/right-arrow.svg'>", // slaidu pārslēgšanas pogas
        prevText: "<img class='main' src='svg/left-arrow.svg'>",
        infiniteLoop: false,
        hideControlOnEnd: true,
        startSlide: 0,
        onSlideBefore: function($slideElement,oldIndex,newIndex) { // funkija kura pārslēdz mēnešus galvenē atkarībā no slaida
            
            var index = newIndex; //newIndex ir jauna slaida index pirmais = 0
            monthindex = todaymonth + index; // monthindex ir jauna slaida mēneša index ja esošais 0,ja 2 meneši pēc tad 2
            if(monthindex > 11) monthindex = monthindex - 12; // skaitlis nevar būt lielāks par 11 lai korekti izvadīt mēneša masīvu vārdos
            else if(monthindex < 0) monthindex = monthindex + 12;
            
            jQuery('.month').hide().fadeOut('fast').html(months[monthindex]).fadeIn('slow');
            jQuery('#mainslidermonth').html(monthindex); // kad pārslēdz no gaidāmā uz pagājušiem pasākumiem lai pārslēdzas slaidera headera mēnesis
        },
        pagerCustom: '.slider-months',
        adaptiveHeight: true,
        touchEnabled: false,
        wrapperClass: 'main-bx bx-wrapper'
    });
    
    for(var i = 1;i <= $('#counter').text(); i++){

    var eventdate = new Date($('#eventdate' + i).text());
    jQuery('.pagmonth' + i).html(months[eventdate.getMonth()]);

    }
    // pogu zem slidera mēnešu uzstādīšana
        for(var i = 0;i <= 5;i++){

            var index = todaymonth + i;

            if(index > 11) index = index - 12;
            else if(index < 0) index = index + 12;

            jQuery('#month'+ i).html(months[index]);

        }
        $(".vip").mouseover(function(){
            $(this).data('clicked', true);
            
        });
        $(".vip").mouseout(function(){
            $(this).data('clicked', false);
            
        });/* $(e.target).closest('td').find('popover') */
        $("td.clickshow").click(function(e) {
            if($('.vip').data('clicked') != true && $(e.target).is('.close') == false && !$(e.target).closest('tr').find('div.popover').hasClass('popover'))
                if(!$(this).closest('tr').hasClass('expiredevent'))
            window.location = $(this).find("a").attr("href");
         });
         $(".download").mouseover(function(){
            $(".download").data('hover', true);
        });
        $(".download").mouseout(function(){
            $(".download").data('hover', false);
        });
        $(".pdfdownload > div").click(function(e){
            e.stopPropagation(); 
         });
         $('.clickdownload').click(function(){

            if($('.download').data('hover') != true)
                window.open($(this).find("a").attr("href"),'_blank');
                
         });
         $('.bx-viewport').height($('.bx-viewport').height() + 20);
         
         $('tr.expiredevent').find('button').prop('disabled','disabled');
         $('tr.expiredevent').find('a:not(.today)').removeClass('button').addClass('inactive');
         
            $('.reporttooltip').tooltip({title: 'Atskaite būs pieejama nākošajā pasākuma dienā', placement: "top",trigger: 'hover',container: '.questiontooltip'});

    
});