<?php
#
# EasycronApi 
# version 0.1
# This is class has been written to help users of easycron.com to use the API.
# The latest version can be found at http://www.easycron.com/easycron-api-kit.zip
# Improvements, bugs or modifications can be send to info@easycron.com
#
# Copyright (c) 2012, EasyCron.com
# All rights reserved.
#

class EasycronApi
{
	// The token act as a "password" to access the API
	// Your token can be found at http://www.easycron.com/user/token
	public $token;
	
	// endpoint of the API
	public $uri = 'https://www.easycron.com/rest/';

	/**
	 * Constructor, sets token
	 */
	public function __construct($token)
	{
		$this->token = $token;
	}
	
	/**
	 * Makes the actual call to the easycron.
     *
	 * @param    $method : string the name of the API method (ex: 'add' or 'edit')
	 * @param    $data	 : array array(name => value) pairs to send to API endpoint
	 * @return           : array / false - will return any array returned by easycron or false if the connection to easycron fails.
	 */
	public function call($method, $data = array())
	{
	    $data['token'] = $this->token;
        $arguments = array();
        foreach ($data as $name => $value) {
            $arguments[] = $name . '=' . urlencode($value);
        }
        $temp = implode('&', $arguments);

        $url = $this->uri . $method . '?' . $temp;
        $result = file_get_contents($url);

        if ($result) {
            return json_decode($result, true);   
        } else {
            return $result;
        }
	}
}