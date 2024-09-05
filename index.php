<?php
const TARGET_HOST = 'https://your-new-domain-here.com'; // Write your target host URL here

// Get the path and query string from the original request
$requestUri = $_SERVER['REQUEST_URI'];

// Construct the target URL with the new domain
$targetUrl = TARGET_HOST . $requestUri;

// Initialize a cURL session
$ch = curl_init($targetUrl);

// Set the cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true); // This includes the headers in the output
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if necessary
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']); // Set the request method (GET, POST, etc.)
curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // Limit the number of redirects to prevent infinite loops

// Forward all incoming request headers to the target URL
$headers = [];

foreach (getallheaders() as $name => $value) {
    $headers[] = "$name: $value";
}

$headers[] = 'X-Forwarded-For: ' . $_SERVER['REMOTE_ADDR'];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// If the request has a body (e.g., POST), forward it as well
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH') {
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
}

// Execute the request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    http_response_code(500);
    echo "An error occurred while fetching the content.";
    curl_close($ch);
    exit;
}

// Separate the headers and the body
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headerString = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);

// Get the HTTP response code
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Forward the response headers to the client
$headerLines = explode("\r\n", $headerString);
foreach ($headerLines as $headerLine) {
    if (!empty($headerLine) && !preg_match('/^Transfer-Encoding:/i', $headerLine) && !preg_match('/^Content-Encoding:/i', $headerLine)) {
        header($headerLine);
    }
}

// Set the HTTP response code
http_response_code($httpCode);

// Output the body content
echo $body;
