jQuery('document').ready(function(){

    function getdata($data,$default = null){ // saņem datus ja ir tukšs izvada null,jeb ja ir otrais arguments tad izvada to,ja nav tukšs izvada datus

        if(isNaN($data)) return $default;
        else return $data;
    
    }
    function getstandcount(){
        var standcount,ticketcount,seatcount,tablecount;

        ticketcount = getdata(parseInt(jQuery('#tickets').val()),0);
        seatcount = getdata(parseInt(jQuery('#seatcount').val()),0);
        tablecount = getdata(parseInt(jQuery('#tablecount').val()),0);

        standcount = ticketcount - (tablecount + seatcount);

        if(standcount < 0) standcount = 0;
        jQuery('.stand-tickets').html(standcount);
    }

    jQuery('.count').on('click keyup',function(){
        getstandcount();
    });
    getstandcount();

});