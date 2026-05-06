<?php

namespace Tests\Feature\Api\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Role::create(['name' => 'admin']);
        $this->user->assignRole('admin');
        $this->actingAs($this->user);
    }

    public function test_can_list_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson(route('admin.products.index'));
        $response->assertStatus(200)
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'slug', 'price']
            ]
        ]);
    }
}
