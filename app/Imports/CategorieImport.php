<?php

namespace App\Imports;

use App\Models\Categorie;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategorieImport implements ToModel, WithHeadingRow, WithMapping
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Categorie([
            'code_cat' => $row['code_cat'],
            'nom_cat' => $row['nom_cat'],
        ]);
    }

    public function map($row): array
    {
        return [
            'code_cat' => $row['code_cat'],
            'nom_cat' => $row['nom_cat'],
        ];
    }
}
