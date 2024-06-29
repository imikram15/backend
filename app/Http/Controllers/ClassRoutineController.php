<?php

namespace App\Http\Controllers;

use App\Models\classRoutine;
use App\Models\students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassRoutineController extends Controller
{

    public function getRoutineByType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_type' => 'required|string|in:teachers,students', // Add more types if needed
            'member_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $memberType = $request->input('member_type');
        $memberId = $request->input('member_id');

        $query = classRoutine::query();

        if ($memberType == 'teachers') {
            $query->where('teacher_id', $memberId);
        } elseif ($memberType == 'students') {
            $student = students::find($memberId);
            if ($student) {
                $query->where('class_id', $student->class_id);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Student not found'
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 422,
                'errors' => 'Invalid member type'
            ], 422);
        }

        $routines = $query->with(['teachers', 'subjects', 'classes', 'classrooms'])->get();

        if ($routines->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'routines' => $routines
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No routines found for this member."
            ], 404);
        }
    }



    public function getRoutinesByClass(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|integer|exists:classes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $classId = $request->input('class_id');

        $routines = ClassRoutine::where('class_id', $classId)
            ->with(['classes', 'classrooms', 'teachers', 'subjects'])->get();
        if ($routines->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'routines' => $routines
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No routines found for this class."
            ], 404);
        }
    }

    public function index(Request $request)
    {
        $classId = $request->input('class_id');

        $query = classRoutine::query();

        if ($classId && is_numeric($classId)) {
            $query->where('class_id', $classId);
        }

        $routines = $query->with(['teachers', 'subjects', 'classes', 'classrooms'])->get();

        return response()->json([
            'message' => 'Data fetched',
            'status' => 'true',
            'data' => $routines,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'day' => 'required|string',
            'startinghours' => 'required|integer|min:0|max:23',
            'startingminute' => 'required|integer|min:0|max:59',
            'endinghours' => 'required|integer|min:0|max:23',
            'endingminute' => 'required|integer|min:0|max:59',
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $routine = classRoutine::create($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Routine recorded successfully.',
            'data' => $routine
        ], 200);
    }

    public function show($id)
    {
        $routine = classRoutine::with(['teachers', 'subjects', 'classes', 'classrooms'])->find($id);

        if (!$routine) {
            return response()->json(['message' => 'Routine not found'], 404);
        } else {
            return response()->json($routine, 200);
        }
    }

    public function update(Request $request, $id)
    {
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'class_id' => 'required|exists:classes,id',
            'day' => 'required|string',
            'startinghours' => 'required|integer|min:0|max:23',
            'startingminute' => 'required|integer|min:0|max:59',
            'endinghours' => 'required|integer|min:0|max:23',
            'endingminute' => 'required|integer|min:0|max:59',
            'classroom_id' => 'required|string',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $routine = classRoutine::find($id);
        if (!$routine) {
            return response()->json([
                'status' => 404,
                'message' => 'Routine not found'
            ], 404);
        } else {
            $routine->update($updateData);
            return response()->json([
                'status' => 200,
                'message' => 'Routine updated successfully.',
                'data' => $routine
            ], 200);
        }
    }

    public function destroy($id)
    {
        $routine = classRoutine::find($id);

        if (!$routine) {
            return response()->json(['message' => 'Routine not found'], 404);
        } else {
            $routine->delete();
            return response()->json(['message' => 'Routine deleted successfully'], 200);
        }
    }
}
