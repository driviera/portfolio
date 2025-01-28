<?php
define('reCAPTCHAsecret',getenv('reCAPTCHAsecret'));
function checkToken() {
    if( empty($_POST['token']) ) {
        return false;
    } elseif ( !empty($_POST['token']) ) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        if(isset($_POST['token']) && $_POST['token']<>'') $token = $_POST['token'];
        else $token = '';

        $params=['secret'=>reCAPTCHAsecret, 'response'=>$token];
        $defaults = array(
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify', 
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
        );
        $curl = curl_init();
        curl_setopt_array($curl,$defaults);
        $result = curl_exec($curl);
        $result = json_decode($result);
        return(true === $result->success);
    }
}