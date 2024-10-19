<?php

namespace Tests\Feature\Http\Controllers\Api\V2;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;


class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_v2(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->create();

        Recipe::factory(5)->create();

        $response =  $this->getJson('/api/v2/recipes');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [],
                'links' => [],
                'meta' => []
            ]);
    }
}
