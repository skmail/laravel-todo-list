<?php

namespace Tests\Feature;

use App\CSVImport;
use App\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;


class TodoImportTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_import()
    {

        $this->login();

        $mock = \Mockery::mock(CSVImport::class);

        app()->instance(CSVImport::class,$mock);

        $mock->shouldReceive('import')->once()->andReturn([
            ['title1'],
            ['title3'],
            ['title2'],
        ]);

        $this->json('POST', '/todo/import',[
            'file' => UploadedFile::fake()->create('document.pdf', 25)
        ]);

        $this->assertCount(3,Todo::all());
    }
}
