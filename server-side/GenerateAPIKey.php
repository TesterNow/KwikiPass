<?php
//You must generate a dynamic key to use our API
//This key must be sent along with each new connection request, that is, each time a connection is opened to log in or signup new users.

//Take the time (UTC)
//Unix timestamp (the number of seconds since January 1 1970 00:00:00 UTC)
//Create an object.Date

$date = new DateTime();
$date->setTimezone(new DateTimeZone('UTC'));
$data->Date = strtotime($date->format('Y-m-d H:i:s'));

//...Read your RSA private key.
$fp=fopen ("kwikipass/private/private_key.pem","r");
$private_key=fread ($fp,8192);
fclose($fp);

//...Encrypt it with your RSA private key.
openssl_private_encrypt(json_encode($data), $encryptedData, $private_key, OPENSSL_PKCS1_PADDING);

//...print the result in base64
echo base64_encode($encryptedData);
?>
