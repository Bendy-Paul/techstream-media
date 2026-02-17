<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Company;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// 1. Setup Data
$user = User::factory()->create();
$company = Company::create([
    'name' => 'Test Company ' . uniqid(),
    'slug' => 'test-company-' . uniqid(),
    'user_id' => $user->id,
    'email' => 'test@company.com',
]);

echo "Created Company: " . $company->name . " (ID: " . $company->id . ")\n";

// 2. Simulate Controller Logic (Store Event)
// Input data mock
$requestData = [
    'title' => 'Test Event ' . uniqid(),
    'organizers' => [$company->id], // Selected organizers
    // ... other fields
];

echo "Simulating Event Creation...\n";

try {
    DB::transaction(function () use ($requestData) {
        // Determine Organizer (Owner)
        $organizerId = null;
        $companyIds = $requestData['organizers'];

        if (!empty($companyIds)) {
            $ownerCompanyId = $companyIds[0];
            $company = Company::find($ownerCompanyId);

            if ($company) {
                $organizer = $company->organizers()->first();
                if (!$organizer) {
                    $organizer = $company->organizers()->create([
                        'name' => $company->name,
                        'slug' => Str::slug($company->name . '-organizer-' . uniqid()),
                        'email' => $company->email,
                    ]);
                    echo "Created New Organizer Profile: " . $organizer->name . " (ID: " . $organizer->id . ")\n";
                }
                $organizerId = $organizer->id;
            }
        }

        // Create Event
        $event = Event::create([
            'organizer_id' => $organizerId,
            'title' => $requestData['title'],
            'slug' => Str::slug($requestData['title']),
            'description' => 'Test Description',
            'start_datetime' => now(),
            'end_datetime' => now()->addDays(1),
            'is_virtual' => true,
        ]);

        // Sync Co-Organizers
        $event->coOrganizers()->sync($requestData['organizers']);

        echo "Created Event: " . $event->title . " (ID: " . $event->id . ")\n";
        echo "Event Organizer ID: " . $event->organizer_id . "\n";

        // Verify Relationships
        if ($event->organizer_id == $organizerId) {
            echo "SUCCESS: Event is linked to the correct Organizer.\n";
        } else {
            echo "FAILURE: Event organizer_id mismatch.\n";
        }

        if ($event->organizers->count() > 0) {
            echo "SUCCESS: Event has co-organizers (backward compatibility accessor works).\n";
            echo "Co-Organizer Name: " . $event->organizers->first()->name . "\n";
        } else {
            echo "FAILURE: Event has no co-organizers.\n";
        }
    });
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
