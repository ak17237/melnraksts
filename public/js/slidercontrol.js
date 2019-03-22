$(document).ready(function(){

    var currentslide = 3; // Sākuma slaida numurs

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

    $('.slider').bxSlider({ // bxSlider plugina iestatījumi atrodami mājaslapā
        mode: 'fade',
        keyboardEnabled: true,
        controls: true,
        nextSelector: 'a.next',
        prevSelector: 'a.prev',
        nextText: "<img src='css/images/RightArrow.png'>", // slaidu pārslēgšanas pogas
        prevText: "<img src='css/images/LeftArrow.png'>",
        infiniteLoop: false,
        hideControlOnEnd: true,
        startSlide: 0,
        onSlideBefore: function($slideElement,oldIndex,newIndex) { // funkija kura pārslēdz mēnešus galvenē atkarībā no slaida

            var index = newIndex; //newIndex ir jauna slaida index pirmais = 0
            monthindex = todaymonth + index; // monthindex ir jauna slaida mēneša index ja esošais 0,ja 2 meneši pēc tad 2
            if(monthindex > 11) monthindex = monthindex - 12; // skaitlis nevar būt lielāks par 11 lai korekti izvadīt mēneša masīvu vārdos
            else if(monthindex < 0) monthindex = monthindex + 12;
            
            jQuery('.month').hide().fadeOut('fast').html(months[monthindex]).fadeIn('slow');
        },
        pagerCustom: '.slider-months',
        adaptiveHeight: true
    });
    
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
        });
        $("td.top").click(function() {
            
            if($('.vip').data('clicked') != true)
            window.location = $(this).find("a").attr("href");
         });  
            
        
    
});