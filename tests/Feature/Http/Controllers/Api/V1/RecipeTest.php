<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;

use Laravel\Sanctum\Sanctum;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Tag;


class RecipeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->create();

        Recipe::factory(2)->create();

        $response =  $this->getJson('/api/v1/recipes');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'type',
                        'attributes' => ['title' , 'description'],
                    ]
                ]
            ]);
    }

    public function test_store(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $data = [
            'category_id'      => $category->id,
            'title'         => $this->faker->sentence,
            'description'   => $this->faker->paragraph,
            'ingredients'   => $this->faker->text,
            'instructions'  => $this->faker->text,
            'tags'          => $tag->id,
            'image'         => UploadedFile::fake()->image('recipe.png')
        ];

        $response =  $this->postJson('/api/v1/recipes/', $data);
        $response->assertStatus(Response::HTTP_CREATED);
    }


    public function test_show(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->create();

        $recipe = Recipe::factory()->create();

        $response =  $this->getJson('/api/v1/recipes/' . $recipe->id);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'attributes' => ['title', 'description'],
                ]
            ]);
    }

    public function test_update(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();
        $recipe = Recipe::factory()->create();

        $data = [
            'category_id'      => $category->id,
            'title'         => 'update title',
            'description'   => 'update description',
            'ingredients'   => $this->faker->text,
            'instructions'  => $this->faker->text,
        ];

        $response =  $this->putJson('/api/v1/recipes/' . $recipe->id, $data);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('recipes', [
            'title' => 'update title',
            'description' => 'update description'
        ]);
    }

    public function test_destroy(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->create();

        $recipe = Recipe::factory()->create();

        $response =  $this->deleteJson('/api/v1/recipes/' . $recipe->id);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
