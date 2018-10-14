<?php

namespace Tests\Feature;

use App\Mail\TodoAssigne;
use App\Todo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class AssignUserTodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_assign_requires_auth()
    {
        $todo = factory(Todo::class)->create();

        $user = factory(User::class,2)->create();

        $todo->assignees()->attach(2);

        $this->post('/todo/1/assign/1')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }


    public function test_a_user_can_be_assigned_to_todo()
    {

        $this->withoutExceptionHandling();

        $this->login();

        $todo = factory(Todo::class)->create();

        $user = factory(User::class,2)->create();

        $todo->assignees()->attach(2);

        $this->json('post','/todo/1/assign/1');

        $this->assertCount(2,$todo->assignees);
    }

    public function test_user_must_be_exists_in_db()
    {
        $this->login();

        $todo = factory(Todo::class)->create();

        $user = factory(User::class,2)->create();

        $todo->assignees()->attach(2);

        $this->json('post','/todo/1/assign/50')
            ->assertStatus(404);
    }

    public function test_todo_must_be_exists_in_db()
    {

        $this->login();

        $todo = factory(Todo::class)->create();

        $user = factory(User::class,2)->create();

        $todo->assignees()->attach(2);

        $this->json('post','/todo/22/assign/50')
            ->assertStatus(404);
    }



    public function test_assigned_user_recieve_an_email()
    {
        Mail::fake();

        $this->withoutExceptionHandling();

        $this->login();

        $todo = factory(Todo::class)->create();

        $user = factory(User::class,2)->create();

        $todo->assignees()->attach(2);

        $this->json('post','/todo/1/assign/2');

        Mail::assertSent(TodoAssigne::class, function ($mail) use ($todo) {
            return $mail->todo->id === $todo->id;
        });

        $user = $user[0];

        Mail::assertSent(TodoAssigne::class, function ($mail) use ($todo,$user) {
            return $mail->hasTo($user->email);
        });

    }
}
