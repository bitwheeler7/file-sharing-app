<?php
header('Content-Type: application/json');

$serverAddr = $_SERVER['SERVER_ADDR'] ?? null;
$host = $_SERVER['HTTP_HOST'] ?? null;
$hostname = gethostname();
$hostnameIp = $hostname ? gethostbyname($hostname) : null;

$result = [
    'success' => true,
    'host' => $host,
    'server_addr' => $serverAddr,
    'hostname' => $hostname,
    'hostname_ip' => $hostnameIp,
];

// If the server address is missing or localhost, add some fallback guesses.
if (empty($serverAddr) || in_array($serverAddr, ['127.0.0.1', '::1'])) {
    if (!empty($hostnameIp) && !in_array($hostnameIp, ['127.0.0.1', $hostname])) {
        $result['server_addr'] = $hostnameIp;
    }
}

echo json_encode($result);
