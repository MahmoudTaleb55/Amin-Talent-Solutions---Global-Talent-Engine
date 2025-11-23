<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \Illuminate\Support\Facades\DB::table('users')->get(['id', 'name', 'email']);
echo "Users in database:\n";
foreach ($users as $user) {
  echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
}
