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
    function imagecss(){

        var galleryPhoto = $('.gallery-photo');
        var addGallery = $('.add-gallery');

        if(galleryPhoto.length > 0){

            var left = galleryPhoto[0].offsetLeft;
            var top = galleryPhoto[0].offsetTop;

            addGallery.eq(0).css({"margin-left" : "0"});
            if(addGallery[0].offsetLeft != left){
    
                addGallery.eq(0).css({"margin-left" : "4.697%"});

            }

            addGallery.eq(0).css({"margin-top" : "0"});

            if(addGallery[0].offsetTop != top){
    
                addGallery.eq(0).css({"margin-top" : "30px"});

            }
            
        }
        for(var i = 0;i < galleryPhoto.length;i++){ // cikls,kas noņem margin left ja div attēls ir jaunajā rindā
    // lai ja elements ir jaunajā rindā un viņs ir pirmais rindas elements,ne bīdītos no malas
    // lai pārbaudīt arī elementus kuri jau saņēmas margin bet nobīdījas uz jaunu
// un tad vajag atņemt margin mēs sākumā to uzstādam uz 0 lai pārbaudīt vai tas ir pirmais elemetns un vai tas ir tajā pašā rindā

        galleryPhoto.eq(i).css({"margin-left" : "0"});

            if(galleryPhoto[i].offsetLeft != left){ 

                galleryPhoto.eq(i).css({"margin-left" : "4.697%"}); 

            }

            galleryPhoto.eq(i).css({"margin-top" : "0"});

            if(galleryPhoto[i].offsetTop != top){
    
                galleryPhoto.eq(i).css({"margin-top" : "30px"});

            } 
    
        }

    }
    imagecss();
    $(window).resize(function(){ 

        imagecss();

    });
    
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


    });
    $('.imgcb').click(function(){

        if($(".imgcb").is(':checked')) $('.submitGallery').prop('disabled', false);
        else $('.submitGallery').prop('disabled', true); 

    });
    $('.innerImage').hide();
    $('.outerImage').show();

});