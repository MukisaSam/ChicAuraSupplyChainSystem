<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\SupplyRequest;
use App\Models\SuppliedItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $supplierUser;
    protected $supplier;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create supplier user and profile
        $this->supplierUser = User::create([
            'name' => 'Test Supplier',
            'email' => 'supplier@test.com',
            'password' => bcrypt('password'),
            'role' => 'supplier',
        ]);

        $this->supplier = Supplier::create([
            'user_id' => $this->supplierUser->id,
            'business_address' => '123 Test Street',
            'phone' => '1234567890',
            'license_document' => 'test_license.pdf',
            'materials_supplied' => ['cotton', 'silk'],
        ]);
    }

    public function test_supplier_can_access_dashboard()
    {
        $response = $this->actingAs($this->supplierUser)
            ->get('/supplier/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Supplier Dashboard');
    }

    public function test_supplier_can_view_supply_requests()
    {
        $response = $this->actingAs($this->supplierUser)
            ->get('/supplier/supply-requests');

        $response->assertStatus(200);
        $response->assertSee('Supply Requests');
    }

    public function test_supplier_can_view_supplied_items()
    {
        $response = $this->actingAs($this->supplierUser)
            ->get('/supplier/supplied-items');

        $response->assertStatus(200);
        $response->assertSee('Supplied Items');
    }

    public function test_supplier_can_access_analytics()
    {
        $response = $this->actingAs($this->supplierUser)
            ->get('/supplier/analytics');

        $response->assertStatus(200);
        $response->assertSee('Analytics');
    }

    public function test_supplier_can_access_chat()
    {
        $response = $this->actingAs($this->supplierUser)
            ->get('/supplier/chat');

        $response->assertStatus(200);
        $response->assertSee('Chat with Manufacturer');
    }

    public function test_supplier_can_access_reports()
    {
        $response = $this->actingAs($this->supplierUser)
            ->get('/supplier/reports');

        $response->assertStatus(200);
        $response->assertSee('Reports');
    }

    public function test_non_supplier_cannot_access_supplier_routes()
    {
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($adminUser)
            ->get('/supplier/dashboard');

        $response->assertStatus(403);
    }

    public function test_supplier_can_view_individual_supply_request()
    {
        $supplyRequest = SupplyRequest::create([
            'supplier_id' => $this->supplier->id,
            'item_id' => 1,
            'quantity' => 100,
            'due_date' => now()->addDays(14),
            'status' => 'pending',
            'payment_type' => 'cash',
            'delivery_method' => 'delivery',
        ]);

        $response = $this->actingAs($this->supplierUser)
            ->get("/supplier/supply-requests/{$supplyRequest->id}");

        $response->assertStatus(200);
        $response->assertSee('Supply Request');
    }

    public function test_supplier_can_view_individual_supplied_item()
    {
        $suppliedItem = SuppliedItem::create([
            'supplier_id' => $this->supplier->id,
            'item_id' => 1,
            'price' => 10.00,
            'delivered_quantity' => 50,
            'delivery_date' => now(),
            'quality_rating' => 4.5,
            'status' => 'delivered',
        ]);

        $response = $this->actingAs($this->supplierUser)
            ->get("/supplier/supplied-items/{$suppliedItem->id}");

        $response->assertStatus(200);
        $response->assertSee('Supplied Item Details');
    }

    public function test_supplier_cannot_access_other_supplier_data()
    {
        $otherSupplierUser = User::create([
            'name' => 'Other Supplier',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
            'role' => 'supplier',
        ]);

        $otherSupplier = Supplier::create([
            'user_id' => $otherSupplierUser->id,
            'business_address' => '456 Other Street',
            'phone' => '0987654321',
            'license_document' => 'other_license.pdf',
            'materials_supplied' => ['wool'],
        ]);

        $supplyRequest = SupplyRequest::create([
            'supplier_id' => $otherSupplier->id,
            'item_id' => 1,
            'quantity' => 100,
            'due_date' => now()->addDays(14),
            'status' => 'pending',
            'payment_type' => 'cash',
            'delivery_method' => 'delivery',
        ]);

        $response = $this->actingAs($this->supplierUser)
            ->get("/supplier/supply-requests/{$supplyRequest->id}");

        $response->assertStatus(403);
    }
} 