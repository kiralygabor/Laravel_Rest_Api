<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_books()
    {
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        Book::factory()->create(['name' => 'Book One', 'author_id' => $author->id, 'category_id' => $category->id]);
        Book::factory()->create(['name' => 'Book Two', 'author_id' => $author->id, 'category_id' => $category->id]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Book One'])
                 ->assertJsonFragment(['name' => 'Book Two']);
    }

    public function test_index_filters_by_needle()
    {
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        Book::factory()->create(['name' => 'Tüskevár', 'author_id' => $author->id, 'category_id' => $category->id]);
        Book::factory()->create(['name' => 'Vuk', 'author_id' => $author->id, 'category_id' => $category->id]);

        $response = $this->getJson('/api/books?needle=Tüske');

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Tüskevár'])
                 ->assertJsonMissing(['name' => 'Vuk']);
    }

    public function test_store_creates_new_book()
{
    $author = Author::factory()->create();
    $category = Category::factory()->create();

    $user = User::factory()->create();
    $token = $user->createToken('TestToken')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/books', [
        'name' => 'Tüskevár',
        'author_id' => $author->id,
        'category_id' => $category->id,
        'price' => 55.74,
        'publication_date' => '1986-12-25',
        'isbn' => '9782857749653',
        'cover' => 'covers/tuskevar.jpg',
        'edition' => '1',
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment(['name' => 'Tüskevár']);

    $this->assertDatabaseHas('books', ['name' => 'Tüskevár']);
}


    public function test_update_modifies_existing_book()
    {
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        $book = Book::factory()->create(['name' => 'Old Book', 'author_id' => $author->id, 'category_id' => $category->id]);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/books/{$book->id}", [
            'name' => 'Updated Book',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'price' => 60.0,
            'publication_date' => '1987-01-01',
            'isbn' => '9782857749654',
            'cover' => 'covers/updated.jpg',
            'edition' => '2',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Book']);

        $this->assertDatabaseHas('books', ['id' => $book->id, 'name' => 'Updated Book']);
    }

    public function test_update_returns_404_for_missing_book()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/books/999', [
            'name' => 'Nem létező',
            'author_id' => 1,
            'category_id' => 1,
            'price' => 10,
            'publication_date' => '2000-01-01',
            'isbn' => '9780000000000',
            'cover' => 'covers/none.jpg',
            'edition' => '1',
        ]);

        $response->assertStatus(404);
    }

    public function test_delete_removes_book()
{
    $author = Author::factory()->create();
    $category = Category::factory()->create();

    $book = Book::factory()->create([
        'name' => 'The London Fog',
        'author_id' => $author->id,
        'category_id' => $category->id
    ]);

    $user = User::factory()->create();
    $token = $user->createToken('TestToken')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson("/api/books/{$book->id}");

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'Book deleted successfully',
                 'id' => (string) $book->id 
             ]);

    $this->assertDatabaseMissing('books', ['id' => $book->id]);
}

}
