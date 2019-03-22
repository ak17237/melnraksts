jQuery('document').ready(function(){

    function VIPtooltip(number){

        $('.homecopybtn' + number).tooltip({title: 'Uzspiest lai nokopēt rezervācijas linku vip pasākumam', placement: "bottom",trigger: 'hover'});

        jQuery('.homecopybtn' + number).click(function(){
     
         $('.homecopybtn' + number).tooltip('dispose');
         $('.homecopybtn' + number).tooltip({title: 'Copied', placement: "bottom",trigger: 'hover',delay: {show: 500, hide: 100}});
         $('.homecopybtn' + number).tooltip('show');
     
         setTimeout(function(){
     
             $('.homecopybtn' + number).tooltip('hide');
             $('.homecopybtn' + number).tooltip('dispose');
             $('.homecopybtn' + number).tooltip({title: 'Uzspiest lai nokopēt rezervācijas linku vip pasākumam', placement: "bottom",trigger: 'hover'});
     
         }, 1000);
     
         $('.homecopybtn' + number).popover({title: "Rezervācijas links", content: jQuery('#linkcopy' + number).val(),placement: 'top',trigger: 'manual'}); 
         $('.homecopybtn' + number).popover("show");
         
         $('.popover-header').append('<a href="#" id="popoverclose" class="close" data-dismiss="alert">&times;</a>');
     
         jQuery('#linkcopy' + number).select();
         document.execCommand("copy");
     
         });
         $('.homecopybtn' + number).click(function (e) {
             e.stopPropagation();
         });
         
         $(document).click(function (e) {
             if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
                 $('.homecopybtn' + number).popover('hide');
             }
         });

    }

    var onload = jQuery('#customSwitch1').is(":checked");
    jQuery('#customSwitch1').click(function(){

    if(jQuery('#customSwitch1').is(":checked") == onload) jQuery('.alert-warning').hide('fast');
    else jQuery('.alert-warning').show('fast');

    });

   
    $('#copybtn').tooltip({title: 'Copied', placement: "top",trigger: 'click'});

    jQuery('#copybtn').click(function(){

    jQuery('#reservlink').select();
    document.execCommand("copy");

    });
    jQuery('#copybtn').mouseout(function(){

        $('#copybtn').tooltip('hide');

    });
for(var j = 0;j < 6;j++){
    for(var i = 1;i <= $('#countVIP' + j).text();i++){
        VIPtooltip(i);
    }
}
});
