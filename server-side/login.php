<?php
/*
Receive user data.
- Username
- AES data
*/

$user = $_POST["user"];
$aes = $_POST["aes"];

//decrypt aes data with the user's password
//$aes contains the ciphertext and the initialization vector (iv), encoded in base64 and separated by ;

$encstr = base64_decode(explode(";",$aes)[0]);
$iv = base64_decode(explode(";",$aes)[1]);

// Get the user's password from your database.
$password = "USER PASSWORD";
$salt = $user;

$keyLength = 32;
$iterations = 5000;
$key = openssl_pbkdf2($password, $salt, $keyLength, $iterations, 'sha384');

$output = openssl_decrypt($encstr, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

if($output == true)
{
  /*
  Decryption successful.
  Authenticated user
  */
  
  // Decode json string.
  $obj = json_decode($output, false);
  
  // Get time (UTC) to calculate the elapsed time.
  $date = new DateTime();
  $date->setTimezone(new DateTimeZone('UTC'));
  $data = strtotime($date->format('Y-m-d H:i:s'));
  
  if($data-strtotime($obj->Date) < 10)
  {
    // less than 10 seconds have elapsed
    // for slow connections give more time
    
    // Optional data
    // $obj->IP, $obj->Socket
    
    // Continue with your login code
    //...
  }
}
else
{
  //Failed authentication.
}
?>
