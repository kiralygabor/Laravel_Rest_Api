<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\User; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class BookControllerTest extends TestCase
{
    use RefreshDatabase; 
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_index_returns_all_books()
    {
        Book::factory()->create([
            'name' => 'Kalózok',
            'category_id' => Category::factory(),
            'price' => 20.99,
            'publication_date' => '2021-05-10',
            'author_id' => Author::factory(),
            'isbn' => '978-1-00001-001-1',
            'cover' => 'covers/book30.jpg',
        ]);
     

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Kalózok'])
            ->assertJsonFragment(['category_id' => 3])
            ->assertJsonFragment(['price' => 20.99])
            ->assertJsonFragment(['publication_date' => '2021-05-10'])
            ->assertJsonFragment(['edition' => 5])
            ->assertJsonFragment(['author_id' => 3])
            ->assertJsonFragment(['isbn' => '978-1-00001-001-1'])
            ->assertJsonFragment(['cover' => 'covers/book30.jpg']);

    } 
}
