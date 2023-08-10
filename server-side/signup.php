<?php
/*
Receive user data.
- Username
- RSA data
- AES data
*/

$user = $_POST["user"];
$rsa = $_POST["rsa"];
$aes = $_POST["aes"];


// decrypt rsa data with private key
$fp=fopen ("kwikipass/private/private_key.pem","r");
$private_key=fread ($fp,8192);
fclose($fp);

$result = openssl_private_decrypt(base64_decode($rsa), $decriptedData, $private_key, OPENSSL_PKCS1_OAEP_PADDING);
if(!$result)
{
  //Decryption failed.
  //...
}
else
{
  // $decryptedData contains the user's password
  // then decrypt aes data with the user's password
  
  // $aes contains the ciphertext and the initialization vector (iv), encoded in base64 and separated by ;
  
  $encstr = base64_decode(explode(";",$aes)[0]);
  $iv = base64_decode(explode(";",$aes)[1]);
  
  $password = $decriptedData;
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
      // $obj->IP, $obj->Socket, $obj->Max_Users
      
      // Save $password in your database as the user's password.
      // Continue with your signup code
      //...
    }
  }
  else
  {
    //Failed authentication.
  }
}
?>
