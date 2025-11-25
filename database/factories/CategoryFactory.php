<?php

namespace Database\Factories;

    use App\Models\Category;
    use App\Models\User;    
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
		use RefreshDatabase; 
		protected $model = Category::class;

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
        Category::factory()->create(['name' => 'Fantasy']);
        Category::factory()->create(['name' => 'Valami']);

        $response = $this->getJson('/api/categories?needle=bar');

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
            'name' => 'Fantasy'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Fantasy']);
		
        $this->assertDatabaseHas('categories', ['name' => 'Fantasy']);
    }

    public function test_update_modifies_existing_category()
    {
        $category = Category::factory()->create(['name' => 'Fantasy']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/categories/{$category->id}", [
            'name' => 'Fantasy 2'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Fantasy 2']);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Fantasy 2']);
    } 

    public function test_update_returns_404_for_missing_category()
    {
        $response = $this->putJson('/api/categories/999', [
            'name' => 'Fantasy 999'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    } 

    public function test_delete_removes_category()
    {
        $category = Category::factory()->create(['name' => 'Fantasy']);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    } 
}
