<?php
require_once 'vendor/autoload.php';
$header = array(
    "X-Mashape-Key" => "PUT YOUR MASHAPE API KEY HERE",
    "Accept" => "application/json"
  )
if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$url = $_GET['url'];
	if ($url == 'change') {
		echo "Change Email Address";
	}
	elseif ($url == 'randomMail') {
		$response = Unirest\Request::get("https://privatix-temp-mail-v1.p.mashape.com/request/domains/",$header
);
		$domains = $response->body;
		$rd = $domains[array_rand($domains)];
		echo rand_str().''.$rd;
	}
	elseif ($url == 'domains') {
		$response = Unirest\Request::get("https://privatix-temp-mail-v1.p.mashape.com/request/domains/",$header
);
		echo json_encode($response->body);
	}
	elseif ($url == 'getMails') {
		$mail = explode('getMails',$_SERVER['REQUEST_URI']);
		$rMail = substr($mail[1],1,strlen($mail[1]) - 1);
		$response = Unirest\Request::get('https://privatix-temp-mail-v1.p.mashape.com/request/mail/id/'.md5($rMail).'/',$header);
		$out = array();
		$code = $response->code;
		if($code == '200')
		{
		$body = $response->body;
		for($i = 0; $i<=count($body)-1; $i++) {
			
			$from = $body[$i]->mail_from;
			$subject = $body[$i]->mail_subject;
			$text = $body[$i]->mail_text;
			$mail_data = array('id' => $i, 'from' => $from, 'subject' => $subject, 'text' => htmlspecialchars($text));
			array_push($out,$mail_data);
		}
		echo json_encode($out);
	}
		http_response_code($code);
	}
	else{
		echo "Invaild Request";
	}

}
else{
	echo "Invaild Method";
}
function rand_str(){
	// Character List to Pick from
$chrList = 'abcdefghijklmnopqrstuvwxyz';

// Minimum/Maximum times to repeat character List to seed from
$chrRepeatMin = 1; // Minimum times to repeat the seed string
$chrRepeatMax = 5; // Maximum times to repeat the seed string

// Length of Random String returned
$chrRandomLength = 8;

// The ONE LINE random command with the above variables.
return substr(str_shuffle(str_repeat($chrList, mt_rand($chrRepeatMin,$chrRepeatMax))),1,$chrRandomLength);
}

?>
