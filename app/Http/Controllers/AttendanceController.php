<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\students;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{

    public function getAttendanceById(Request $request)
    {
        $memberType = $request->input('member_type');
        $memberId = $request->input('member_id');
        $month = $request->input('month');
        $year = $request->input('year');

        if (!is_numeric($month) || !is_numeric($year) || $month < 1 || $month > 12 || $year < 2000 || $year > Carbon::now()->year) {
            return response()->json([
                'message' => 'Invalid month or year',
                'status' => 'false'
            ], 400);
        }

        if (empty($memberType) || empty($memberId)) {
            return response()->json([
                'message' => 'Member type and member ID are required',
                'status' => 'false'
            ], 400);
        }

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $datesArray = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $datesArray[] = $date->format('d');
        }

        $query = Students::query();

        if ($memberType == 'students') {
            $query->where('id', $memberId);
        } else {
            return response()->json([
                'message' => 'Invalid member type',
                'status' => 'false'
            ], 400);
        }

        $students = $query->with([
            'attendances' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            },
            'attendances.attendance_status'
        ])
            ->get();

        return response()->json([
            'message' => 'Data fetched',
            'status' => 'true',
            'data' => $students,
            'dates' => $datesArray,
        ], 200);
    }

    public function getWeeklyAttendance()
    {
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        $counts = [];
        $debug_dates = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $dayOfWeek = $date->format('l');
            $days[] = $dayOfWeek;

            $count = students::whereHas('attendances', function ($query) use ($date) {
                $query->whereDate('date', $date->toDateString());
            })->count();
            $counts[] = $count;
        }

        return response()->json([
            'status' => 200,
            'days' => $days,
            'counts' => $counts,
            'debug_dates' => $debug_dates
        ], 200);
    }



    public function index(Request $request)
    {
        $classId = $request->input('class_id', 1);
        if (!is_numeric($classId) || $classId <= 0) {
            return response()->json([
                'message' => 'Invalid class ID',
                'status' => 'false'
            ], 400);
        }

        $month = $request->input('month');
        $year = $request->input('year');
        if (!is_numeric($month) || !is_numeric($year) || $month < 1 || $month > 12 || $year < 2000 || $year > Carbon::now()->year) {
            return response()->json([
                'message' => 'Invalid month or year',
                'status' => 'false'
            ], 400);
        }

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $datesArray = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $datesArray[] = $date->format('d');
        }
        $students = students::where('class_id', $classId)
            ->with([
                'attendances' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                },
                'attendances.attendance_status'
            ])
            ->get();
        return response()->json([
            'message' => 'Data fetched',
            'status' => 'true',
            'data' => $students,
            'dates' => $datesArray,
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $errors = [];

        foreach ($data as $index => $item) {
            $validator = Validator::make($item, [
                'status_id' => 'required|exists:attendance_status,id',
                'class_id' => 'required|exists:classes,id',
                'student_id' => 'required|exists:students,id',
                'date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors[$index] = $validator->messages();
            }
        }
        if (!empty($errors)) {
            return response()->json([
                'status' => 422,
                'errors' => $errors
            ], 422);
        } else {
            foreach ($data as $item) {
                Attendance::create($item);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Attendance recorded successfully.'
            ], 200);
        }
    }


    public function show($id)
    {
        $attendance = Attendance::with(['attendance_status', 'classes', 'students'])->find($id);

        if (!$attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        } else {
            return response()->json($attendance);
        }
    }

    public function edit($id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        } else {
            return response()->json($attendance);
        }
    }

    public function update(Request $request, $id)
    {
        $updateData = $request->except('image');

        $validator = Validator::make($updateData, [
            'status_id' => 'required|exists:attendance_status,id',
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            $attendance = Attendance::find($id);
            if (!$attendance) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Attendance not found'
                ], 404);
            } else {
                $attendance->update($updateData);
                return response()->json([
                    'status' => 200,
                    'message' => 'Attendance updated successfully.'
                ], 200);
            }
        }


    }

    public function destroy($id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        } else {
            $attendance->delete();
            return response()->json(['message' => 'Attendance deleted successfully'], 200);
        }
    }
}




// public function getWeeklyAttendance()
//     {
//         $now = Carbon::now();
//         $startOfWeek = $now->startOfWeek(Carbon::MONDAY)->toDateString();
//         $endOfWeek = $now->endOfWeek(Carbon::SUNDAY)->toDateString();
//         // return ['s' => $startOfWeek, 'e' => $endOfWeek];
//         $days = [];
//         $counts = [];

//         for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
//             $dayOfWeek = $date->format('l');
//             $days[] = $dayOfWeek;
//         }

//         $counts = students::whereHas('attendances', function ($query) use ($startOfWeek, $endOfWeek) {
//             $query->whereDate('date', [$startOfWeek, $endOfWeek]);
//         })->count();



//         return response()->json([
//             'status' => 200,
//             'days' => $days,
//             'counts' => $counts,
//         ], 200);
//     }