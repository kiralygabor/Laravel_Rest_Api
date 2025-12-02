<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'name' => 'The London Fog',
            'author_id' => Author::factory(),
            'category_id' => Category::factory(),
            'price' => 55.74,
            'publication_date' => '1986-12-25',
            'isbn' => $this->faker->unique()->isbn13(),
            'cover' => 'covers/quia.jpg',
            'edition' => '3',
        ];
    }
}
