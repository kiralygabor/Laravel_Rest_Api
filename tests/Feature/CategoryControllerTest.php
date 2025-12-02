<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_categories()
    {
        Category::factory()->create(['name' => 'Fantasy']);
        Category::factory()->create(['name' => 'Valami']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Fantasy'])
                 ->assertJsonFragment(['name' => 'Valami']);
    }

    public function test_index_filters_by_needle()
    {
        Category::factory()->create(['name' => 'Fantasy']);
        Category::factory()->create(['name' => 'Valami']);

        $response = $this->getJson('/api/categories?needle=Fan');

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Fantasy'])
                 ->assertJsonMissing(['name' => 'Valami']);
    }

    public function test_store_creates_new_category()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/categories', [
            'name' => 'History',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'History']);

        $this->assertDatabaseHas('categories', ['name' => 'History']);
    }

    public function test_update_modifies_existing_category()
    {
        $category = Category::factory()->create(['name' => 'Fantasy']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/categories/{$category->id}", [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Category']);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Category']);
    }

    public function test_update_returns_404_for_missing_category()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/categories/999', [
            'name' => 'Nem létező',
        ]);

        $response->assertStatus(404);
    }

    public function test_delete_removes_category()
{
    $category = Category::factory()->create(['name' => 'Fantasy']);

    $user = User::factory()->create();
    $token = $user->createToken('TestToken')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson("/api/categories/{$category->id}");

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'Category deleted successfully',
                 'id' => (string)$category->id
             ]);

    $this->assertDatabaseMissing('categories', ['id' => (string)$category->id]);
}

}
