<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTodoTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_contain_a_title()
    {
        $this->post('todo')->assertSessionHasErrors('title');
    }

    public function test_it_saved_to_db()
    {
        $this->withoutExceptionHandling();

        $this->post('todo',[
            'title' => 'Hello'
        ])->assertStatus(201);

        $this->assertCount(1,\App\Todo::all());

        $this->assertEquals('Hello',\App\Todo::first()->title);
    }

}
