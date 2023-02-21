<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class SitesExport implements FromCollection, WithMapping
{
    protected $sites;

    public function __construct(Collection $sites)
    {
        $this->sites = $sites;
    }

    public function collection(): Collection
    {
        return $this->sites;
    }

    /**
     *
     * @param $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->type,
            $row->user->name,
            $row->user->email,
            $row->created_at,
            $row->updated_at,
        ];
    }
}
