<?php

namespace Tests\Feature;

use App\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTodoTest extends TestCase
{

    use RefreshDatabase;

    public function test_update_requires_auth()
    {
        $this->put('todo/1')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->assertCount(0,\App\Todo::all());
    }


    public function test_todo_must_be_exists_in_database()
    {
        $this->login();

        $this->put('/todo/2', [
            'title' => 's'
        ])->assertStatus(404);
    }

    public function test_it_requires_a_title()
    {
        $this->login();

        $todo = $this->createTodo();

        $this->put('todo/' . $todo->id)->assertSessionHasErrors('title');
    }


    public function test_status_must_be_valid()
    {
        $this->login();

        $todo = $this->createTodo();

        $this->put('todo/' . $todo->id, [
            'status' => 'active'
        ])->assertSessionHasErrors('status');
    }

    public function test_title_should_be_string()
    {
        $this->login();

        $todo = $this->createTodo();

        $this->put('todo/' . $todo->id, [
            'title' => [
                'string'
            ]
        ])->assertSessionHasErrors('title');
    }


    public function test_it_updates()
    {
        $this->login();

        $todo = $this->createTodo();

        $this->put('todo/' . $todo->id, [
            'title' => 'Hello'
        ])->assertStatus(200);

        $this->assertEquals('Hello', $todo->fresh()->title);
    }


    public function test_a_todo_can_toggle_its_status()
    {
        $this->login();

        $todo = $this->createTodo();

        $this->put('todo/' . $todo->id, [
            'title' => 'Hello',
            'status' => 'completed'
        ])->assertStatus(200);

        $this->assertEquals('completed', $todo->fresh()->status);
    }

    public function test_to_todo_can_be_moved_under_main_todo()
    {
        $this->login();

        $parentTodo = factory(Todo::class)->create();

        $todo = $this->createTodo();

        $this->put('todo/' . $todo->id,[
            'title' => 'Hello',
            'parent_id' => $parentTodo->id
        ])->assertStatus(200);

        $this->assertCount(2,\App\Todo::all());

        $this->assertEquals($parentTodo->id,$todo->fresh()->parent_id);

        $this->assertEquals($parentTodo->id,$todo->fresh()->parent->id);

        $this->assertCount(1,$parentTodo->items);
    }


    public function test_parent_id_must_be_valid()
    {
        $this->login();

        $todo = $this->createTodo();

        $this->put('todo/' . $todo->id,[
            'title' => 'Hello',
            'parent_id' => 3
        ])->assertSessionHasErrors('parent_id');
    }

    public function test_parent_id_can_be_nullable()
    {
        $this->login();

        $todo = $this->createTodo();

        $this->put('todo/' . $todo->id,[
            'title' => 'Hello',
            'parent_id' => ''
        ])->assertStatus(200);
    }


    private function createTodo(){
        return factory(Todo::class)->create();
    }



}
