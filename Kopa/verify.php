<?php
$secretKey = '6LezhiUpAAAAAG3aRhW8J31XWC7w_1HKXfb4C8PI'; // Replace with your secret key

$responseKey = $_POST['g-recaptcha-response'];

// Make sure the form response exists
if (isset($responseKey) && !empty($responseKey)) {
    // Build the POST request to Google's reCAPTCHA verification endpoint
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secretKey,
        'response' => $responseKey
    );

    $options = array(
        'http' => array(
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify);

    if ($captchaSuccess->success) {
        // reCAPTCHA verification successful, proceed with your logic
        echo "reCAPTCHA verification successful!";
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $body = $_POST['message'];
        $email = $_POST['email'];
        
        header("location: mailto:media@kopamax.com?subject=".$subject."&body=".$body."  | From: ".$name." | Email: ".$email);

    } else {
        // reCAPTCHA verification failed
        echo "reCAPTCHA verification failed!";
        header("location: recaptchaFailed.html");
    }
} else {
    // Error if no response received
    echo "No reCAPTCHA response received!";
    header("location: noRecaptcha.html");
}
?>
