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
$threeDSNonce =$_POST["nonce"];
$memeberId = $_POST["memberid"];

//Create Subscription
$result = $gateway->subscription()->create([
    'paymentMethodNonce' => $threeDSNonce,
    'planId' => 'hen',
    'id' => $memeberId
  ]);
  
  //Result Handling
  if ($result->success) {
      header("Location: " . $baseUrl . "transaction.php?id=" . $memeberId);
  } else {
      foreach($result->errors->deepAll() AS $error) {
          echo($error->code . ": " . $error->message . "\n");
          
      }
  }