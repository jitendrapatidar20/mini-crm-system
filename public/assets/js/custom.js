

$("#login").validate({
        ignore: [],
        rules: {
            email: {required: true,email: true},
            password: {required: true},
        },
        messages: {
                email: "Please enter a valid email address",
                password: {
                        required: "Please provide a password"
                       // minlength: "Your password must be at least 8 characters long",
                       // maxlength: "Password Cannot be longer than 15 characters"
                }
        },
        errorPlacement: function (error, element) {
            if(element.attr("name")=="password"){
                error.insertAfter($(element).parents('.input-field').after());
            }else{
                 element.after(error);
            }
        },
        submitHandler: function(form) {
            //loader show
            $('.loading').show();
             $("#login-btn").attr("disabled");
            // login ajax run
             getCurrentLocation()
             .then(function(coordinates) {
             	   var formData = new FormData($('#login')[0]);
             	   formData.append("latitude", coordinates.latitude);
                    formData.append("longitude", coordinates.longitude);
		            $.ajax({
		                type : "POST",
		                url: BASEURL + '/post-login',
		                mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'json',
		                data: formData,
		                success : function(response){
		                    console.log(response);
		                    //return false;
		                    $('.loading').hide();
		                    $('.error').remove();
		                    $("#login")[0].reset();
		                     $("#login-btn").removeAttr("disabled");
		                    showMesseage('success', response.message);
		                    setTimeout(function(){
		                       window.location.href = response.redirect.startsWith("http") 
                                ? response.redirect 
                                : BASEURL + "/" + response.redirect;  
		                    }, 2000);

		                },
		                error: function (request, status, error) {
		                    //loader hide
		                    $('.loading').hide();
		                     $("#login-btn").removeAttr("disabled");
		                    console.log(request);
		                    //$('.error').remove();
		                    if(request.status==401){
		                        var response = JSON.parse(request.responseText);
		                        showMesseage('error', response.error);
		                    }
		                    return false;
		                }
		            });
	          })
	          .catch(function(error) {
	          	    $('.loading').hide();
                    $("#login-btn").removeAttr("disabled");
                    var message = "Unable to get location!";
                    showMesseage('error', message);
               });
        }
});


function timeComvert(times){
    var res = times.split(":");
    return res[0]+res[1];
}

function encodeBase64(element) {
  //alert($(this).attr("name"));
  //console.log($(element).attr('class'));
    var fileName = $(element).parent().children('.first').children().attr("class");
    //alert(fileName);
    var extension_allow_string = $(element).parent().children('.first').children().attr("data-extension");
    //alert(JSON.stringify(extension_allow_string));
    var extension_allow = extension_allow_string.split(",");
    var filesize_allow = $(element).parent().children('.first').children().attr("data-size");

    var file = element.files[0];
    var filename = file.name;
    var extension = filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
    if ($.inArray(extension, extension_allow) > -1){
        $("."+fileName+"_error").html("");
    }else{
        $("."+fileName+"_error").html("This file extension not allowed.");        
    }

    var filesize = file.size/1024;
    if(filesize_allow < filesize){
        $("."+fileName+"_error_size").html("File size only "+filesize_allow+" KB allowed.");
    }else{
        $("."+fileName+"_error_size").html("");
    }


    //$("."+fileName).val(reader.result);
    $("."+fileName).val(filename);
  var reader = new FileReader();
  reader.onloadend = function() {
    //$(".link").attr("href",reader.result);
    //$("."+fileName).val(reader.result);
    //$(".link").text(reader.result);
  }
  reader.readAsDataURL(file);
}


 const forms = document.querySelector(".forms"),
      pwShowHide = document.querySelectorAll(".eye-icon"),
      links = document.querySelectorAll(".link");

pwShowHide.forEach(eyeIcon => {
    eyeIcon.addEventListener("click", () => {
        let pwFields = eyeIcon.parentElement.parentElement.querySelectorAll(".password");
        
        pwFields.forEach(password => {
            if(password.type === "password"){
                password.type = "text";
                eyeIcon.classList.replace("bx-hide", "bx-show");
                return;
            }
            password.type = "password";
            eyeIcon.classList.replace("bx-show", "bx-hide");
        })
        
    })
}) 

  function getCurrentLocation() {

        return new Promise(function(resolve, reject) {
            // Success callback function to handle the retrieved coordinates
            function successCallback(position) {
                var obj = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };

                // Resolve the Promise with the obj
                resolve(obj);
            }
            // Error callback function
            function errorCallback(error) {
                // Reject the Promise with the error message
                reject(error);
            }
            // Get the user's current location
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        });
}     




