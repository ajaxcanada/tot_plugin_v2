// NOTE jQuery(document).ready... DOES NOT WORK IN CHROME, IT HAS TO BE $(document).ready...
$(document).ready(function() {
    if (jQuery) {
     console.log( 'loaded jQuery');
//        hide the php server side stuff to speed user interface
        $("#js_enabled_hide_buttons").hide();
    } else {
     console.log( 'jQuery didnt load');
        //alert("jQuery library is not found!");
        $("#js_enabled_hide_buttons").show();
    }
});

// THIS WORKS. FINALLY. ANOTHER WEEK SPENT FIGURING OUT HOW SOMETHING WORKS. 082414: WOO-HOO
$("#sub").click(function(event) {
    var vname = this.name;

    console.log( vname );
    console.log( 'ready!' );
    var data = {
        action: 'my_action',
        security: MyAjax.security,
        dbase: vname
    };
    
    console.log(data);
    $.ajax({
        url: MyAjax.ajaxurl,
        type: "post",
        data: data,
        success: function(output) {
            $("#result").html('<br>' + output);
        },
        error: function(output) {
            $("#result").html('your a failure'+output);
        }
    });
    event.preventDefault();
});



//jQuery(document).ready(function($) {
////    ("#sub").click(function(event) {
////    alert('Got this from the server: ' + response);
////    });
//    //works
//    var data = {
//        action: 'my_action',
//        security: MyAjax.security,
//        whatever: 1234
//    };
//
//    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
//    $.post(MyAjax.ajaxurl, data, function(response) {
//        $("#result").html("works " + response);
//        //alert('Got this from the server: ' + response);
//    });
//});
//
//
//$("#sub").click(function($) {
//    alert("pass");//Do stuff here
//    $.post(MyAjax.ajaxurl, data, function(response) {
//      $("#result").html(response);
//alert('Got this from the server: ' + response);
//  });
//    $.ajax({
//        url: MyAjax.ajaxurl,
//        type: "post",
//        data: ({action: 'my_action'}),
//        success: function(output) {
//            $("#result").html('Submitted successfully<br>' + output);
////            //alert("success");//Do stuff here
//        },
//        error: function() {
//            $("#result").html('your a failure');
//            //alert("failure");//Do stuff here
//        }
//    });
/* Stop form from submitting normally */
//  event.preventDefault();
//});

//$(document).click(function($) {
//        alert("pass_1");//Do stuff here
//	$('#tot_ready').click(function(event) {
//            alert('Hey! You have clicked the button!');
//	});
//});


//$("#sub").click(function(event) {
//
//    /* Stop form from submitting normally */
//    event.preventDefault();
//
//    /* Clear result div*/
//    $("#result").html('');
//
//    /* Get some values from elements on the page: */
//    var values = $("form").serializeArray();
//    alert(values);
//    /* Send the data using post and put the results in a div */
//    $.ajax({
//        url: "insert.php",
//        type: "post",
//        data: values,
//        success: function() {
//            alert("success");
//            $("#result").html('Submitted successfully');
//        },
//        error: function() {
//            alert("failure");
//            $("#result").html(values);
//        }
//    });
//});
//







































//$("#sub").click(function() {
// LOOK INTO POST FOR ACTION FROM FORM
//    var data = $("#group_selected").val;
//var data = $("#main_form_data :input").serializeArray();
//alert(data);


//  $.post($("#main_form_data").attr("name"), data, function(info) { $("#result").html(info);
//$("#result").html(data);
//        });
//clearInput();
//});

//$("#main_form_data").submit(function() {
//    return false;
//});
//function clearInput() {
//    $("#main_form_data :input").each(function() {
//        $(this).val('');
//    });
//}

//alert("post");