<?php

// Define your secret (if you set one in GitHub & this is optional)
$secret = 'your_secret_key';

// Get the payload from GitHub
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];

// Verify the payload (if you set a secret in GitHub)
if ($secret && $signature) {
    list($algo, $hash) = explode('=', $signature, 2);
    $payloadHash = hash_hmac($algo, $payload, $secret);

    if ($hash !== $payloadHash) {
        // Signature doesn't match
        header('HTTP/1.1 403 Forbidden');
        die('Forbidden');
    }
}

// Decode the JSON payload
$data = json_decode($payload, true);

// Check if the push is to the master branch
if ($data['ref'] === 'refs/heads/master') {
    // Execute the deployment script and log output
    $output = shell_exec('/home/outduprw/elogistics.com.bd/script.sh > /home/outduprw/elogistics.com.bd/deploy.log 2>&1');
    // Optionally, log the output to verify execution
    file_put_contents('/home/outduprw/elogistics.com.bd/deploy_output.log', $output);
}

echo 'Deployment script executed';
