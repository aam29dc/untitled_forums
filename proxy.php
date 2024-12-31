<?php
// Simple PHP Proxy Script

// The URL to proxy (can be passed via query string)
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Check if URL is provided
if (!$url) {
    die('No URL provided!');
}

// Sanitize the URL (optional)
$url = filter_var($url, FILTER_SANITIZE_URL);

// Initialize cURL
$ch = curl_init();

// Set the cURL options
// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects (-L)
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:133.0) Gecko/20100101 Firefox/133.0"); // Set User-Agent
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
    "Accept-Language: en-US,en;q=0.5",
    "Connection: keep-alive",
    "Upgrade-Insecure-Requests: 1",
    "TE: Trailers",
    "Referer: https://www.google.com/"
]);

// Allow cURL to handle the content encoding (gzip, deflate, br, etc.)
curl_setopt($ch, CURLOPT_ENCODING, ''); // This tells cURL to handle decompression automatically.

curl_setopt($ch, CURLOPT_HEADER, false); // Exclude headers from output
curl_setopt($ch, CURLOPT_VERBOSE, true); // Show verbose output (for debugging)
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2TLS); // Force HTTP/1.1 instead of HTTP/2
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout after 30 seconds
// Execute the cURL request and store the response
$response = curl_exec($ch);

// Check for errors
if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Output the content
    echo $response;
}

// Close the cURL session
curl_close($ch);
?>
