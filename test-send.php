<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

Mail::to('eric@westteck.com')->send(new TestEmail());
echo "Test email sent!\n";
