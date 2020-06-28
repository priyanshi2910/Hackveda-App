<?php
include("db.php");

$name = $_POST["name"];
$email = $_POST["email"];
$mobile = $_POST["mobile"];
$password = $_POST["password"];


$valid_name = filter_var($name, FILTER_SANITIZE_STRING);

$valod_email = filter_var($email, FILTER_SANITIZE_EMAIL);

$valid_mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

$api_key='6d7cf952a3204449b0830b8c314895dc';
$emailToValidate = $vald_email;

$url = 'https://api.zerobounce.net/v2/validate api_key='.$api_key.'&email='.urlencode($emailToValidate).'&ip_address='.$ip;

$ch = curl_int($url);

curl_setopt($ch, CURLOPT_SSLVERSION, 6);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($ch, CURLOPT_TIMEOUT, 150);
$zb_response = curl_exec($ch);
curl_close($ch);

$zb_response = jason_decode($zb_response, true);

$verified_status = $zb_response['status'];

$output["email_status" ] = $verified_status;

$otp = rand(1111, 999999);
$smsformat = $otp."is your OTP to continue registration on DoubtsApp";




$sms_text = urlencode($smsformat);
$ch = curl_init();
curl_setipt($ch,CURLOPT_URL, "http://nimbusit.co.in/api/swsendSingle.asp");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"username=priyanshi&password=40640199&sender=HAKVED&sendto=".$valid_mobile."&message=".$sms_text);
$response=curl_exec($ch);
curl_close($ch);


if($verified_status === "valid"){
    
if(!($stmt = $con->prepare("INSERT into login(Name, Email, Mobile, Password, OTP) VALUES(?, ?, ?, ?, ?, ?)"))){
    $output["result"] = false;
    $output["message"] = "Prepare failed";
}
 
if(!($stmt->bind_param("sssss", $valid_name, $valid_email,$valid_mobile, $password, $otp))){
    $output["result"] = false;
    $output["message"] = "Bind failed";
}

if(!($stmt->execute())){
    $output["result"] = false;
    $output["message"] = "Email or mobile exists. Try another email or mobile";
}else{
    $output["result"] = true;
    $output["message"] = "Account Created";
}
    $stmt->close();
}else{
    $output["result"] = false;
    $output["message"] = "Please check your email";
    
}
 
echo json_encode($output);

$con->close();

?>
