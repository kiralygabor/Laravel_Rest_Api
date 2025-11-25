<?php

namespace Database\Factories;

    use App\Models\Book;
    use App\Models\User;    
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
		use RefreshDatabase; 
		protected $model = Book::class;

		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition()
		{
			return [
				'name' => $this->faker->unique()->word(),
			];
		}

        public function test_index_filters_by_needle()
    {
        Book::factory()->create(['name' => 'The London Fog']);
        Book::factory()->create(['name' => 'Vuk']);

        $response = $this->getJson('/api/books?needle=bar');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'The London Fog'])  // The London Fog benne van a válaszban
            ->assertJsonMissing(['name' => 'Vuk']);  // Vuk nincs benne
    }
    
    public function test_store_creates_new_book()
    {
		$user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/books', [
            'name' => 'Tüskevár'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Tüskevár']);
		
        $this->assertDatabaseHas('books', ['name' => 'Tüskevár']);
    }

    public function test_update_modifies_existing_book()
    {
        $book = Book::factory()->create(['name' => 'The London Fog']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/books/{$book->id}", [
            'name' => 'The London Fog 2'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'The London Fog 2']);

        $this->assertDatabaseHas('books', ['id' => $book->id, 'name' => 'The London Fog 2']);
    } 

    public function test_update_returns_404_for_missing_book()
    {
        $response = $this->putJson('/api/books/999', [
            'name' => 'The London Fog 999'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    } 

    public function test_delete_removes_book()
    {
        $book = Book::factory()->create(['name' => 'The London Fog']);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    } 
}
