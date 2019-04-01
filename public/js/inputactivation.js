jQuery('document').ready(function(){
    /* Funkcijas kas atslēdz text input negatīva radio izvēles gadījumā */
    function disableseat (){
        if($('input#customRadio2').is(':checked')) {

            jQuery('.eventseat').prop('disabled',true);
            jQuery('.eventseat').val('');
            jQuery('.alertseatnr').hide();
            jQuery('[name="seatnr"]').removeClass('is-invalid');
            
        }
        
    };
    function enableseat(){

        if($('input#customRadio1').is(':checked')) {

            jQuery('.eventseat').prop('disabled',false);
            if($('.alertseatnr').text() != ''){

                jQuery('.alertseatnr').show();
                jQuery('[name="seatnr"]').addClass('is-invalid');

            }

        }

    };
    function disabletable (){
        if($('input#defaultInline2').is(':checked')) {

            jQuery('.eventtable').prop('disabled',true);
            jQuery('.eventtable').val('');
            jQuery('#tablenr').prop('disabled',true);
            jQuery('#tablenr').val('');
            jQuery('.alerttablenr').hide();
            jQuery('[name="tablenr"]').removeClass('is-invalid');
            jQuery('[name="seatsontablenr"]').removeClass('is-invalid');

        }
        
    };
    function enabletable(){

        if($('input#defaultInline1').is(':checked')) {

            jQuery('.eventtable').prop('disabled',false);
            jQuery('#tablenr').prop('disabled',false);
            jQuery('#tablenr').val('1');
            if($('.alerttablenr').text() != ''){

                jQuery('.alerttablenr').show();
                jQuery('[name="tablenr"]').addClass('is-invalid');
                jQuery('[name="seatsontablenr"]').addClass('is-invalid');

            }

        }

    };
    function disabletickets(){
        if($('input#Radio2').is(':checked')) {

            jQuery('.tickets').prop('disabled',true);
            jQuery('.tickets').val('');
            jQuery('.alertticketcount').hide();
            jQuery('[name="ticketcount"]').removeClass('is-invalid');

        }
        
    };
    function enabletickets(){
        
        if($('input#Radio1').is(':checked')) {

            jQuery('.tickets').prop('disabled',false);
            if($('.alertticketcount').text() != ''){

                jQuery('.alertticketcount').show();
                jQuery('[name="ticketcount"]').addClass('is-invalid');

            }

        }

    }
    function disabletransport(){
        if($('input#Radio1').is(':checked')) {

            jQuery('#transport').prop('disabled',true);
            jQuery('#transport').val('');

        }
        else {
            jQuery('#transport').prop('disabled',false);
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