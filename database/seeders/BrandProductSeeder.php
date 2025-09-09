<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first company admin user
        $companyAdmin = User::role('CompanyAdmin')->first();
        
        if (!$companyAdmin) {
            $this->command->warn('No company admin user found. Please run the TestUserSeeder first.');
            return;
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create some sample brands
            $brands = [
                [
                    'company_id' => $companyAdmin->company_id,
                    'name' => 'Samsung',
                    'status' => 'active',
                ],
                [
                    'company_id' => $companyAdmin->company_id,
                    'name' => 'Apple',
                    'status' => 'active',
                ],
                [
                    'company_id' => $companyAdmin->company_id,
                    'name' => 'Xiaomi',
                    'status' => 'active',
                ],
                [
                    'company_id' => $companyAdmin->company_id,
                    'name' => 'OnePlus',
                    'status' => 'active',
                ],
                [
                    'company_id' => $companyAdmin->company_id,
                    'name' => 'Oppo',
                    'status' => 'inactive',
                ],
            ];

            $createdBrands = [];
            foreach ($brands as $brandData) {
                $brand = Brand::create($brandData);
                $createdBrands[] = $brand;
                $this->command->info("Created brand: {$brand->name}");
            }

            // Create sample products for each brand
            $products = [
                // Samsung products
                ['name' => 'Galaxy S21', 'description' => 'Flagship smartphone with Exynos 2100', 'status' => 'active'],
                ['name' => 'Galaxy Note 20', 'description' => 'Productivity smartphone with S Pen', 'status' => 'active'],
                ['name' => 'Galaxy A52', 'description' => 'Mid-range smartphone with 90Hz display', 'status' => 'active'],
                
                // Apple products
                ['name' => 'iPhone 13 Pro', 'description' => 'Pro camera system with 3x optical zoom', 'status' => 'active'],
                ['name' => 'iPhone SE', 'description' => 'Compact design with A15 Bionic chip', 'status' => 'active'],
                
                // Xiaomi products
                ['name' => 'Redmi Note 11', 'description' => 'Budget smartphone with AMOLED display', 'status' => 'active'],
                ['name' => 'Mi 11 Ultra', 'description' => 'Flagship with 50MP camera system', 'status' => 'active'],
                
                // OnePlus products
                ['name' => 'OnePlus 10 Pro', 'description' => 'Flagship with Hasselblad camera', 'status' => 'active'],
                ['name' => 'OnePlus Nord 2', 'description' => 'Mid-range with 90Hz display', 'status' => 'active'],
                
                // Oppo products (inactive brand)
                ['name' => 'Find X5 Pro', 'description' => 'Flagship with MariSilicon X NPU', 'status' => 'inactive'],
            ];

            $brandIndex = 0;
            $productCount = 0;
            
            foreach ($products as $productData) {
                // Cycle through brands for product assignment
                $brand = $createdBrands[$brandIndex % count($createdBrands)];
                $brandIndex++;
                
                $product = new Product([
                    'brand_id' => $brand->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'status' => $productData['status'],
                ]);
                
                $product->save();
                $productCount++;
            }

            // Commit the transaction
            DB::commit();
            
            $this->command->info("Successfully seeded {$brandIndex} brands and {$productCount} products!");
            
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            $this->command->error('Error seeding brands and products: ' . $e->getMessage());
        }
    }
}
