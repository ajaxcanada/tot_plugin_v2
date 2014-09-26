// NOTE jQuery(document).ready... 
$(document).ready(function() {
    if (jQuery) {
        console.log('watchdog activated');
        // deal with the enter key
//        $(window).keydown(function(event) {
//            if (event.keyCode == 13) {
//                event.preventDefault();
//                return false;
//            }
//        });

    } else {
        alert("jQuery library is not found!");
    }
});
//$("input").on("click", function() {

//});

// MAIN | TOGGLE THE LINE COLOR OF THE ICON AND BORDER OF UPLOAD AREA
$('#main-icon-area-style').on('click', 'input', function(event) {
    var file_clicked = $(this).attr('name');

    $(this, '#USER_UPLOAD_AREA_DIV').toggleClass("red_INPUT_MAIN_ICON");
    $('#USER_UPLOAD_AREA_DIV').css('border', 'solid 2px ' + $(this).css("border-top-color"));
    //console.log(file_clicked + " " + vcolor);
    var vcolor = $(this).css("border-top-color");

    if (vcolor === 'rgb(255, 0, 0)') {
        //$('#USER_UPLOAD_AREA_DIV').css('border-top-color', 'red').css('borderTopColor');
        $('#result').html('READY TOT DELETE A FILE! click on the file to delete, wait for it to disapear! WARNING: File is a goner!');
    } else {
        // CLEAR THE RESULT TEXT
        $('#result').html('');
    }

});

// MAIN | CLICK ADD INSIDE RECORD
$('#main_form_input_fields').on('click', 'button', function(event) {
//$('#main_form_input_fields').find('.add_field').click(function(event) {
    event.preventDefault();
    var nav_to_table_group = $(this).attr("value");
    console.log("button: " + nav_to_table_group);
    var x = "<input type='text' id='new_field' name='new_field' placeholder='enter a new field name'>";
    $('#main-table-data-edit').html(x);
    $('#new_field').focus();

});

// MAIN | USER UPLOAD AREA | CLICK ON ICON
$('#USER_UPLOAD_AREA_DIV').on('click', 'img', function(event) {
    var file_clicked = $(this).attr('src');
    var my_border_color = $('#delete').css('border-top-color');

    console.log(file_clicked);
    console.log(my_border_color);
    if (my_border_color === 'rgb(255, 0, 0)')
    {
        var data = {
            action: 'my_action', security: load_wp_AJAX.security,
            main_command: 'delete_file', file_name: file_clicked
        };

        $.ajax({
            url: load_wp_AJAX.ajaxurl,
            type: "post",
            data: data,
            success: function(output) {
                //$("#result").html(" " + output + ". ");
                $("#USER_UPLOAD_AREA_DIV").html(output);

            },
            error: function(output) {
                $("#result").html('error 1, contact support');
            }
        });
    }
});
// ============================================
// MAIN | UPDATE MAIN DATA FROM INPUTS ON CHANGE
$('#main_form_data').on('change', 'input', function(event) {
    // STOP SERVER SIDE POST 
    event.preventDefault();
    // GET THE INPUT DATA
    console.log("fn=" + input_name + ' fv=' + input_value);

    var input_name = this.name;
    var input_value = this.value;

    if (input_value === 'allow_admin') {
        var button_state = $('input[name=edit_mode]:checked', '#main_form_data').val();
        console.log("bs=" + button_state + " fn=" + input_name + ' fv=' + input_value);

        $("#result").html($("input:checked").val() + " is checked!");

    } else {
        if (input_name === 'new_field') {
            add_field_to_table(input_value);
        } else {

            console.log("fn=" + input_name + ' fv=' + input_value);
            //LOAD A VARIABLE WITH THE DATA
            var data = {
                action: 'my_action',
                security: load_wp_AJAX.security,
                main_command: 'update_main_data',
                field_name: input_name,
                field_value: input_value
            };
            // EXECUTE THE SERVER SIDE CODE
            $.ajax({
                url: load_wp_AJAX.ajaxurl,
                type: "post",
                data: data,
                success: function(output) {
                    //$("#result").html("" + output + "");
                    //console.log('successfully got ' + output + 'from the database');
                },
                error: function(output) {
                    $("#result").html('error 2, contact support');
                }
            });
        }
    }
});
function add_field_to_table(input_value) {
    //console.log("made it" + input_value);// MAIN FORM | sub BUTTON TO ADD A GROUP
    var data = {
        action: 'my_action',
        security: load_wp_AJAX.security,
        main_command: 'add_new_field',
        field_value: input_value
    };
    // EXECUTE THE SERVER SIDE CODE
    $.ajax({
        url: load_wp_AJAX.ajaxurl,
        type: "post",
        data: data,
        success: function(output) {
            $("#main_form_input_fields").html(output);
            //console.log('successfully got ' + output + 'from the database');
        },
        error: function(output) {
            $("#result").html('error 3, contact support');
        }
    });


    //add_column_to_table
    //var x = "<input type='text' class='new_name' id='new_name' name='new_name' placeholder='enter a new name'>";
    //$('#table-title').html(x);
    //$('#new_name').focus(); 
}


// =============================================================================
// MAIN/NAVIGATION | BUTTON TO NAVIGATE TO A NEW GROUP
//                 | BUTTON TO ADD A GROUP
//                 | BUTTON TO DELETE GROUP
$('#TOT_NAV').on('click', 'button', function(event) {
    // STOP SERVER SIDE POST
    event.preventDefault();
    // GET THE NAME OF THE BUTTON TO NAVIGATE TO IT
    var nav_to_table_group = $(this).attr("value");
    console.log("nav button=" + nav_to_table_group);

    // GET ADMIN CHECKBOX. PASS THAT TO SERVER
    var checkbox_admin_state = $('input[name=edit_mode]:checked', '#main_form_data').val();
    if (checkbox_admin_state === "undefined") {
        console.log("checkbox_admin_state=" + checkbox_admin_state);
    }

    if (typeof nav_to_table_group !== "undefined") {
        if (nav_to_table_group === 'add-record') {
            jquery_add_record();
        } else if (nav_to_table_group === 'delete-record') {
            jquery_delete_record();
        } else {
            //console.log(nav_to_table_group);
            // STEP 1

            var data = {
                action: 'my_action',
                security: load_wp_AJAX.security,
                main_command: 'get_main_navigation',
                nav_button_selected: nav_to_table_group
            };
            $.ajax({
                url: load_wp_AJAX.ajaxurl,
                type: "post",
                data: data,
                dataType: 'html',
                success: function(nav_out) {
                    $("#TOT_NAV").html(nav_out);
                    //console.log("step 1-" + nav_out);
                },
                error: function(output) {
                    $("#result").html('error 4, contact support');
                }
            });

            // STEP 2
            var data1 = {
                action: 'my_action',
                security: load_wp_AJAX.security,
                main_command: 'get_record_data',
                admin: checkbox_admin_state,
                nav_button_selected: nav_to_table_group
            };
            $.ajax({
                url: load_wp_AJAX.ajaxurl,
                type: "post",
                data: data1,
                success: function(output1) {
                    $("#main_form_input_fields").html(output1);
                    var record_out = output1;
                    //console.log("step 2-" + output1);

                },
                error: function(output1) {
                    $("#result").html('error 5, contact support');
                }
            });
        }
        // return false;
    }
});
function jquery_add_record() {
// MAIN FORM | sub BUTTON TO ADD A GROUP

    var x = "<input type='text' class='new_name' id='new_name' name='new_name' placeholder='enter a new name'>";
    $('#table-title').html(x);
    $('#new_name').focus();

}
function jquery_delete_record() {
// MAIN FORM | sub BUTTON TO DELETE GROUP
    console.log(" delete group ");

}

// =============================================================================
//           | INPUT CHANGE CALL PHP SERVER ADD A NEW TABLE
$('#TOT_NAV').on('change', 'input', function(event) {
    // STOP SERVER SIDE POST 
    event.preventDefault();
    var field_name = this.name;
    var field_value = this.value;
    console.log(field_name + " " + field_value);
    var data = {
        action: 'my_action',
        security: load_wp_AJAX.security,
        main_command: 'add_new_table',
        new_name: field_value
    };
    $.ajax({
        url: load_wp_AJAX.ajaxurl,
        type: "post",
        data: data,
        success: function(output) {
            $("#TOT_NAV").html(output);
            $("#result").html(" update succesful! ");
            //console.log('successfully got ' + output + 'from the database');
        },
        error: function(output) {
            $("#result").html(" " + output + ' error 6, contact support');
        }
    });
});
// =============================================================================


// MANAGER WINDOW  
// =============================================================================
// =============================================================================
//                 | GROUP NAVIGATION
$('#field-selector').on('click', 'button', function(event) {
    event.preventDefault(); // STOP SERVER SIDE POST 
    var nav_to_table_group = $(this).attr("value");
    //console.log(event.which + " " + nav_to_table_group);
    // STEP 1 - CHANGE THE GROUP
    if (typeof nav_to_table_group !== "undefined") {
        var data = {
            action: 'my_action', security: load_wp_AJAX.security,
            main_command: 'get_field_navigation', nav_button_selected: nav_to_table_group
        };
        $.ajax({
            url: load_wp_AJAX.ajaxurl, type: "post", data: data, dataType: 'html',
            success: function(nav_out) {
                $("#field-selector").html(nav_out);
            },
            error: function(output) {
                $("#result").html('error 7, contact support');
            }
        });

        // STEP 2 - UPDATE THE RECORDS
        var data1 = {
            action: 'my_action', security: load_wp_AJAX.security,
            main_command: 'get_field_data', field_nav_button_selected: nav_to_table_group
        };
        $.ajax({
            url: load_wp_AJAX.ajaxurl, type: "post", data: data1,
            success: function(output1) {
                $("#field-table").html(output1);
            },
            error: function(output1) {
                $("#result").html('error 8, contact support');
            }
        });
    }
    return false;
});

// MANAGER WINDOW | FIELD TABLE CHANGES
$('#field-table').on('change', 'input', function(event) {
    // STOP SERVER SIDE POST 
    //event.preventDefault();
    var field_name = this.name;
    var field_id = this.id;
    var field_value = this.value;
    //event.log (field_name);
    var data = {
        action: 'my_action',
        security: load_wp_AJAX.security,
        main_command: 'update_field_data',
        field_name: field_name,
        field_value: field_value,
        field_id: field_id
    };

    console.log(field_name, field_value, field_id);
    $.ajax({
        url: load_wp_AJAX.ajaxurl,
        type: "post",
        data: data,
        success: function(output) {
            $("#result").html(" " + output + ". ");
            //console.log('successfully got ' + output + 'from the database');
        },
        error: function(output) {
            $("#result").html('error 9, contact support');
        }
    });
});

//$(window).load(function() {
//
//    var theWindow = $(window),
//            $bg = $("#TOT_MAIN_USER_FORM"),
//            aspectRatio = $bg.width() / $bg.height();
////    console.log('theWindow');
////    console.log($bg);
////    function resizeBg() {
////
////        if ((theWindow.width() / theWindow.height()) < aspectRatio) {
////            $bg
////                    .removeClass()
////                    .addClass('bgheight');
////        } else {
////            $bg
////                    .removeClass()
////                    .addClass('bgwidth');
////        }
////
////    }
//    console.log(theWindow.width() + " " + theWindow.height() + " " + aspectRatio);
//
////    theWindow.resize(resizeBg).trigger("resize");
//
//});
