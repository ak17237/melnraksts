$(document).ready(function(){

    $('.reciever-js-search').select2({ // select2 plugina izveidošana klasei un parametru uzstādīšana
        placeholder: 'Izvēlieties saņēmēju',
        allowClear: true,
        closeOnSelect: false,
        tags: true
      });
      $('.transport-js-search').select2({
        placeholder: 'Izvēlieties pasākumu',
        allowClear: true,
        tags: true
      });

      $('.reciever').next().addClass('reciever-select'); // Pievienot klasi select2 pluginam lai varētu tos paslēpt uzspiežot uz checkbox
      $('.transport').next().addClass('transport-select');

    $('#avatar').change(function(){

        $('#addavatar').submit();
        
    });
    function showname(){

        $('.changename').hide();
        $('.cancelname').show();
        $('.savename').show();
        $('.fname').show();
        $('.fnametext').hide();

    }
    function hidename(){
        $('.cancelname').hide();
        $('.savename').hide();
        $('.changename').show();
        $('.fname').hide();
        $('.fname').removeClass('is-invalid');
        $('.fnametext').show();

    }
    if($('div').find('span#fname').length > 0) showname();
    else hidename();

    $('.changename').click(function(){

        showname();

    });
    $('.cancelname').click(function(){

        hidename();
    });

    function showsurname(){

        $('.changesurname').hide();
        $('.cancelsurname').show();
        $('.savesurname').show();
        $('.lname').show();
        $('.lnametext').hide();

    }
    function hidesurname(){

        $('.changesurname').show();
        $('.cancelsurname').hide();
        $('.savesurname').hide();
        $('.lname').hide();
        $('.lname').removeClass('is-invalid');
        $('.lnametext').show();

    }

    if($('div').find('span#lname').length > 0) showsurname();
    else hidesurname();

    $('.changesurname').click(function(){

        showsurname();

    });
    $('.cancelsurname').click(function(){

        hidesurname();

    });

    function showemail(){

        $('.changeemail').hide();
        $('.cancelemail').show();
        $('.saveemail').show();
        $('.email').show();
        $('.emailtext').hide();

    }
    function hideemail(){

        $('.changeemail').show();
        $('.cancelemail').hide();
        $('.saveemail').hide();
        $('.email').hide();
        $('.email').removeClass('is-invalid');
        $('#email').removeClass('display');
        $('.emailtext').show();

    }
    
    if($('div').find('span#email').length > 0) showemail();
    else hideemail();

    $('.changeemail').click(function(){

        showemail();

    });
    $('.cancelemail').click(function(){

        hideemail();

    });

    function showpass(){

        $('.passdiv').css({'text-align' : '-webkit-center'});
        $('.passtitle').css({'font-size' : '20px'});
        $('.passinputdiv').css({'text-align' : '-webkit-center'});
        $('.changepass').hide();
        $('.cancelpass').show();
        $('.savepass').show();
        $('.resetpass').show();
        $('#resetpasstooltip').show();
        $('.pass').show();
        $('.passtext').hide();

    }
    function hidepass(){

        $('.passdiv').css({'text-align' : '-webkit-auto'});
        $('.passtitle').css({'font-size' : '14px'});
        $('.passinputdiv').css({'text-align' : '-webkit-auto'});
        $('.changepass').show();
        $('.cancelpass').hide();
        $('.savepass').hide();
        $('.resetpass').hide();
        $('#resetpasstooltip').hide();
        $('.pass').hide();
        $('.pass').removeClass('is-invalid');
        $('.passtext').show();

    }
    if($('div').find('span#oldpass').length > 0 || $('div').find('span#pass').length > 0) showpass();
    else hidepass();

    $('.changepass').click(function(){

        showpass();

    });
    $('.cancelpass').click(function(){

        hidepass();

    });

    $('#resetpasstooltip').tooltip({title: 'Uzstādīt tagadējo paroli tādu pašu kā pirmā logošanas reizē(Latvenergo parole)', placement: "top",trigger: 'hover',container: '.questiontooltip'});

    if(localStorage.getItem('profiletab') == null || localStorage.getItem('profiletab') == 'profile'){

        $('button#profilename').addClass('active');
        $('.emailinfo').hide();

    }
    else{

        $('button#emailsend').addClass('active');
        $('.profileinfo').hide();

    }
    $('button#profilename').click(function(){

        $('button#profilename').addClass('active');
        $('button#emailsend').removeClass('active');

        $('.emailinfo').fadeOut(200,function(){
            $('.profileinfo').fadeIn(200);
            });

        localStorage.setItem("profiletab","profile");

    });
    $('button#emailsend').click(function(){

        $('button#profilename').removeClass('active');
        $('button#emailsend').addClass('active');

        $('.profileinfo').fadeOut(200,function(){
        $('.emailinfo').fadeIn(200);
        });

        localStorage.setItem("profiletab","email");

    });

    if($("input[name='transportcb']").is(':checked')){

        $('.reciever-select').css({'display' : 'none'});
        $('.transport-select').css({'display' : 'block'});

    }
    else{

        $('.reciever-select').css({'display' : 'block'});
        $('.transport-select').css({'display' : 'none'});

    }
    $('input[name="transportcb"]').click(function(){

        if($("input[name='transportcb']").is(':checked')){

            $('.reciever-select').css({'display' : 'none'});
            $('.transport-select').css({'display' : 'block'});
    
        }
        else{

            $('.reciever-select').css({'display' : 'block'});
            $('.transport-select').css({'display' : 'none'});

        }

    }); 

    $('#transportemail').tooltip({title: 'Sūtīt ziņu lietotājiem kuri ir rezervēti noteiktam pasākumam un izvēlējās tranportu. Iekavās ir cilvēku skaits. Ja saraksts tukšs,tad pasākumu kuri vēl nav pagājuši kuros ir cilvēki kuri negrib braukt patstāvīgi nav.',
     placement: "top",trigger: 'hover',container: '.questiontooltip'});

});