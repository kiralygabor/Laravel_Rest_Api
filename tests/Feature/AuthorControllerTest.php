<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_index_returns_all_authors()
    {
        Author::factory()->create([
            'name' => 'Nagy József',
            'age' => 34,
            'gender' => 'male',
            'nationality' => 'Hungarian',
        ]);
     

        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Nagy József'])
            ->assertJsonFragment(['age' => 34])
            ->assertJsonFragment(['gender' => 'male'])
            ->assertJsonFragment(['nationality' => 'Hungarian']);
    } 
}
