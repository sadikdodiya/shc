<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\PartEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all active items
        $items = Item::active()->with('company')->get();
        
        if ($items->isEmpty()) {
            $this->command->info('No active items found. Please run ItemSeeder first.');
            return;
        }
        
        // Get admin users for each company
        $adminUsers = [];
        foreach ($items->groupBy('company_id') as $companyId => $companyItems) {
            $adminUser = User::where('company_id', $companyId)
                ->role('CompanyAdmin')
                ->first();
                
            if ($adminUser) {
                $adminUsers[$companyId] = $adminUser->id;
            } else {
                // Fallback to any user in the company
                $fallbackUser = User::where('company_id', $companyId)->first();
                if ($fallbackUser) {
                    $adminUsers[$companyId] = $fallbackUser->id;
                }
            }
        }
        
        if (empty($adminUsers)) {
            $this->command->info('No users found. Please create users first.');
            return;
        }
        
        $this->command->info('Seeding part entries...');
        $bar = $this->command->getOutput()->createProgressBar($items->count() * 5);
        
        $entries = [];
        
        foreach ($items as $item) {
            $companyId = $item->company_id;
            $userId = $adminUsers[$companyId] ?? null;
            
            if (!$userId) {
                continue; // Skip if no user is available for this company
            }
            
            // Initial stock entry
            $entries[] = [
                'item_id' => $item->id,
                'type' => 'in',
                'quantity' => $item->minimum_stock > 0 ? $item->minimum_stock * 2 : 100,
                'reference' => 'INIT-' . strtoupper(uniqid()),
                'notes' => 'Initial stock',
                'user_id' => $userId,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ];
            
            $bar->advance();
            
            // Add some random stock movements
            for ($i = 0; $i < 4; $i++) {
                $type = rand(0, 1) ? 'in' : 'out';
                $quantity = $type === 'in' 
                    ? rand(1, 50) 
                    : rand(1, 20);
                
                $entries[] = [
                    'item_id' => $item->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'reference' => $type === 'in' 
                        ? 'PUR-' . strtoupper(uniqid())
                        : 'ISSUE-' . strtoupper(uniqid()),
                    'notes' => $type === 'in' 
                        ? 'Purchase order received' 
                        : 'Issued for job',
                    'user_id' => $userId,
                    'created_at' => now()->subDays(rand(1, 29)),
                    'updated_at' => now()->subDays(rand(1, 29)),
                ];
                
                $bar->advance();
            }
            
            // Insert in chunks to improve performance
            if (count($entries) >= 100) {
                PartEntry::insert($entries);
                $entries = [];
            }
        }
        
        // Insert any remaining entries
        if (!empty($entries)) {
            PartEntry::insert($entries);
        }
        
        // Update stock quantities for all items
        $this->command->info('\nUpdating item stock quantities...');
        $items = Item::with('partEntries')->get();
        
        foreach ($items as $item) {
            $item->updateStockFromEntries();
        }
        
        $bar->finish();
        $this->command->newLine();
        $this->command->info('Part entries seeded successfully!');
    }
}
