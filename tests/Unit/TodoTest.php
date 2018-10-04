<?php

namespace Tests\Unit;

use App\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_has_many_items()
    {
        $parent = factory(Todo::class)->create();

        $sub = factory(Todo::class,4)->create(['parent_id' => $parent->id]);

        $this->assertCount(4,$parent->items);
    }

    public function test_it_may_belongs_to_an_item()
    {
        $parent = factory(Todo::class)->create();

        $sub = factory(Todo::class,4)->create(['parent_id' => $parent->id]);


        foreach ($sub as $item){
            $this->assertEquals(1, $item->parent->id);
        }
    }
}
