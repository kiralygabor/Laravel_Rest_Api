<?php

namespace Database\Factories;

    use App\Models\Author;
    use App\Models\User;    
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
		use RefreshDatabase; 
		protected $model = Author::class;

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
        Author::factory()->create(['name' => 'Emma Clarke']);
        Author::factory()->create(['name' => 'Kis Béla']);

        $response = $this->getJson('/api/authors?needle=bar');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Emma Clarke'])  
            ->assertJsonMissing(['name' => 'Kis Béla']);  
    }
    
    public function test_store_creates_new_author()
    {
		$user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/authors', [
            'name' => 'Kis Béla'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Kis Béla']);
		
        $this->assertDatabaseHas('authors', ['name' => 'Kis Béla']);
    }

    public function test_update_modifies_existing_author()
    {
        $author = Author::factory()->create(['name' => 'Emma Clarke']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/authors/{$author->id}", [
            'name' => 'Emma Clarkess'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Emma Clarkess']);

        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Emma Clarkess']);
    } 

    public function test_update_returns_404_for_missing_author()
    {
        $response = $this->putJson('/api/authors/999', [
            'name' => 'Emma Clarkessss'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    } 

    public function test_delete_removes_author()
    {
        $author = Author::factory()->create(['name' => 'Emma Clarke']);

        $response = $this->deleteJson("/api/authors/{$author->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    } 
}
