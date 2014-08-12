<?php

function Create_main_styles() {

/*  hide the title off the main page */
$css_out .= "<style>";
$css_out .= ".post-1282 .entry-title {display: none;}";
$css_out .= ".post-1293 .entry-title {display: none;}";

$css_out .= "</style>";

return $css_out;
}


//
//function Create_javascript(){
//echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js">';//</script>';
//   // echo '<script type="text/javascript">';    
////echo '<script>';    
//echo   '$(".wrapper1").scroll(function(){';
//echo     '$(".wrapper2")';
//echo            ' .scrollLeft($(".wrapper1").scrollLeft());';
//echo     '});';
//echo     '$(".wrapper2").scroll(function(){';
//echo         '$(".wrapper1")';
//echo             '.scrollLeft($(".wrapper2").scrollLeft());';
//echo     '});';
//echo '});';
//
//echo "</script>";
//}

?>