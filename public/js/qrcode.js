$(document).ready(function(){

    function openQRCamera(node) {
        var reader = new FileReader(); // izveidojam jaunu faila lasīšanas klasi kura var lasīt binārus datus
        reader.onload = function() { // kad viss ielādēsies funkcija
          node.value = "";
          qrcode.callback = function(res) {
            if(res instanceof Error) {  // ja neizdevās atrast QRkodu

                if($('.qr-success').length > 0)  $(".qr-success").remove();
                if($(".js-qr-warning").length == 0){

                $(".alert-danger").remove();

                $("<div class='alert alert-dismissible alert-danger qr-warning js-qr-warning' style='padding-top: 20px;'>" +
                "<button type='button' class='close qr-close' data-dismiss='alert'>&times;</button>" + 
                "<p class='mb-0' id='qrcode-error'></p><br></div>").insertBefore(".qrcode-text").slideDown();

                $('#qrcode-error').text('QR kods netika atrasts! Pameiģiniet vēl reiz.');
                
                }

            } else {

              $('.qrcoderesult').val(res);
              $('#scanqrcode').submit();

            }
          };
          qrcode.decode(reader.result);
        };
        reader.readAsDataURL(node.files[0]);
      }
      $('#qrcode').change(function(){

        openQRCamera(this);
        
      });
      
});