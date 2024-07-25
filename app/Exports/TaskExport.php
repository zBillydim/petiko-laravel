<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class TaskExport implements FromCollection, Responsable
{
    use Exportable;
     /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'users.xlsx';
        /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    protected $user_id;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Task::where('user_id', $this->user_id)->get();
    }
}
