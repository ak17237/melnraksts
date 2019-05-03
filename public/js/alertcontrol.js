jQuery('document').ready(function(){

    function VIPtooltip(number){
        var homecopybtn = $('.homecopybtn' + number);
        var linkcopy = jQuery('#linkcopy' + number);
        var popovers = $('#popover' + number);

        homecopybtn.tooltip({title: 'Uzspiest lai nokopēt rezervācijas linku vip pasākumam', placement: "top",trigger: 'hover'});

        homecopybtn.on('click', function(){
            homecopybtn.tooltip('dispose');
            homecopybtn.tooltip({title: 'Copied', placement: "top",trigger: 'hover',delay: {show: 500, hide: 100}});
            homecopybtn.tooltip('show');
        
            setTimeout(function(){
    
                homecopybtn.tooltip('hide');
                homecopybtn.tooltip('dispose');
                homecopybtn.tooltip({title: 'Uzspiest lai nokopēt rezervācijas linku vip pasākumam', placement: "top",trigger: 'hover'});
        
            }, 1000);
        
            homecopybtn.popover({title: "Rezervācijas links", content: linkcopy.val(),placement: 'bottom',trigger: 'manual',container: popovers}); 
            homecopybtn.popover("show");
            
            $('#popover' + number + ' .popover-header').append('<a href="#" id="popoverclose'+ number +'" class="close" data-dismiss="alert">&times;</a>');
        
            linkcopy.select();
            document.execCommand("copy");
      
         });
         
          homecopybtn.click(function (e) {
              e.stopPropagation();
          });
          
         
         $(document).click(function (e) {
             if ($(e.target).is('#popoverclose'+ number)) {
                homecopybtn.popover('hide'); 
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
    
    for(var i = 1;i <= $('#countVIP').text();i++){
        VIPtooltip(i);
    }

    $('#tabletooltip').tooltip({title: 'Lai izvēlēties galda numuru ir jāizvēlas galda numurs kuram ir brīvas vietas', placement: "bottom",trigger: 'hover',container: '.questiontooltip'});

    $('#tabletooltip').mouseover(function(){
        $('#tablenr').tooltip({title: 'Izvēleties galdiņu',placement:'right',trigger: 'hover',container: '.righttooltip'});
        $('#tablenr').tooltip('show');
    });
    $('#tabletooltip').mouseout(function(){
        $('#tablenr').tooltip('dispose');
    });


});
