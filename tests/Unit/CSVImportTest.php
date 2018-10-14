<?php

namespace Tests\Unit;

use App\CSVImport;
use Tests\TestCase;

class CSVImportTest extends TestCase
{
    public function test_it_imports()
    {
        $import = app(CSVImport::class);

        $results = $import->import(base_path('tests/Fixtures/test.csv'));

        $this->assertEquals(
            [
                ['todo1'],
                ['todo2'],
                ['todo3'],
            ],
            $results
        );
    }
}
