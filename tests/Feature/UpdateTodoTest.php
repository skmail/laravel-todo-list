<?php

namespace Tests\Feature;

use App\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTodoTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_requires_a_title()
    {

        $todo = factory(Todo::class)->create();

        $this->put('todo/1')->assertSessionHasErrors('title');
    }

    public function test_title_should_be_string()
    {
        $todo = factory(Todo::class)->create();

        $this->put('todo/1', [
            'title' => [
                'string'
            ]
        ])->assertSessionHasErrors('title');
    }


    public function test_it_updates()
    {
        $this->withoutExceptionHandling();

        $todo = factory(Todo::class)->create();

        $this->put('todo/1', [
            'title' => 'Hello'
        ])->assertStatus(200);

        $this->assertEquals('Hello', $todo->fresh()->title);
    }
}
