$(document).ready(function(){
    $('#editAlert').hide();
    
});
$(window).load(function(){
    $('#gallery').val(null);

	$('#gallery').change(function(){

        if($(this)[0].files.length > 0) $('#addphotosgallery').submit();

        console.log($(this)[0].files[0]); // piekļuve pie visiem failiem: $(this)[0].files. Pie konkrētā: $(this)[0].files[0]. Pie faila skaita: $(this)[0].files.length
        // Pie konkrētā faila vārda: $(this)[0].files[0].name
        

    });
    $(window).resize(function(){

        $('.gallery-photo').css({'height' : $('.gallery-photo').width()/1.7777777});
        $('.add-gallery').css({'height' : $('.add-gallery').width()/1.7777777});

});

    $('.gallery-photo').css({'height' : $('.gallery-photo').width()/1.7777777});
    $('.add-gallery').css({'height' : $('.add-gallery').width()/1.7777777});

        
        var galleryPhoto = $('.gallery-photo');
        var addGallery = $('.add-gallery');

        galleryPhoto.eq(0).css({"margin-top" : "0"}); // lai saņemtu vērtības
        galleryPhoto.eq(0).css({"margin-left" : "0"}); // pirmais elements ja viņš ir vienmēr būs 0
        
        

        for(var i = 0;i < galleryPhoto.length;i++){ // cikls,kas noņem margin left ja div attēls ir jaunajā rindā
    // lai ja elements ir jaunajā rindā un viņs ir pirmais rindas elements,ne bīdītos no malas
    // lai pārbaudīt arī elementus kuri jau saņēmas margin bet nobīdījas uz jaunu

            if(galleryPhoto[i].offsetLeft >= left){ 
                
                galleryPhoto.eq(i).css({"margin-left" : "10.56%"}); 

            }
            else {

                galleryPhoto.eq(i).css({"margin-left" : "0"}); 

            }

            galleryPhoto.eq(i).css({"margin-top" : "0"});

            if(galleryPhoto[i].offsetTop != top){
    
                galleryPhoto.eq(i).css({"margin-top" : "30px"});

            } 
    
        }console.log(galleryPhoto.length)
        if(galleryPhoto.length > 1){

            var left = galleryPhoto[1].offsetLeft; // saņem otrā elementa vērtību
            var top = galleryPhoto[0].offsetTop;

            if(addGallery[0].offsetLeft  >= left){ // ja viņš ir vienāds jeb lielāks par otro elementu viņš nav pirmais rindā
    
                addGallery.eq(0).css({"margin-left" : "10.56%"});

            }
            else {

                addGallery.eq(0).css({"margin-left" : "0"}); 

            }

            addGallery.eq(0).css({"margin-top" : "0"});// ja viņs nav vienāds ar pirmo elementu tad,viņs nav pirmajā rindā

            if(addGallery[0].offsetTop != top){
    
                addGallery.eq(0).css({"margin-top" : "30px"});

            }
            
        }
        else if(galleryPhoto.length == 0){

            addGallery.eq(0).css({"margin-left" : "0"}); 
            addGallery.eq(0).css({"margin-top" : "0"});
        }
    
    
    $('#editGallery').click(function(){

        $('.content-page').addClass('overlay-back');
        $('.over-content').addClass('overlay');
        $('.con-ov').addClass('content-overlay');
        $('#editAlert').slideDown(500);
        $('.add-gallery').fadeOut();
        $('.page-header').css({'box-shadow' : 'none'});
        $('.innerImage').show();
        $('.outerImage').hide();
        $('.submitGallery').prop('disabled', true);
        $('.text').hide();

    });
    $('.closeEdit').click(function(){

        $('.content-page').removeClass('overlay-back');
        $('.over-content').removeClass('overlay');
        $('.con-ov').removeClass('content-overlay');
        $('#editAlert').slideUp(500);
        $('.add-gallery').fadeIn();
        $('.page-header').css({'box-shadow' : '0px 5px 20px black'});
        $('.innerImage').hide();
        $('.outerImage').show();
        $('.text').show();


    });
    $('.imgcb').click(function(){

        if($(".imgcb").is(':checked')) $('.submitGallery').prop('disabled', false);
        else $('.submitGallery').prop('disabled', true); 

    });
    $('.innerImage').hide();
    $('.outerImage').show();

    // Saņemam modalu
    var modal = $('.modal');

    // Saņemam attēlu un ievietojam to modalā un izmantojam alt attēlam lai izvadīt tekstu par viņu
    var eye = $('.text');
    var modalImg = $('.modal-content');
    var captionText = $('#caption');

    eye.click(function(){

        modal.css({'display' : 'block'});
        modalImg.attr('src',$(this).closest('.imageContainer').find('img.outerImage').attr('src'));
        captionText.html($(this).closest('.imageContainer').find('img.outerImage').attr('alt'));

    });

    // Saņem span elemetnu,kas aizver attēļu
    var span = $('.closeimg');

    // Kad nospiež uz <span> (x), aizvert modalu
    span.click(function(){

        modal.css({'display' : 'none'});

    });

    });