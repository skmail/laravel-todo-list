<?php

namespace Tests\Feature;

use App\Todo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTodoTest extends TestCase
{

    use RefreshDatabase;


    public function test_creation_requires_auth()
    {
        $this->post('todo')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->assertCount(0,\App\Todo::all());
    }

    public function test_it_contain_a_title()
    {
        $this->login();

        $this->post('todo')->assertSessionHasErrors('title');
    }

    public function test_it_saved_to_db()
    {
        $this->login();

        $this->post('todo',[
            'title' => 'Hello'
        ])->assertStatus(201);

        $this->assertCount(1,\App\Todo::all());

        $this->assertEquals('Hello',\App\Todo::first()->title);
    }


    public function test_an_item_can_be_added_under_main_item()
    {
        $this->login();

        $parentTodo = factory(Todo::class)->create();

        $this->post('todo',[
            'title' => 'Hello',
            'parent_id' => $parentTodo->id
        ])->assertStatus(201);

        $this->assertCount(2,\App\Todo::all());

        $this->assertEquals($parentTodo->id,Todo::find(2)->parent_id);

        $this->assertEquals($parentTodo->id,Todo::find(2)->parent->id);

        $this->assertCount(1,$parentTodo->items);
    }


    public function test_parent_id_must_be_valid()
    {
        $this->login();

        $this->post('todo',[
            'title' => 'Hello',
            'parent_id' => 3
        ])->assertSessionHasErrors('parent_id');
    }

    public function test_parent_id_can_be_nullable()
    {
        $this->login();

        $this->post('todo',[
            'title' => 'Hello',
            'parent_id' => ''
        ])->assertStatus(201);
    }

}
