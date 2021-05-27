<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/forms/braintree-php-3.34.0/braintree-php-3.34.0/lib/Braintree.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/forms/braintree-php-3.34.0/braintree-php-3.34.0/lib/autoload.php');
//require_once("../includes/braintree_init.php");

// Instantiate a Braintree Gateway like this:
$gateway = new Braintree_Gateway([
    'environment' => 'sandbox',
    'merchantId' => 'g82dbc9xdvtp4yx9',
    'publicKey' => 'jgcjp29yk9vkpknx',
    'privateKey' => '76a4490891f809c5e17e0fd39b674255'
]);
// end of gateway

//declare variable we want to receive from post from client

$threeDSNonce =$_POST["3ds_nonce"];
$amount = $_POST["amount"];
$nonce = $_POST["paymentMethodNonce"]; 
$memeberId = $_POST["memberid"];
$fName =$_POST["parent0fname"];
$lName= $_POST["parent0lname"];
$email = $_POST["parent0email"];
  //end of variable declaration 

  //create a customer and a payment method token useing the above variables and a paymentmethod nonce received from client
$result = $gateway->customer()->create([
    'firstName' => $fName,
    'lastName' => $lName,
    'email'=> $email,
    'paymentMethodNonce' => $nonce
]);
if ($result->success) {

   $pmt = $result->customer->paymentMethods[0]->token;
   //echo($pmt);
  // create an if statement to ensure pmt isn't blank, also we need $pmt here so its in scope.
  // this new nonce that we create needs to be sent to the client, or retrieved by an ajax call so we can 
  // put it through 3D Secure. Once its Gone through 3D a new nonce that has been enriched will be returned to the server
  // we will use this to create the subscription >:(

   if($pmt != null){

    $result = $gateway->paymentMethodNonce()->create($pmt);

    $nonce3ds = $result->paymentMethodNonce->nonce;

    echo($nonce3ds); // need to send this back to the clients

    
}else {
  foreach($result->errors->deepAll() AS $error) {
      echo($error->code . ": " . $error->message . "\n");
  }
}

}
else {
  foreach($result->errors->deepAll() AS $error) {
      echo($error->code . ": " . $error->message . "\n");
  }
}
