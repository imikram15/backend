<?php

namespace App\Http\Controllers;

use App\Models\AttendanceStatus;
use Illuminate\Http\Request;

class AttendanceStatusController extends Controller
{
    public function index()
    {
        $status = AttendanceStatus::all();
        return response()->json([
            'message' => 'data fetched',
            'status' => 'true',
            'data' => $status
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:attendance_status',
        ]);
        AttendanceStatus::create($request->all());

        return redirect()->route('attendance_status.index')->with('success', 'Attendance status created successfully.');
    }
}
