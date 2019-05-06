$(document).ready(function(){

    $("input[name='manualreserv']").click(function(){
        
        if($("input[name='manualreserv']").is(':checked')){

            $('.manualreservdata').show('fast');

        }
        else $('.manualreservdata').hide('fast');

    });
    if($("input[name='manualreserv']").is(':checked')){

        $('.manualreservdata').show();

    }
    else $('.manualreservdata').hide();
    
});