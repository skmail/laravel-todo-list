<?php

namespace Tests\Feature;

use App\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTodoTest extends TestCase
{
    use RefreshDatabase;


    public function test_deletion_requires_auth()
    {
        $this->delete('todo/1')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->assertCount(0,\App\Todo::all());
    }


    public function test_todo_must_be_exists_in_database()
    {
        $this->login();

        $this->delete('/todo/1')->assertStatus(404);
    }

    public function test_todo_can_be_deleted()
    {
        $this->login();

        $todo = factory(Todo::class)->create();

        $this->delete('/todo/' . $todo->id)->assertStatus(200);

        $this->assertCount(0, Todo::all());
    }


    public function test_when_delete_a_parent_todo_subs_should_be_delete()
    {
        $this->login();

        $todo = factory(Todo::class)->create();

        $sub = factory(Todo::class, 5)->create(['parent_id' => $todo->id]);

        $this->delete('/todo/' . $todo->id)->assertStatus(200);

        $this->assertCount(0, Todo::all());
    }

    public function test_when_delete_a_parent_todo_should_delete_all_subs()
    {
        $this->login();

        $todo = factory(Todo::class)->create();

        $sub = factory(Todo::class, 5)->create(['parent_id' => $todo->id]);

        $sub2 = factory(Todo::class, 5)->create(['parent_id' => $sub[0]->id]);

        $this->delete('/todo/' . $todo->id)->assertStatus(200);

        $this->assertCount(0, Todo::all());
    }

}
