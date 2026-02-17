<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\User\OrganizerController;
use App\Http\Controllers\User\OrganizerEventController;
use Illuminate\Support\Str;

echo "Starting Verification...\n";

// 1. Create a Test User
$user = User::factory()->create();
Auth::login($user);
echo "Logged in as User: " . $user->name . " (ID: " . $user->id . ")\n";

// 2. Test Organizer Profile Creation
echo "Testing Organizer Creation...\n";
$organizerController = new OrganizerController();
$orgRequest = Request::create('/user/organizer', 'POST', [
    'name' => 'Test Organizer ' . uniqid(),
    'email' => 'organizer@test.com',
    'description' => 'Test Description',
]);
$orgRequest->setUserResolver(function () use ($user) {
    return $user;
});

// Simulate store
// Note: Direct controller call bypasses route model binding and some middleware, but validates logic
try {
    $organizerController->store($orgRequest);
    $organizer = $user->organizers()->first();

    if ($organizer) {
        echo "SUCCESS: Organizer Profile created. ID: " . $organizer->id . "\n";
    } else {
        echo "FAILURE: Organizer Profile not created.\n";
        exit;
    }
} catch (\Exception $e) {
    echo "ERROR during Organizer creation: " . $e->getMessage() . "\n";
    if ($e instanceof \Illuminate\Validation\ValidationException) {
        print_r($e->errors());
    }
    exit;
}

// 3. Test Event Creation (Pending Status)
echo "Testing Event Creation...\n";
$eventController = new OrganizerEventController();
$eventRequest = Request::create('/user/organizer/events', 'POST', [
    'title' => 'My Community Event ' . uniqid(),
    'start_datetime' => now()->addDays(5)->toDateTimeString(),
    'end_datetime' => now()->addDays(5)->addHours(2)->toDateTimeString(),
    'description' => 'This is a community event.',
    'location_name' => 'Virtual',
    'is_virtual' => '1',
    'categories' => [], // assuming empty allowed or handling in controller
    'tags' => [],
]);
$eventRequest->setUserResolver(function () use ($user) {
    return $user;
});

try {
    $eventController->store($eventRequest);

    // Check the event
    $event = $organizer->events()->latest()->first();

    if ($event) {
        echo "SUCCESS: Event Created. ID: " . $event->id . "\n";
        echo "Event Status: " . $event->event_status . "\n";

        if ($event->event_status === Event::STATUS_PENDING) {
            echo "SUCCESS: Event status is PENDING as expected.\n";
        } else {
            echo "FAILURE: Event status is " . $event->event_status . " (Expected: pending)\n";
        }
    } else {
        echo "FAILURE: Event not found in database.\n";
    }
} catch (\Exception $e) {
    echo "ERROR during Event creation: " . $e->getMessage() . "\n";
    if ($e instanceof \Illuminate\Validation\ValidationException) {
        print_r($e->errors());
    }
}
