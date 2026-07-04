<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Category;
use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InventorySystemTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function createRole(string $name, string $displayName): Role
    {
        return Role::create(['name' => $name, 'display_name' => $displayName]);
    }

    private function createUser(string $roleName = 'admin', array $overrides = []): User
    {
        $role = Role::where('name', $roleName)->first()
            ?? $this->createRole($roleName, ucfirst($roleName));

        return User::create(array_merge([
            'role_id'  => $role->id,
            'name'     => 'Test ' . ucfirst($roleName),
            'email'    => $roleName . '@test.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ], $overrides));
    }

    private function createCategory(string $name = 'Elektronik', string $code = null): Category
    {
        $code = $code ?? strtoupper(substr(preg_replace('/[^A-Z]/', '', strtoupper($name)), 0, 6) . rand(100, 999));
        return Category::create(['code' => $code, 'name' => $name, 'description' => 'Test category']);
    }

    private function createItem(Category $category, array $overrides = [], ?User $createdBy = null): Item
    {
        // If no user provided, we need to create/get one for the FK
        if ($createdBy === null) {
            $adminRole = Role::where('name', 'admin')->first()
                ?? $this->createRole('admin', 'Administrator');
            $createdBy = User::where('email', 'admin_default@test.com')->first()
                ?? User::create([
                    'role_id'   => $adminRole->id,
                    'name'      => 'Default Admin',
                    'email'     => 'admin_default@test.com',
                    'password'  => Hash::make('password'),
                    'is_active' => true,
                ]);
        }

        return Item::create(array_merge([
            'category_id' => $category->id,
            'created_by'  => $createdBy->id,
            'code'        => 'ITM-' . uniqid(),
            'name'        => 'Test Item',
            'stock'       => 10,
            'min_stock'   => 2,
            'condition'   => 'good',
            'location'    => 'Gudang A',
        ], $overrides));
    }

    // ── 1. Authentication Tests ──────────────────────────────────────────────

    /** @test */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $this->createUser('admin');

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function test_inactive_user_cannot_login(): void
    {
        $this->createUser('admin', ['is_active' => false, 'email' => 'inactive@test.com']);

        // Inactive user can technically log in (Breeze doesn't block it on login)
        // BUT once authenticated, the EnsureUserIsActive middleware should kick them out
        // We test this by logging in then hitting a protected route
        $this->post('/login', [
            'email'    => 'inactive@test.com',
            'password' => 'password',
        ]);

        // Try to access dashboard — middleware should redirect back to login
        $response = $this->get('/dashboard');
        $response->assertRedirect();
    }

    /** @test */
    public function test_user_can_logout(): void
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)
             ->post('/logout')
             ->assertRedirect(); // Breeze redirects to '/', which then goes to /login

        $this->assertGuest();
    }

    /** @test */
    public function test_forgot_password_page_is_accessible(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    // ── 2. Role Access Control Tests ─────────────────────────────────────────

    /** @test */
    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createUser('admin');
        $this->actingAs($admin)->get('/dashboard')->assertStatus(200);
    }

    /** @test */
    public function test_staff_can_access_dashboard(): void
    {
        $staff = $this->createUser('staff', ['email' => 'staff@test.com']);
        $this->actingAs($staff)->get('/dashboard')->assertStatus(200);
    }

    /** @test */
    public function test_manager_can_access_dashboard(): void
    {
        $manager = $this->createUser('manager', ['email' => 'manager@test.com']);
        $this->actingAs($manager)->get('/dashboard')->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_access_user_management(): void
    {
        $admin = $this->createUser('admin');
        $this->actingAs($admin)->get('/users')->assertStatus(200);
    }

    /** @test */
    public function test_staff_cannot_access_user_management(): void
    {
        $staff = $this->createUser('staff', ['email' => 'staff2@test.com']);
        $this->actingAs($staff)->get('/users')->assertStatus(403);
    }

    /** @test */
    public function test_manager_cannot_access_user_management(): void
    {
        $manager = $this->createUser('manager', ['email' => 'mgr@test.com']);
        $this->actingAs($manager)->get('/users')->assertStatus(403);
    }

    /** @test */
    public function test_manager_can_access_reports(): void
    {
        $manager = $this->createUser('manager', ['email' => 'mgr2@test.com']);
        $this->actingAs($manager)->get('/reports')->assertStatus(200);
    }

    /** @test */
    public function test_manager_cannot_create_items(): void
    {
        $manager = $this->createUser('manager', ['email' => 'mgr3@test.com']);
        $this->actingAs($manager)->get('/items/create')->assertStatus(403);
    }

    // ── 3. Category CRUD Tests ────────────────────────────────────────────────

    /** @test */
    public function test_admin_can_view_categories(): void
    {
        $admin = $this->createUser('admin');
        $this->actingAs($admin)->get('/categories')->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_create_category(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->post('/categories', [
            'code'        => 'ELK',
            'name'        => 'Elektronik',
            'description' => 'Perangkat elektronik kantor',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Elektronik']);
    }

    /** @test */
    public function test_category_name_is_required(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->post('/categories', [
            'name' => '',
            'code' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function test_admin_can_update_category(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory('Elektronik');

        $response = $this->actingAs($admin)->patch("/categories/{$category->id}", [
            'code' => 'ELK',
            'name' => 'Elektronik Updated',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Elektronik Updated']);
    }

    /** @test */
    public function test_admin_can_delete_category(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory('ToDelete');

        $response = $this->actingAs($admin)->delete("/categories/{$category->id}");
        $response->assertRedirect();
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    // ── 4. Item CRUD Tests ────────────────────────────────────────────────────

    /** @test */
    public function test_admin_can_view_items_list(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $this->createItem($category, ['name' => 'Laptop Dell']);

        $response = $this->actingAs($admin)->get('/items');
        $response->assertStatus(200)->assertSee('Laptop Dell');
    }

    /** @test */
    public function test_admin_can_create_item(): void
    {
        Storage::fake('public');
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();

        $response = $this->actingAs($admin)->post('/items', [
            'name'        => 'Laptop HP',
            'category_id' => $category->id,
            'stock'       => 5,
            'min_stock'   => 1,
            'condition'   => 'good',
            'location'    => 'Gudang A',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('items', ['name' => 'Laptop HP']);
    }

    /** @test */
    public function test_item_code_is_auto_generated(): void
    {
        Storage::fake('public');
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();

        $this->actingAs($admin)->post('/items', [
            'name'        => 'Monitor LG',
            'category_id' => $category->id,
            'stock'       => 3,
            'min_stock'   => 1,
            'condition'   => 'good',
            'location'    => 'Gudang B',
        ]);

        $item = Item::where('name', 'Monitor LG')->first();
        $this->assertNotNull($item->code);
        $this->assertNotEmpty($item->code);
    }

    /** @test */
    public function test_item_validation_fails_without_required_fields(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->post('/items', []);
        $response->assertSessionHasErrors(['name', 'category_id', 'stock', 'min_stock', 'condition']);
    }

    /** @test */
    public function test_admin_can_view_item_detail(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['name' => 'Keyboard Logitech']);

        $response = $this->actingAs($admin)->get("/items/{$item->id}");
        $response->assertStatus(200)->assertSee('Keyboard Logitech');
    }

    /** @test */
    public function test_admin_can_edit_item(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['name' => 'Old Name']);

        $response = $this->actingAs($admin)->put("/items/{$item->id}", [
            'name'        => 'New Name',
            'category_id' => $category->id,
            'stock'       => 10,
            'min_stock'   => 2,
            'condition'   => 'good',
            'location'    => 'Gudang A',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('items', ['name' => 'New Name']);
    }

    /** @test */
    public function test_admin_can_soft_delete_item(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category);

        $response = $this->actingAs($admin)->delete("/items/{$item->id}");
        $response->assertRedirect();

        // Soft deleted - still in database with deleted_at
        $this->assertSoftDeleted('items', ['id' => $item->id]);
    }

    /** @test */
    public function test_item_search_works(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $this->createItem($category, ['name' => 'Laptop Pro', 'code' => 'LPT-001']);
        $this->createItem($category, ['name' => 'Mouse Wireless', 'code' => 'MSE-001']);

        $response = $this->actingAs($admin)->get('/items?search=Laptop');
        $response->assertStatus(200)
                 ->assertSee('Laptop Pro')
                 ->assertDontSee('Mouse Wireless');
    }

    /** @test */
    public function test_item_filter_by_category_works(): void
    {
        $admin     = $this->createUser('admin');
        $catA      = $this->createCategory('Elektronik');
        $catB      = $this->createCategory('Furnitur');
        $this->createItem($catA, ['name' => 'Laptop']);
        $this->createItem($catB, ['name' => 'Meja Kerja']);

        $response = $this->actingAs($admin)->get("/items?category_id={$catA->id}");
        $response->assertStatus(200)
                 ->assertSee('Laptop')
                 ->assertDontSee('Meja Kerja');
    }

    /** @test */
    public function test_admin_can_upload_item_image(): void
    {
        Storage::fake('public');

        // Skip if GD extension not installed
        if (!function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category, [], $admin);

        $file = UploadedFile::fake()->image('item.jpg', 800, 600);

        $this->actingAs($admin)->put("/items/{$item->id}", [
            'name'        => $item->name,
            'category_id' => $category->id,
            'stock'       => $item->stock,
            'min_stock'   => $item->min_stock,
            'condition'   => $item->condition,
            'location'    => $item->location,
            'image'       => $file,
        ]);

        $item->refresh();
        $this->assertNotNull($item->image);
        Storage::disk('public')->assertExists($item->image);
    }

    /** @test */
    public function test_cannot_delete_item_with_active_borrowing(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category);

        // Create an active borrowing for this item
        $borrowing = Borrowing::create([
            'borrowing_code'       => 'BRW-TEST-001',
            'borrower_name'        => 'Test Borrower',
            'borrow_date'          => now()->toDateString(),
            'expected_return_date' => now()->addDays(7)->toDateString(),
            'status'               => 'borrowed',
            'created_by'           => $admin->id,
        ]);

        BorrowingDetail::create([
            'borrowing_id' => $borrowing->id,
            'item_id'      => $item->id,
            'quantity'     => 1,
        ]);

        $response = $this->actingAs($admin)->delete("/items/{$item->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('items', ['id' => $item->id, 'deleted_at' => null]);
    }

    // ── 5. Borrowing Tests ────────────────────────────────────────────────────

    /** @test */
    public function test_admin_can_view_borrowings(): void
    {
        $admin = $this->createUser('admin');
        $this->actingAs($admin)->get('/borrowings')->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_create_borrowing(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['stock' => 5]);

        $response = $this->actingAs($admin)->post('/borrowings', [
            'borrower_name'        => 'John Doe',
            'borrower_department'  => 'IT Division',
            'borrower_phone'       => '08123456789',
            'borrower_email'       => 'john@telkomsel.com',
            'borrow_date'          => now()->toDateString(),
            'expected_return_date' => now()->addDays(7)->toDateString(),
            'notes'                => 'Test borrowing',
            'items'                => [
                ['item_id' => $item->id, 'quantity' => 2, 'notes' => ''],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('borrowings', ['borrower_name' => 'John Doe']);

        // Stock should be reduced
        $item->refresh();
        $this->assertEquals(3, $item->stock);
    }

    /** @test */
    public function test_borrowing_reduces_item_stock(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['stock' => 10]);

        $this->actingAs($admin)->post('/borrowings', [
            'borrower_name'        => 'Jane Doe',
            'borrow_date'          => now()->toDateString(),
            'expected_return_date' => now()->addDays(3)->toDateString(),
            'items'                => [
                ['item_id' => $item->id, 'quantity' => 3, 'notes' => ''],
            ],
        ]);

        $item->refresh();
        $this->assertEquals(7, $item->stock);
    }

    /** @test */
    public function test_cannot_borrow_more_than_available_stock(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['stock' => 2]);

        $response = $this->actingAs($admin)->post('/borrowings', [
            'borrower_name'        => 'Over Borrower',
            'borrow_date'          => now()->toDateString(),
            'expected_return_date' => now()->addDays(3)->toDateString(),
            'items'                => [
                ['item_id' => $item->id, 'quantity' => 5, 'notes' => ''],
            ],
        ]);

        // Should redirect back with error
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Stock should not change
        $item->refresh();
        $this->assertEquals(2, $item->stock);
    }

    /** @test */
    public function test_admin_can_process_return(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['stock' => 5]);

        // Create a borrowing
        $borrowing = Borrowing::create([
            'borrowing_code'       => 'BRW-RET-001',
            'borrower_name'        => 'Return Test',
            'borrow_date'          => now()->toDateString(),
            'expected_return_date' => now()->addDays(7)->toDateString(),
            'status'               => 'borrowed',
            'created_by'           => $admin->id,
        ]);

        BorrowingDetail::create([
            'borrowing_id' => $borrowing->id,
            'item_id'      => $item->id,
            'quantity'     => 2,
        ]);

        // Update stock to simulate it was reduced
        $item->update(['stock' => 3]);

        $response = $this->actingAs($admin)->post("/borrowings/{$borrowing->id}/return");
        $response->assertRedirect();

        $borrowing->refresh();
        $this->assertEquals('returned', $borrowing->status);

        // Stock should be restored
        $item->refresh();
        $this->assertEquals(5, $item->stock);
    }

    /** @test */
    public function test_cannot_return_already_returned_borrowing(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category);

        $borrowing = Borrowing::create([
            'borrowing_code'       => 'BRW-DONE-001',
            'borrower_name'        => 'Already Returned',
            'borrow_date'          => now()->toDateString(),
            'expected_return_date' => now()->addDays(7)->toDateString(),
            'actual_return_date'   => now()->toDateString(),
            'status'               => 'returned',
            'created_by'           => $admin->id,
        ]);

        $response = $this->actingAs($admin)->post("/borrowings/{$borrowing->id}/return");
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function test_borrowing_history_is_accessible(): void
    {
        $admin = $this->createUser('admin');
        $this->actingAs($admin)->get('/borrowings/history/all')->assertStatus(200);
    }

    /** @test */
    public function test_borrowing_detail_page_shows_items(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['name' => 'Test Gadget']);

        $borrowing = Borrowing::create([
            'borrowing_code'       => 'BRW-SHOW-001',
            'borrower_name'        => 'Detail Test Person',
            'borrow_date'          => now()->toDateString(),
            'expected_return_date' => now()->addDays(7)->toDateString(),
            'status'               => 'borrowed',
            'created_by'           => $admin->id,
        ]);

        BorrowingDetail::create([
            'borrowing_id' => $borrowing->id,
            'item_id'      => $item->id,
            'quantity'     => 1,
        ]);

        $response = $this->actingAs($admin)->get("/borrowings/{$borrowing->id}");
        $response->assertStatus(200)->assertSee('Test Gadget');
    }

    // ── 6. Dashboard Tests ────────────────────────────────────────────────────

    /** @test */
    public function test_dashboard_shows_stats(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $this->createItem($category, ['stock' => 10]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200)
                 ->assertSee('Total Barang')
                 ->assertSee('Barang Tersedia')
                 ->assertSee('Dipinjam Aktif');
    }

    // ── 7. User Management Tests ──────────────────────────────────────────────

    /** @test */
    public function test_admin_can_create_user(): void
    {
        $admin    = $this->createUser('admin');
        $staffRole = Role::create(['name' => 'staff', 'display_name' => 'Staff']);

        $response = $this->actingAs($admin)->post('/users', [
            'name'                  => 'New Staff',
            'email'                 => 'newstaff@test.com',
            'role_id'               => $staffRole->id,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'newstaff@test.com']);
    }

    /** @test */
    public function test_admin_can_deactivate_user(): void
    {
        $admin  = $this->createUser('admin');
        $staff  = $this->createUser('staff', ['email' => 'staff_del@test.com']);

        $response = $this->actingAs($admin)->delete("/users/{$staff->id}");
        $response->assertRedirect();

        $staff->refresh();
        $this->assertFalse((bool) $staff->is_active);
    }

    /** @test */
    public function test_admin_cannot_deactivate_themselves(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->delete("/users/{$admin->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $admin->refresh();
        $this->assertTrue((bool) $admin->is_active);
    }

    // ── 8. Reports Tests ──────────────────────────────────────────────────────

    /** @test */
    public function test_admin_can_access_reports_page(): void
    {
        $admin = $this->createUser('admin');
        $this->actingAs($admin)->get('/reports')->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_export_items_excel(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $this->createItem($category, ['name' => 'Export Test Item'], $admin);

        $response = $this->actingAs($admin)->get('/reports/export-items');
        // Stream downloads may not set Content-Type correctly in test, just check status
        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_export_borrowings_excel(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->get('/reports/export-borrowings');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_export_pdf(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->get('/reports/export-pdf');
        $response->assertStatus(200);
    }

    // ── 9. API Tests ──────────────────────────────────────────────────────────

    /** @test */
    public function test_api_login_returns_token(): void
    {
        $this->createUser('admin');

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['token', 'user'],
                 ]);
    }

    /** @test */
    public function test_api_login_fails_with_invalid_credentials(): void
    {
        $this->createUser('admin');

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function test_api_items_endpoint_requires_auth(): void
    {
        $response = $this->getJson('/api/v1/items');
        $response->assertStatus(401);
    }

    /** @test */
    public function test_api_items_returns_list(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $this->createItem($category, ['name' => 'API Item']);

        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/items');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data',
                     'meta',
                 ]);
    }

    /** @test */
    public function test_api_me_returns_user_info(): void
    {
        $admin = $this->createUser('admin');
        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/auth/me');
        $response->assertStatus(200)
                 ->assertJsonPath('data.email', 'admin@test.com');
    }

    // ── 10. Low Stock Notification Tests ─────────────────────────────────────

    /** @test */
    public function test_low_stock_item_is_flagged(): void
    {
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['stock' => 1, 'min_stock' => 5]);

        $this->assertTrue($item->is_low_stock);
    }

    /** @test */
    public function test_sufficient_stock_item_is_not_flagged(): void
    {
        $category = $this->createCategory();
        $item     = $this->createItem($category, ['stock' => 10, 'min_stock' => 2]);

        $this->assertFalse($item->is_low_stock);
    }

    /** @test */
    public function test_dashboard_shows_low_stock_items(): void
    {
        $admin    = $this->createUser('admin');
        $category = $this->createCategory();
        $this->createItem($category, ['name' => 'Stok Menipis Item', 'stock' => 1, 'min_stock' => 5]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200)->assertSee('Stok Menipis');
    }
}
