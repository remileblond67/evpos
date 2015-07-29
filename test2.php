<?php
	$opt = array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);
	$get = file_get_contents('https://srvpnextp.cus.fr:1671/1/investigations/0388309704/UserUO?GIMP2', false, stream_context_create($opt));
	$xml = simplexml_load_string($get, 'SimpleXMLElement', LIBXML_NOWARNING);
	if(empty($xml)) echo 'empty!';
	else print_r($xml);
?>
