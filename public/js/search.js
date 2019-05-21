jQuery('document').ready(function(){

    $('#myresrvsearchinput,.reservsearchcbdiv').on('keyup click',function(){
 console.log(1);
        // Search text
        var text = $('#myresrvsearchinput').val().toLowerCase();

        // Hide all content class element
        $('.searchcontent').hide();
        $('.searchcontent').removeClass('visible');

        var title = date = address = tickets = anotation = 'none';

        if($('#customCheck1').is(':checked')) title = 'eventtitle';
        if($('#customCheck2').is(':checked')) date = 'searcheventdate';
        if($('#customCheck3').is(':checked')) address = 'eventaddress';
        if($('#customCheck4').is(':checked')) tickets = 'ticketsnumber';
        if($('#customCheck5').is(':checked')) tickets = 'searchanotation';

        // Search 
        $('.' + title + ',.' + date + ',.' + address + ',.' + tickets).each(function(){
      
            if($(this).text().toLowerCase().indexOf(""+text+"") != -1 ){
            $('.noresults').remove();
            $(this).closest('.searchcontent').show();
            $(this).closest('.searchcontent').addClass('visible');
            }
        });
        if($('.visible').length == 0){
            if(!$('div').hasClass('noresults'))
                $('<div class="noresults"><h3><i>Nav rezervētu pasākumu.</i></h3></div>').insertAfter('thead');
        }

    });
    function searchevent(){

        $('button#reservsearch').removeClass('active');
        $('button#eventsearch').addClass('active');
        $('#reservationsOptions').fadeOut(200,function(){
            $('#eventOptions').fadeIn(200);
        });
        $("[name='eventsearch']").val('checkevent');
        $("[name='reservatesearch']").val('');
        localStorage.setItem('searchbtn','event');

    }
    function searchreservate(){

        $('button#reservsearch').addClass('active');
        $('button#eventsearch').removeClass('active');
        $('#eventOptions').fadeOut(200,function(){
            $('#reservationsOptions').fadeIn(200);
        });
        $("[name='eventsearch']").val('');
        $("[name='reservatesearch']").val('checkreservation');
        localStorage.setItem('searchbtn','reservate');

    }
    if($('button#reservsearch').hasClass('active')) localStorage.setItem('searchbtn','reservate');
    else if($('button#eventsearch').hasClass('active')) localStorage.setItem('searchbtn','event');

    if(localStorage.getItem('searchbtn') == 'event' || localStorage.getItem('searchbtn') == null) searchevent();
    if(localStorage.getItem('searchbtn') == 'reservate') searchreservate();
    $('button#eventsearch,.searchbtn-small,.searchbtn').click(function(){

    searchevent();

    });
    $('button#reservsearch').click(function(){

        searchreservate();
    });
    var validatelength = 3;

    if($('#customCheck6').is(':checked') && localStorage.getItem('searchbtn') == 'reservate') validatelength = 1;
    else validatelength = 3;
    if($('#customCheck2').is(':checked') && localStorage.getItem('searchbtn') == 'event') validatelength = 1;
    else validatelength = 3;



    if($('#mysearchinput').length > 0){ // ja elements eksistē lapā(lai nebūtu konsoles kļūdas kad meiģina lasīt citā lapā)

    if($('#mysearchinput').val().length < validatelength || $('#mysearchinput').val().length > 50) 
            $('.searchsubmitpage').prop('disabled', true);

    }

    $('#mysearchinput,#customCheck6,#customCheck2,#eventsearch,#reservsearch').on('keyup click change',function(){

        if($('#customCheck6').is(':checked') && localStorage.getItem('searchbtn') == 'reservate') validatelength = 1; // lai kad ir izvēlēti meklēšanas kritēriji cipariem
        else if($('#customCheck2').is(':checked') && localStorage.getItem('searchbtn') == 'event') validatelength = 1;// mazākais skaitlis ko var ievadīt ir 1 nevis 3
        else validatelength = 3;

        if($('#mysearchinput').length > 0){// ja elements eksistē lapā(lai nebūtu konsoles kļūdas kad meiģina lasīt citā lapā)

        if($('#mysearchinput').val().length < validatelength || $('#mysearchinput').val().length > 50) 
            $('.searchsubmitpage').prop('disabled', true);
        else $('.searchsubmitpage').prop('disabled', false);

        }

    });

});