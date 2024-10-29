<?php
require_once 'recaptcha-master/src/autoload.php';
$secret = '6LdeYmkqAAAAAP_IkbDlxZLaQnscyx_1-UgslXZX';
$site_key = '6LdeYmkqAAAAAPkM-9FjmwnB_3T4lDAhRp7snnSG';
if (isset($_POST['g-recaptcha-response'])) {
	$recaptcha = new \ReCaptcha\ReCaptcha($secret);
	$recaptcha_resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
															->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
}
?>