jQuery(document).ready(function($) {
	var data = {
		'action': 'my_action',
		'whatever': ajax_object.we_value      // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		alert('Got this from the server: ' + response);
	});
});

//jQuery(document).ready(function() {
//    if (jQuery) {
//        //alert("jQuery library is loaded!");
//        //hide the php server side stuff to speed user interface
//        $("#js_enabled_hide_buttons").hide();
//    } else {
//        //alert("jQuery library is not found!");
//        $("#js_enabled_hide_buttons").show();
//    }
//    $("#group_selected").click(function() {
//    var testVal = $("#group_selected").val();
//    });
//});

$(function(){
            $('#inp').keyup(function(){

            var inpval=$('#inp').val();

            $.ajax({
                type: 'POST',
                data: ({p : inpval}),
                url: 'ajax_listener.php',
                success: function(data) {
                     $('.result').html(data);
          }
        });
    });
});
 
function showUser(str) {
    if (str === "") {
        document.getElementById("group_selected").innerHTML = "";
        return;
    }
    //talk to server
    if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
//    update the field
//            xmlhttp.onreadystatechange = function() {
//                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
//                    document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
//                }
//            }
//    ;
    //data to server
    xmlhttp.open("GET", "plugin_main_form.php?q=" + str, true);
    xmlhttp.send();
}

//function showUser(str) {
//  if (str==="") {
//    document.getElementById("txtHint").innerHTML="";
//    return;
//  } 
//  if (window.XMLHttpRequest) {
//    // code for IE7+, Firefox, Chrome, Opera, Safari
//    xmlhttp=new XMLHttpRequest();
//  } else { // code for IE6, IE5
//    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//  }
//  xmlhttp.onreadystatechange=function() {
//    if (xmlhttp.readyState===4 && xmlhttp.status===200) {
//      document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
//    }
//  };
//  xmlhttp.open("GET","getuser.php?q="+str,true);
//  xmlhttp.send();
//}


//jQuery(document).ready(function() {
//    $("#group_selected").click(function() {
//        if (jQuery) {
//            //alert("jQuery library is loaded!");
//            $("#js_enabled_hide_buttons").hide();
//            var testVal = $("#group_selected").val();
//
//        } else {
//            //alert("jQuery library is not found!");
//        }
//    });
//});

//
//jQuery(function(){
//        $("#submit").click(function(){
//        //$(".error").hide();
//        //var hasError = false;
//        var passwordVal = $("#password").val();
//        var checkVal = $("#password-check").val();
//        if (passwordVal == '') {
//            $("#password").after('<span class="error">Please enter a password.</span>');
//            hasError = true;
//        } else if (checkVal == '') {
//            $("#password-check").after('<span class="error">Please re-enter your password.</span>');
//            hasError = true;
//        } else if (passwordVal != checkVal ) {
//            $("#password-check").after('<span class="error">Passwords do not match.</span>');
//            hasError = true;
//        }
//        if(hasError == true) {return false;}
//    });
//});
//
//
////jQuery(document).ready(function (){
//jQuery(function(){
//        $("#").click(function(){
//            alert("jQuery is loaded and ready to be used");
//        $(".error").hide();
//        var hasError = false;
//        var passwordVal = $("#password").val();
//        var checkVal = $("#password-check").val();
//        if (passwordVal == '') {
//            $("#password").after('<span class="error">Please enter a password.</span>');
//            hasError = true;
//        } else if (checkVal == '') {
//            $("#password-check").after('<span class="error">Please re-enter your password.</span>');
//            hasError = true;
//        } else if (passwordVal != checkVal ) {
//            $("#password-check").after('<span class="error">Passwords do not match.</span>');
//            hasError = true;
//        }
//        if(hasError == true) {return false;}
//                });
//});