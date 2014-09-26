/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
// Function to get input value.
    $('#text_value').click(function() {
        var text_value = $("#text").val();
        if (text_value == '') {
            alert("Enter Some Text In Input Field");
        } else {
            alert(text_value);
        }
    });
    $('#text_reset').click(function() {
        $("#text").val('');
    });

    // Funtion to get checked radio's value.
    $('#radio_value').click(function() {
        $('#result').empty();
        var value = $("form input[type='radio']:checked").val();
        if ($("form input[type='radio']").is(':checked')) {
            $('#result').append("Checked Radio Button Value is :<span> " + value + " </span>");
        } else {
            alert(" Please Select any Option ");
        }
    });
    
    // Get value Onchange radio function.
    $('input:radio').change(function() {
        var value = $("form input[type='radio']:checked").val();
        alert("Value of Changed Radio is : " + value);
    });
    
    // Funtion to reset or clear selection.
    $('#radio_reset').click(function() {
        $('#result').empty();
        $("input:radio").attr("checked", false);
    });
    
    // To get value of textarea.
    $('#textarea_value').click(function() {
        var textarea_value = $("#textarea").val();
        if (textarea_value == '') {
            alert("Enter Some Text In Textarea");
        } else {
            alert(textarea_value);
        }
    });
    $('#textarea_reset').click(function() {
        $("textarea").val('');
    });
});