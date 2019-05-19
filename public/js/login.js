$(document).ready(function(){

    $("input[name='resetuser']").click(function(){

        if($("input[name='resetuser']").is(':checked')){

            $('.rememberusers').hide('fast');

        }
        else $('.rememberusers').show('fast');

    });
    if($("input[name='resetuser']").is(':checked')){

        $('.rememberusers').hide();

    }

});