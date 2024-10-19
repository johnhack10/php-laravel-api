<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;

use App\Models\Category;
use App\Models\User;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory(2)->create();

        $response =  $this->getJson('/api/v1/categories');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'type',
                        'attributes' => ['name'],
                    ]
                ]
            ]);
    }

    public function test_show(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $response =  $this->getJson('/api/v1/categories/' . $category->id);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'attributes' => ['name'],
                ]
            ]);
    }
}
