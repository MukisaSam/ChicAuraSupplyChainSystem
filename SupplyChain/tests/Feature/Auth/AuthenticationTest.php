<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_users_are_redirected_to_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_supplier_users_are_redirected_to_supplier_dashboard(): void
    {
        $supplier = User::create([
            'name' => 'Supplier User',
            'email' => 'supplier@test.com',
            'password' => bcrypt('password'),
            'role' => 'supplier',
        ]);

        Supplier::create([
            'user_id' => $supplier->id,
            'business_address' => '123 Test St',
            'phone' => '1234567890',
            'license_document' => 'test.pdf',
            'materials_supplied' => json_encode(['Fabric']),
        ]);

        $response = $this->post('/login', [
            'email' => 'supplier@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/supplier/dashboard');
    }

    public function test_manufacturer_users_are_redirected_to_manufacturer_dashboard(): void
    {
        $manufacturer = User::create([
            'name' => 'Manufacturer User',
            'email' => 'manufacturer@test.com',
            'password' => bcrypt('password'),
            'role' => 'manufacturer',
        ]);

        Manufacturer::create([
            'user_id' => $manufacturer->id,
            'business_address' => '456 Test Ave',
            'phone' => '0987654321',
            'license_document' => 'test.pdf',
            'production_capacity' => 1000,
            'specialization' => json_encode(['Dresses']),
        ]);

        $response = $this->post('/login', [
            'email' => 'manufacturer@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/manufacturer/dashboard');
    }

    public function test_wholesaler_users_are_redirected_to_wholesaler_dashboard(): void
    {
        $wholesaler = User::create([
            'name' => 'Wholesaler User',
            'email' => 'wholesaler@test.com',
            'password' => bcrypt('password'),
            'role' => 'wholesaler',
        ]);

        Wholesaler::create([
            'user_id' => $wholesaler->id,
            'business_address' => '789 Test Blvd',
            'phone' => '5555555555',
            'license_document' => 'test.pdf',
            'business_type' => 'Retail Chain',
            'preferred_categories' => json_encode(['Women\'s Wear']),
            'monthly_order_volume' => 500,
        ]);

        $response = $this->post('/login', [
            'email' => 'wholesaler@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/wholesaler/dashboard');
    }

    public function test_debug_authentication_redirect(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        
        // Debug: Let's see what the actual redirect URL is
        $redirectUrl = $response->headers->get('Location');
        dump("Actual redirect URL: " . $redirectUrl);
        
        // For now, let's just assert that we're authenticated
        $this->assertTrue(true);
    }

    public function test_simple_authentication_works(): void
    {
        // Create a user without using the database
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        
        // Let's see what the response contains
        dump("Response status: " . $response->status());
        dump("Response headers: " . json_encode($response->headers->all()));
        
        // For now, just assert we're authenticated
        $this->assertTrue(true);
    }
}
