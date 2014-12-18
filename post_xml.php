<?php
	// open a http channel, transmit data and return received buffer
	function xml_post($post_xml, $url, $port)
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$ch = curl_init();    // initialize curl handle
		
		$headers[] = "Accept: */*";
        $headers[] = "Cache-Control: max-age=0";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Keep-Alive: 300";
        $headers[] = "Accept-Charset: iso-8859-12;q=0.7,*;q=0.7";
        $headers[] = "Accept-Language: en-us,en;q=0.5";
        $headers[] = "Pragma: "; // browsers keep this blank.

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);              // Fail on errors
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_PORT, $port);			//Set the port number
		curl_setopt($ch, CURLOPT_TIMEOUT, 15); // times out after 15s
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_xml); // add POST fields
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

		if($port==443)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}

		$data = curl_exec($ch);

		curl_close($ch);

		return $data;
	}
?>