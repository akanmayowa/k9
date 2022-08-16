<?php
namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // foreach ($rows as $row)
        // {
        //     User::create([
        //         'id' => $row[0],
        //         'first_name' => $row[1]
        //     ]);
        // }
    }
}
