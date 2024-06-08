<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class exportUserController extends Controller
{
    public function export() 
    {       
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    // public function export() 
    // {
    //     $export = new UsersExport();
    //     dd($export->collection());  
    //     return Excel::download($export, 'users.xlsx');
    // }
}
