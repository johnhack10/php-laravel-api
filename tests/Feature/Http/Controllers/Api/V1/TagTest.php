<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;

use App\Models\Tag;
use App\Models\User;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Tag::factory(2)->create();

        $response =  $this->getJson('/api/v1/tags');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'type',
                        'attributes' => ['name'],
                        'relationships' => [
                            'recipes' => []
                        ],
                    ]
                ]
            ]);
    }

    public function test_show(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $tag = Tag::factory()->create();

        $response =  $this->getJson('/api/v1/tags/' . $tag->id);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'attributes' => ['name'],
                    'relationships' => [
                            'recipes' => []
                    ],
                ]
            ]);
    }
}
