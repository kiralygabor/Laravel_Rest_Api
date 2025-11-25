<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_index_returns_all_categories()
    {
        Category::factory()->create([
            'name' => 'Fantasies',
        ]);
     

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Fantasies']);
         
    } 
}
