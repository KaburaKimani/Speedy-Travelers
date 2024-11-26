<?php
// Include the required PHP files
include 'accesstoken.php'; // To get the access token
include 'RegisterIPN.php'; // To register the IPN callback

// Get form data
$amount = $_POST['amount'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Generate a unique merchant reference
$merchantreference = rand(1, 1000000000000000000);
$callbackurl = "https://12eb-41-81-142-80.ngrok-free.app/pesapal/response-page.php";

// Submit order to Pesapal
if (APP_ENVIROMENT == 'sandbox') {
    $submitOrderUrl = "https://cybqa.pesapal.com/pesapalv3/api/Transactions/SubmitOrderRequest";
} elseif (APP_ENVIROMENT == 'live') {
    $submitOrderUrl = "https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest";
} else {
    die("Invalid APP_ENVIROMENT");
}

$headers = array(
    "Accept: application/json",
    "Content-Type: application/json",
    "Authorization: Bearer $token"
);

$data = array(
    "id" => "$merchantreference",
    "currency" => "KES",
    "amount" => $amount,
    "description" => "Payment for goods or services",
    "callback_url" => $callbackurl,
    "notification_id" => "$ipn_id",
    "branch" => "UMESKIA SOFTWARES",
    "billing_address" => array(
        "email_address" => $email,
        "phone_number" => $phone,
        "country_code" => "KE",
        "first_name" => $first_name,
        "middle_name" => "",
        "last_name" => $last_name,
        "line_1" => "Pesapal Limited",
        "line_2" => "",
        "city" => "",
        "state" => "",
        "postal_code" => "",
        "zip_code" => ""
    )
);

$ch = curl_init($submitOrderUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$responseData = json_decode($response);

if ($responseCode == 200 && isset($responseData->redirect_url)) {
    // Redirect user to Pesapal payment page
    header("Location: " . $responseData->redirect_url);
    exit;
} else {
    echo "Failed to initiate payment. Please try again.";
    print_r($responseData);
}
?>
