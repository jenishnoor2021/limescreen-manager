<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Request;

class CustomerImport implements ToCollection
{
    protected $users_id;
    protected $branches_id;

    public function __construct($users_id, $branches_id)
    {
        $this->users_id = $users_id;
        $this->branches_id = $branches_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Skip header row
            if ($index === 0) {
                continue;
            }

            // Skip row if first few fields are empty
            if (
                empty($row[0]) && empty($row[1]) &&
                empty($row[2]) && empty($row[3])
            ) {
                continue;
            }

            Customer::create([
                'child_name'       => $row[0] ?? null,
                'parent_name'      => $row[2] ?? null,
                'email'            => $row[4] ?? null,
                'mobile'           => $row[3] ?? null,
                'whatsapp_number'  => $row[1] ?? null,
                'address'          => $row[6] ?? null,
                'city'             => $row[5] ?? null,
                'users_id'         => $this->users_id,
                'branches_id'      => $this->branches_id,
            ]);
        }
    }
}
