<?php

namespace App\Console\Commands;

use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPackageExpirations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packages:check-expirations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update the status of expired packages';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today();
        $expiredCount = 0;
        
        // Get packages that are active or pending and have an end date in the past
        $expiredPackages = Package::whereIn('status', ['active', 'pending'])
            ->whereDate('end_date', '<', $today)
            ->get();
        
        foreach ($expiredPackages as $package) {
            try {
                $package->update(['status' => 'expired']);
                $expiredCount++;
                
                // Log the expiration
                Log::info("Package #{$package->id} for company {$package->company->name} has expired.");
                
                // Here you could also send notifications to the company admin
                // $package->company->admin->notify(new PackageExpired($package));
                
            } catch (\Exception $e) {
                Log::error("Failed to expire package #{$package->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Successfully expired {$expiredCount} packages.");
        return 0;
    }
}
