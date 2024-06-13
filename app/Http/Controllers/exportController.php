<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Exports\StudentFeeExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class exportController extends Controller
{

    public function StudentFeeExport(Request $request, $startDate, $endDate, $class_id, $status)
    {
        // return $class_id;
        return Excel::download(new StudentFeeExport($startDate, $endDate, $class_id, $status), 'student_fees.xlsx');
    }

    public function AttendanceExport(Request $request, $class_id, $month, $year, )
    {
        return Excel::download(new AttendanceExport($class_id, $month, $year), 'Attendance_Report_' . $month . '_' . $year . '.xlsx');
    }

}

// http://127.0.0.1:8000/api/studentFee/exxport/2024-06-07/2024-06-07