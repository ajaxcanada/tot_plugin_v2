<?php

function send_sms ($number, $name, $message){ 
	//echo $number . $name . $message;
	// this is my trilio information	
    $AccountSid = "AC1ea860e82fab93007d30d6cf9f1bd1ef";
    $AuthToken = "1348cfe3601652ba30d797a10f538e95";
 
    // setup for trilio
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    // array for the message or messages (can be expanded) 
    $people = array( $number => $name );
	
    // loop to send the text messages
    foreach ($people as $number => $name) {
        $sms = $client->account->messages->sendMessage(
            "6137777087", 
            $number,
            $message
        );
        
		return "Sent message to $name";
    } 
}
?>