<?php

namespace App\Http\Controllers;

use App\Mail\TodoAssigne;
use App\Todo;
use App\User;

class TodoAssignmentController extends Controller
{
    /**
     * @param Todo $todo
     * @param User $user
     */
    public function store(Todo $todo,User $user)
    {
        $todo->assignees()->attach($user->id);

        \Mail::to($user->email)->send(new TodoAssigne($todo));


    }
}
