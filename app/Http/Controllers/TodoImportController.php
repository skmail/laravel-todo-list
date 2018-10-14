<?php

namespace App\Http\Controllers;

use App\CSVImport;
use App\Todo;
use Illuminate\Http\Request;

class TodoImportController extends Controller
{

    public function import(CSVImport $csv, Request $request)
    {
        $data =  $csv->import($request->file('file'));

        foreach($data as $item){
            Todo::create(['title' => $item[0]]);
        }

    }

}
