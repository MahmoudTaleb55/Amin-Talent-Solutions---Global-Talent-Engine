<?php
// Simple E2E test script using curl to hit local API endpoints.
// Run after `php artisan serve` is started. This script will:
// 1. Log in as admin and freelancer (seeded users)
// 2. Create an invoice as freelancer
// 3. Simulate payment (admin-only endpoint)
// 4. Release funds as admin

function http_request($method, $url, $data = null, $token = null) {
    $ch = curl_init();
    $headers = ['Accept: application/json'];
    if ($token) $headers[] = "Authorization: Bearer $token";
    if ($data && !is_string($data)) {
        if (is_array($data)) $body = json_encode($data);
        else $body = $data;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    } elseif ($data && is_string($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code'=>$code, 'body'=>$res, 'err'=>$err];
}

$base = 'http://127.0.0.1:8000/api';

echo "Logging in as admin...\n";
$login = http_request('POST', $base . '/login', json_encode(['email'=>'admin@example.com','password'=>'password']));
if ($login['code'] != 200) { echo "Admin login failed: {$login['body']}\n"; exit(1); }
$admin = json_decode($login['body'], true);
$adminToken = $admin['access_token'] ?? null;
if (!$adminToken) { echo "No admin token returned\n"; exit(1); }

echo "Logging in as freelancer...\n";
$login = http_request('POST', $base . '/login', json_encode(['email'=>'freelancer@example.com','password'=>'password']));
if ($login['code'] != 200) { echo "Freelancer login failed: {$login['body']}\n"; exit(1); }
$freelancer = json_decode($login['body'], true);
$freelancerToken = $freelancer['access_token'] ?? null;
if (!$freelancerToken) { echo "No freelancer token returned\n"; exit(1); }

// find the company id
echo "Fetching company user id...\n";
$companyResp = http_request('GET', $base . '/users?role=company', null, $adminToken);
if ($companyResp['code'] != 200) { echo "Failed to fetch company users: {$companyResp['body']}\n"; exit(1); }
$companies = json_decode($companyResp['body'], true);
if (empty($companies)) { echo "No company users found\n"; exit(1); }
$companyId = $companies[0]['id'];

// create invoice as freelancer
echo "Creating invoice as freelancer...\n";
$invoiceResp = http_request('POST', $base . '/projects/1/invoice', json_encode([
    'amount' => 100.00,
    'currency' => 'USD',
    'description' => 'Test invoice',
    'company_id' => $companyId
]), $freelancerToken);
if ($invoiceResp['code'] != 201) { echo "Invoice creation failed: {$invoiceResp['body']}\n"; exit(1); }
$invoice = json_decode($invoiceResp['body'], true);
$invoiceId = $invoice['id'] ?? null;
if (!$invoiceId) { echo "No invoice id returned\n"; exit(1); }

echo "Invoice created with id: $invoiceId\n";

// simulate payment using admin endpoint
echo "Simulating payment (admin)...\n";
$sim = http_request('POST', $base . "/payments/test/invoice/{$invoiceId}/simulate", null, $adminToken);
if (!in_array($sim['code'], [200,201])) { echo "Simulate failed: {$sim['body']}\n"; exit(1); }

echo "Simulate response: {$sim['body']}\n";

// verify invoice is paid
echo "Verifying invoice status is 'paid'...\n";
$invoices = http_request('GET', $base . '/invoices', null, $adminToken);
$found = false;
if ($invoices['code'] == 200) {
    $list = json_decode($invoices['body'], true);
    foreach ($list as $it) {
        if ($it['id'] == $invoiceId) {
            echo "Invoice status: {$it['status']}\n";
            if ($it['status'] !== 'paid') { echo "Invoice not marked paid\n"; exit(1); }
            $found = true; break;
        }
    }
}
if (!$found) { echo "Invoice not found in list\n"; exit(1); }

// release funds as admin
echo "Releasing funds as admin...\n";
$rel = http_request('POST', $base . "/invoices/{$invoiceId}/release", null, $adminToken);
if (!in_array($rel['code'], [200,201])) { echo "Release failed: {$rel['body']}\n"; exit(1); }

echo "Release response: {$rel['body']}\n";

// final verify
$invoices = http_request('GET', $base . '/invoices', null, $adminToken);
if ($invoices['code'] == 200) {
    $list = json_decode($invoices['body'], true);
    foreach ($list as $it) {
        if ($it['id'] == $invoiceId) {
            echo "Final invoice status: {$it['status']}\n";
            if ($it['status'] !== 'released') { echo "Invoice not released\n"; exit(1); }
            echo "E2E flow succeeded\n";
            exit(0);
        }
    }
}

echo "Final invoice not found\n"; exit(1);
