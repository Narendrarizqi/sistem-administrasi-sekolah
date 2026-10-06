<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$routes = [
    '/asesmen',
    '/rekap',
    '/dashboard',
    '/laporan'
];

$adminUser = \App\Models\User::first();

foreach ($routes as $route) {
    echo "Testing GET $route ... ";
    
    // Create a request
    $request = Illuminate\Http\Request::create($route, 'GET');
    
    // Simulate login if we have a user
    if ($adminUser) {
        $app->make('auth')->login($adminUser);
    }

    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        
        if ($status >= 200 && $status < 400) {
            echo "OK ($status)\n";
        } else {
            echo "FAILED ($status)\n";
            if ($status == 500) {
                // If 500, we want to know why.
                // Exception is usually stored in the response if debug is on
                echo substr($response->getContent(), 0, 500) . "\n";
            }
        }
    } catch (\Exception $e) {
        echo "CRASH! " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}
