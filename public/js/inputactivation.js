jQuery('document').ready(function(){
    /* Funkcijas kas atslēdz text input negatīva radio izvēles gadījumā */
    function disableseat (){
        if($('input#customRadio2').is(':checked')) {

            jQuery('.eventseat').prop('disabled',true);
            jQuery('.eventseat').val('');
    
        }
        else jQuery('.eventseat').prop('disabled',false);
    };
    function disabletable (){
        if($('input#defaultInline2').is(':checked')) {

            jQuery('.eventtable').prop('disabled',true);
            jQuery('.eventtable').val('');
            jQuery('#tablenr').prop('disabled',true);
            jQuery('#tablenr').val('');

        }
        else {jQuery('.eventtable').prop('disabled',false);
        jQuery('#tablenr').prop('disabled',false);}
    };
    function disabletickets(){
        if($('input#Radio2').is(':checked')) {

            jQuery('.tickets').prop('disabled',true);
            jQuery('.tickets').val('');

        }
        else jQuery('.tickets').prop('disabled',false);
    };
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
        
    });

    $('input[name="customRadio"]').click(function(){

        disableseat();

    });
    

    $('input[name="Radio"]').click(function(){

        disabletickets();

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
          console.log(o);
          o.textContent = o.getAttribute('value');
        });
      }
      [].forEach.call(document.querySelectorAll('#tablenr'), function(s) {
        s.addEventListener('focus', focus);
        s.addEventListener('blur', blur);
        blur.call(s);
      });


      $('#viplength').on('change', function () {
    
        var value = $(this).val();
        
        if (value !== '') {
            
            $(this).val(Math.max(Math.min(value, 30), 10));
        }
    });
});