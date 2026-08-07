<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineCategory;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Setting;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles, Permissions & Users
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Settings
        Setting::firstOrCreate(
            ['id' => 1],
            [
                'pharmacy_name'  => 'Afzaal Pharmacy',
                'phone'          => '+92-42-1234567',
                'email'          => 'info@afzaalpharmacy.com',
                'address'        => 'Shop 12, Medical Complex, Lahore, Pakistan',
                'tax'            => 0,
                'currency'       => 'PKR',
                'currency_symbol'=> '₨',
                'invoice_prefix' => 'INV',
            ]
        );

        // 3. Medicine Categories
        $categories = [
            ['name' => 'Antibiotics',     'description' => 'Medicines used to treat bacterial infections'],
            ['name' => 'Analgesics',      'description' => 'Pain relief and fever reducers'],
            ['name' => 'Vitamins',        'description' => 'Vitamins and dietary supplements'],
            ['name' => 'Antacids',        'description' => 'Medicines for acidity and stomach issues'],
            ['name' => 'Antihistamines',  'description' => 'Allergy and cold relief medicines'],
            ['name' => 'Cardiovascular',  'description' => 'Heart and blood pressure medicines'],
            ['name' => 'Antidiabetics',   'description' => 'Diabetes management medicines'],
            ['name' => 'First Aid',       'description' => 'First aid supplies and antiseptics'],
            ['name' => 'Supplements',     'description' => 'Nutritional and health supplements'],
            ['name' => 'Dermatology',     'description' => 'Skin care and topical treatments'],
        ];

        foreach ($categories as $cat) {
            MedicineCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // 4. Suppliers
        $suppliers = [
            ['name' => 'GSK Pakistan',    'company' => 'GlaxoSmithKline',  'phone' => '042-111-475-000', 'email' => 'orders@gsk.com.pk',    'status' => 'active'],
            ['name' => 'Getz Pharma',     'company' => 'Getz Pharma Ltd',  'phone' => '021-111-439-800', 'email' => 'info@getzpharma.com',   'status' => 'active'],
            ['name' => 'Abbott Pakistan', 'company' => 'Abbott Labs',       'phone' => '021-111-222-111', 'email' => 'orders@abbott.pk',      'status' => 'active'],
            ['name' => 'Searle Pakistan', 'company' => 'Searle Company',   'phone' => '051-111-000-120', 'email' => 'sales@searlepk.com',    'status' => 'active'],
            ['name' => 'PharmEvo',        'company' => 'PharmEvo Pvt Ltd', 'phone' => '021-111-000-340', 'email' => 'supply@pharmevo.com',   'status' => 'active'],
        ];

        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(['name' => $sup['name']], $sup);
        }

        // 5. Customers
        $customers = [
            ['name' => 'Ahmad Khan',      'phone' => '0300-1234567', 'email' => 'ahmad@gmail.com',     'credit_limit' => 5000,  'credit_balance' => 0],
            ['name' => 'Fatima Bibi',     'phone' => '0301-2345678', 'email' => 'fatima@yahoo.com',    'credit_limit' => 3000,  'credit_balance' => 500],
            ['name' => 'Dr. Raza Hussain','phone' => '0302-3456789', 'email' => 'draza@hospital.com',  'credit_limit' => 20000, 'credit_balance' => 0],
            ['name' => 'Sara Malik',      'phone' => '0303-4567890', 'email' => 'sara@outlook.com',    'credit_limit' => 2000,  'credit_balance' => 0],
            ['name' => 'Tahir Mehmood',   'phone' => '0304-5678901', 'email' => 'tahir@gmail.com',     'credit_limit' => 10000, 'credit_balance' => 1200],
        ];

        foreach ($customers as $cust) {
            Customer::firstOrCreate(['phone' => $cust['phone']], $cust);
        }

        // 6. Sample Medicines
        $antibioticsCat  = MedicineCategory::where('name', 'Antibiotics')->first();
        $analgesicsCat   = MedicineCategory::where('name', 'Analgesics')->first();
        $vitaminsCat     = MedicineCategory::where('name', 'Vitamins')->first();
        $antacidsCat     = MedicineCategory::where('name', 'Antacids')->first();
        $antihistCat     = MedicineCategory::where('name', 'Antihistamines')->first();
        $suppCat         = MedicineCategory::where('name', 'Supplements')->first();
        $firstAidCat     = MedicineCategory::where('name', 'First Aid')->first();

        $medicines = [
            [
                'category_id' => $analgesicsCat?->id,
                'name' => 'Panadol 500mg (Strip of 10)',
                'manufacturer_name' => 'GSK Pakistan',
                'barcode' => 'PAK-GSK-001',
                'unit' => 'strip',
                'purchase_price' => 18.00,
                'sale_price' => 25.00,
                'quantity' => 200,
                'expiry_date' => Carbon::now()->addYears(2),
                'reorder_level' => 50,
                'status' => 'active',
            ],
            [
                'category_id' => $analgesicsCat?->id,
                'name' => 'Brufen 400mg (Strip of 10)',
                'manufacturer_name' => 'Abbott Pakistan',
                'barcode' => 'PAK-ABT-001',
                'unit' => 'strip',
                'purchase_price' => 40.00,
                'sale_price' => 55.00,
                'quantity' => 150,
                'expiry_date' => Carbon::now()->addMonths(18),
                'reorder_level' => 30,
                'status' => 'active',
            ],
            [
                'category_id' => $antibioticsCat?->id,
                'name' => 'Augmentin 625mg (Box of 10)',
                'manufacturer_name' => 'GSK Pakistan',
                'barcode' => 'PAK-GSK-002',
                'unit' => 'pcs',
                'purchase_price' => 350.00,
                'sale_price' => 450.00,
                'quantity' => 80,
                'expiry_date' => Carbon::now()->addYears(1),
                'reorder_level' => 20,
                'status' => 'active',
            ],
            [
                'category_id' => $antibioticsCat?->id,
                'name' => 'Amoxil 500mg Capsule',
                'manufacturer_name' => 'GSK Pakistan',
                'barcode' => 'PAK-GSK-003',
                'unit' => 'strip',
                'purchase_price' => 120.00,
                'sale_price' => 160.00,
                'quantity' => 5,   // Low stock
                'expiry_date' => Carbon::now()->addYears(1),
                'reorder_level' => 20,
                'status' => 'active',
            ],
            [
                'category_id' => $vitaminsCat?->id,
                'name' => 'Centrum Adult Multivitamin',
                'manufacturer_name' => 'Pfizer',
                'barcode' => 'PAK-PFZ-001',
                'unit' => 'bottle',
                'purchase_price' => 800.00,
                'sale_price' => 1100.00,
                'quantity' => 35,
                'expiry_date' => Carbon::now()->addYears(2),
                'reorder_level' => 10,
                'status' => 'active',
            ],
            [
                'category_id' => $antacidsCat?->id,
                'name' => 'Gaviscon Advance Liquid 150ml',
                'manufacturer_name' => 'Reckitt Pakistan',
                'barcode' => 'PAK-RKT-001',
                'unit' => 'bottle',
                'purchase_price' => 280.00,
                'sale_price' => 380.00,
                'quantity' => 60,
                'expiry_date' => Carbon::now()->addMonths(20),
                'reorder_level' => 15,
                'status' => 'active',
            ],
            [
                'category_id' => $antihistCat?->id,
                'name' => 'Zyrtec 10mg Tablet',
                'manufacturer_name' => 'Searle Pakistan',
                'barcode' => 'PAK-SRL-001',
                'unit' => 'tablet',
                'purchase_price' => 15.00,
                'sale_price' => 22.00,
                'quantity' => 300,
                'expiry_date' => Carbon::now()->addYears(2),
                'reorder_level' => 50,
                'status' => 'active',
            ],
            [
                'category_id' => $suppCat?->id,
                'name' => 'Ensure Original Vanilla 400g',
                'manufacturer_name' => 'Abbott Pakistan',
                'barcode' => 'PAK-ABT-002',
                'unit' => 'pcs',
                'purchase_price' => 1200.00,
                'sale_price' => 1600.00,
                'quantity' => 25,
                'expiry_date' => Carbon::now()->addYears(1),
                'reorder_level' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $firstAidCat?->id,
                'name' => 'Betadine Solution 100ml',
                'manufacturer_name' => 'Getz Pharma',
                'barcode' => 'PAK-GTZ-001',
                'unit' => 'bottle',
                'purchase_price' => 150.00,
                'sale_price' => 210.00,
                'quantity' => 40,
                'expiry_date' => Carbon::now()->addYears(3),
                'reorder_level' => 10,
                'status' => 'active',
            ],
            [
                'category_id' => $analgesicsCat?->id,
                'name' => 'Disprin 300mg (Strip of 10)',
                'manufacturer_name' => 'Reckitt Pakistan',
                'barcode' => 'PAK-RKT-002',
                'unit' => 'strip',
                'purchase_price' => 12.00,
                'sale_price' => 18.00,
                'quantity' => 0,    // Out of stock
                'expiry_date' => Carbon::now()->addMonths(6),
                'reorder_level' => 30,
                'status' => 'active',
            ],
            // Expired medicine for testing
            [
                'category_id' => $antibioticsCat?->id,
                'name' => 'Ciprofloxacin 500mg (Old Stock)',
                'manufacturer_name' => 'Generic',
                'barcode' => 'PAK-EXP-001',
                'unit' => 'strip',
                'purchase_price' => 80.00,
                'sale_price' => 110.00,
                'quantity' => 20,
                'expiry_date' => Carbon::now()->subMonths(2),  // Expired
                'reorder_level' => 10,
                'status' => 'active',
            ],
        ];

        foreach ($medicines as $med) {
            if ($med['category_id']) {
                Medicine::firstOrCreate(['barcode' => $med['barcode']], $med);
            }
        }

        $this->command->info('✅ Sample data seeded: Categories, Suppliers, Customers, Medicines.');
        $this->command->info('');
        $this->command->info('🔐 Demo Login Credentials:');
        $this->command->info('   Super Admin  → admin@pharmacy.com     / password');
        $this->command->info('   Pharmacist   → pharmacist@pharmacy.com / password');
        $this->command->info('   Cashier      → cashier@pharmacy.com   / password');
        $this->command->info('   Store Manager→ manager@pharmacy.com    / password');
    }
}
