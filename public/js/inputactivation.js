jQuery('document').ready(function(){
    /* Funkcijas kas atslēdz text input negatīva radio izvēles gadījumā */
    var seatval = tableval = seatontableval = ticketval = transportval = tablecount = "";
    function disableseat (){
        if($('input#customRadio2').is(':checked')) {

            jQuery('.eventseat').prop('disabled',true);
            seatval = jQuery('.eventseat').val();
            jQuery('.eventseat').val('');
            jQuery('.alertseatnr').hide();
            jQuery('.alertradio').show();
            jQuery('[name="seatnr"]').removeClass('is-invalid');
            
        }
        
    };
    function enableseat(){

        if($('input#customRadio1').is(':checked')) {

            jQuery('.eventseat').prop('disabled',false);
            jQuery('.eventseat').val(seatval);
            if($('.alertseatnr').text() != ''){

                jQuery('.alertseatnr').show();
                jQuery('.alertradio').show();
                jQuery('[name="seatnr"]').addClass('is-invalid');

            }

        }

    };
    function disabletable (){
        if($('input#defaultInline2').is(':checked')) {

            jQuery('.eventtable').prop('disabled',true);
            tableval =  jQuery('#eventtable').val();
            seatontableval = jQuery('#seatsontable').val();
            tablecount = jQuery('#tablecount').val();
            jQuery('.eventtable').val('');
            jQuery('#tablenr').prop('disabled',true);
            jQuery('#tablenr').val('');
            jQuery('.alertinline').show();
            jQuery('.alerttablenr').hide();
            jQuery('.alertseattable').hide();
            jQuery('.alerttablecount').hide();
            jQuery('[name="tablenr"]').removeClass('is-invalid');
            jQuery('[name="seatsontablenr"]').removeClass('is-invalid');
            jQuery('[name="tablecount"]').removeClass('is-invalid');

        }
        
    };
    function enabletable(){

        if($('input#defaultInline1').is(':checked')) {

            jQuery('.eventtable').prop('disabled',false);
            jQuery('#eventtable').val(tableval);
            jQuery('#seatsontable').val(seatontableval);
            jQuery('#tablecount').val(tablecount);
            jQuery('#tablenr').prop('disabled',false);
            jQuery('#tablenr').val('1');
            if($('.alerttablenr').text() != ''){

                jQuery('.alerttablenr').show();
                jQuery('[name="tablenr"]').addClass('is-invalid');

            }
            if($('.alertseattable').text() != ''){

                jQuery('.alertseattable').show();
                jQuery('[name="seatsontablenr"]').addClass('is-invalid');

            }
            if($('.alerttablecount').text() != ''){
                
                jQuery('.alerttablecount').show();
                jQuery('[name="tablecount"]').addClass('is-invalid');

            }

        }

    };
    function disabletickets(){
        if($('input#Radio2').is(':checked')) {

            jQuery('.tickets').prop('disabled',true);
            ticketval =  jQuery('.tickets').val();
            jQuery('.tickets').val('');
            jQuery('.alertticketcount').hide();
            jQuery('[name="ticketcount"]').removeClass('is-invalid');       

        }
        
    };
    function enabletickets(){
        
        if($('input#Radio1').is(':checked')) {

            jQuery('.tickets').prop('disabled',false);
            jQuery('.tickets').val(ticketval);
            if($('.alertticketcount').text() != ''){

                jQuery('.alertticketcount').show();
                jQuery('[name="ticketcount"]').addClass('is-invalid');

            }

        }

    }
    function disabletransport(){
        if($('input#Radio1').is(':checked')) {

            jQuery('#transport').prop('disabled',true);
            transportval =  jQuery('#transport').val();
            jQuery('#transport').val('');
        }
        else {
            jQuery('#transport').prop('disabled',false);
            jQuery('#transport').val(transportval);
            $("#transport option[value='Empty']").remove();
            /* jQuery('#transport').val('Riga'); */
        }
    };
    $('input[name="inlineDefaultRadiosExample"]').click(function(){ 

        disabletable();
        enabletable();
        
    });

    $('input[name="customRadio"]').click(function(){
        disableseat();
        enableseat();

    });
    

    $('input[name="Radio"]').click(function(){

        disabletickets();
        enabletickets();

    });
    
    $('input[name="TransportRadio"]').click(function(){

        disabletransport();

    });
    disableseat();
    disabletable();
    disabletickets();
    disabletransport();

    $('textarea').each(function(){ // labo kļūdu,kad ievietojot datus tagā textarea ieliekas liela atstarpe no sākuma php koda dēļ htmlā
        $(this).val($(this).val().trim());
    });
    $('input[name="file"]').change(function(){ // lai parādītos faila izvēles laukā izvēlētā faila nosaukums
        if($('input[name="file"]').val() == '') $('#filename').html('Choose file'); 
        else $('#filename').html($('input[name="file"]').val().replace(/C:\\fakepath\\/i, ''));
    });
     
        var ticketinfotext = 'Atlikušās biļetes no kurām ' + $('#chseat').text() + 
        ' ir sēdvietas un ' + $('#chtable').text() + 
        ' ir sēdvietas pie galdiem,pārējās ir stāvvietas(' + $('#chstand').text() + ')';

        if($('#ticketinfo').text() === "Neierobežots"){

            if($('#chseat').text() != 0 && $('#chtable').text() != 0) ticketinfotext = 'Šajā pasākumā ir ierobežotas sēdvietas,kuru atlikušais skaits ir: ' + $('#chseat').text() + 
                ' un sēdvietas pie galdiem,kuru atlikušais skaits ir: ' + $('#chtable').text() + ' pārējās ir stāvvietas';

            else if($('#chseat').text() == 0) ticketinfotext = 'Šajā pasākumā ir ierobežotas sēdvietas pie galdiem,kuru atlikušais skaits ir: ' + $('#chtable').text() + 
            ' pārējās ir stāvvietas. Parastās sēdvietas nav paredzētas.';

            else if($('#chtable').text() == 0) ticketinfotext = 'Šajā pasākumā ir ierobežotas sēdvietas,kuru atlikušais skaits ir: ' + $('#chseat').text() + 
            ' pārējās ir stāvvietas. Sēdvietas pie galdiem nav paredzētas.';

        }
        $('#tickettooltip').tooltip({title: ticketinfotext, placement: "top",trigger: 'hover',container: '.questiontooltip'});

    $('#reserveditabletooltip').tooltip({title: 'Vai lietotāji varēs rediģēt savas rezervācijas šim pasākumam vai nē', placement: "top",trigger: 'hover',container: '.questiontooltip'});
    
    $('#datefrom,#dateto').change(function(){

        var datefrom  = new Date($('#datefrom').val());
        var dateto  = new Date($('#dateto').val());
         if(datefrom > dateto) $('#dateto').val($('#datefrom').val());

    });
    function focus() {
        [].forEach.call(this.options, function(o) {
          o.textContent = o.getAttribute('value') + ' (' + o.getAttribute('data-descr') + ')';
        });
      }
      function blur() {
        [].forEach.call(this.options, function(o) {
          o.textContent = o.getAttribute('value');
        });
      }
      [].forEach.call(document.querySelectorAll('#tablenr'), function(s) {
        s.addEventListener('focus', focus);
        s.addEventListener('blur', blur);
        blur.call(s);
      });
});