// MAIN | UPDATE MAIN DATA FROM INPUTS ON CHANGE
$('#admin_nav').on('change', 'input', function(event) {
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
                action: 'admin_action',
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
}


// =============================================================================
// MAIN/NAVIGATION | BUTTON TO NAVIGATE TO A NEW GROUP
//                 | BUTTON TO ADD A GROUP
//                 | BUTTON TO DELETE GROUP
$('#admin-nav').on('click', 'button', function(event) {
    // STOP SERVER SIDE POST
    event.preventDefault();
    // GET THE NAME OF THE BUTTON TO NAVIGATE TO IT
    var nav_to_table_group = $(this).attr("value");
    console.log("nav button=" + nav_to_table_group);

// SEND COMMAND TO SERVER
    var data1 = {
        action: 'admin_action',
        security: load_wp_AJAX.security,
        main_command: 'get_record_data',
        nav_button_selected: nav_to_table_group
    };
    $.ajax({
        url: load_wp_AJAX.ajaxurl,
        type: "post",
        data: data1,
        success: function(output1) {
            $("#admin-form-input-fields").html(output1);
            var record_out = output1;
            //console.log("step 2-" + output1);

        },
        error: function(output1) {
            $("#result").html('error 5, contact support');
        }
    });
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
$('#admin_nav').on('change', 'input', function(event) {
    // STOP SERVER SIDE POST 
    event.preventDefault();
    var field_name = this.name;
    var field_value = this.value;
    console.log(field_name + " " + field_value);
    var data = {
        action: 'admin_action',
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

