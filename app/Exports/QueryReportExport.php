<?php

namespace App\Exports;

use App\Models\SomeModel;
use Maatwebsite\Excel\Concerns\FromCollection;

class QueryReportExport implements FromCollection
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        // Retorna os nomes das colunas como cabeçalho
        return array_keys((array) $this->data[0] ?? []);
    }
}
