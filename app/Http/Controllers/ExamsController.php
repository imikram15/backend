<?php

namespace App\Http\Controllers;

use App\Models\exams;
use App\Models\students;
use App\Models\teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamsController extends Controller
{
    public function getExamsByClass(Request $request)
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

        $exams = exams::where('class_id', $classId)
            ->with(['examCategory', 'class', 'subject', 'classroom'])->get();

        if ($exams->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'exams' => $exams
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No exams found for this class."
            ], 404);
        }
    }

    public function getExamByType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_type' => 'required|string|in:students,teachers',
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

        if ($memberType == 'students') {
            $student = Students::find($memberId);
            if (!$student) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Student not found'
                ], 404);
            }

            $classId = $student->class_id;
            $exams = Exams::where('class_id', $classId)
                ->with(['examCategory', 'class', 'subject', 'classroom'])
                ->get();
        } elseif ($memberType == 'teachers') {
            $teacher = Teachers::with('classes')->find($memberId);
            if (!$teacher) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Teacher not found'
                ], 404);
            }

            $classIds = $teacher->classes->pluck('id'); // Extract the IDs of the classes taught by the teacher
            $exams = Exams::whereIn('class_id', $classIds)
                ->with(['examCategory', 'class', 'subject', 'classroom'])
                ->get();
        } else {
            return response()->json([
                'status' => 422,
                'errors' => 'Invalid member type'
            ], 422);
        }

        if ($exams->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'exams' => $exams
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No exams found for this member."
            ], 404);
        }
    }

    public function index(Request $request)
    {
        $classId = $request->input('class_id');

        $query = exams::query();

        if ($classId && is_numeric($classId)) {
            $query->where('class_id', $classId);
        }

        $exams = $query->with(['examCategory', 'class', 'subject', 'classroom'])
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'message' => 'Data fetched',
            'status' => 'true',
            'exams' => $exams,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'examCategory_id' => 'required|exists:add_exam_categories,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|date_format:H:i:s',
            'total_marks' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $exam = exams::create($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Exam recorded successfully.',
            'exams' => $exam
        ], 200);
    }

    public function show($id)
    {
        $exam = exams::with(['examCategory', 'class', 'subject', 'classroom'])->find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        } else {
            return response()->json($exam, 200);
        }
    }

    public function edit($id)
    {
        $exam = exams::with(['examCategory', 'class', 'subject', 'classroom'])->find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        } else {
            return response()->json($exam, 200);
        }
    }

    public function update(Request $request, $id)
    {
        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'examCategory_id' => 'required|exists:add_exam_categories,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|date_format:H:i:s',
            'total_marks' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $exam = exams::find($id);
        if (!$exam) {
            return response()->json([
                'status' => 404,
                'message' => 'Exam not found'
            ], 404);
        } else {
            $exam->update($updateData);
            return response()->json([
                'status' => 200,
                'message' => 'Exam updated successfully.',
                'exams' => $exam
            ], 200);
        }
    }

    public function destroy($id)
    {
        $exam = exams::find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        } else {
            $exam->delete();
            return response()->json(['message' => 'Exam deleted successfully'], 200);
        }
    }
}
