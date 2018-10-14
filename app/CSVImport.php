<?php
/**
 * Created by Solaiman Kmail <psokmail@gmail.com>
 */

namespace App;


class CSVImport
{
    public function import($file)
    {
        $rows = [];
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }
}