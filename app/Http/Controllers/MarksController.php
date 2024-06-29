<?php

namespace App\Http\Controllers;

use App\Models\addExamCategories;
use App\Models\classes;
use App\Models\marks;
use App\Models\students;
use App\Models\Subjects;
use App\Models\teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MarksController extends Controller
{

    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|integer|exists:classes,id',
            'examCategory_id' => 'required|integer|exists:add_exam_categories,id',
            'subject_id' => 'required|integer|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $classId = $request->input('class_id');
        $examCategoryId = $request->input('examCategory_id');
        $subjectId = $request->input('subject_id');
        $perPage = $request->input('per_page', 10);

        $students = students::where('class_id', $classId)->get();

        $marks = marks::where('examCategory_id', $examCategoryId)
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->with(['examCategory', 'class', 'subject', 'student'])
            ->get();

        $class = classes::find($classId);
        $subject = Subjects::find($subjectId);
        $examCategory = addExamCategories::find($examCategoryId);


        $result = $students->map(function ($student) use ($marks, $class, $subject, $examCategory) {
            $mark = $marks->firstWhere('student_id', $student->id);
            return [
                'student' => $student,
                'mark' => $mark ? $mark->mark : null,
                'comment' => $mark ? $mark->comment : null,
                'mark_id' => $mark ? $mark->id : null,
                'class' => $class,
                'subject' => $subject,
                'examCategory' => $examCategory,
            ];
        });

        return response()->json([
            'status' => 200,
            'marks' => $result,
            //->paginate($perPage)
        ], 200);
    }

    public function getMarksByType(Request $request)
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
            $marks = Marks::where('student_id', $memberId)
                ->with(['examCategory', 'class', 'subject', 'student'])
                ->get();
        } else {
            return response()->json([
                'status' => 422,
                'errors' => 'Invalid member type'
            ], 422);
        }

        if ($marks->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'marks' => $marks
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No marks found for this member."
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'examCategory_id' => 'required|exists:add_exam_categories,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,id',
            'mark' => 'required|integer',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        $existingMark = marks::where('examCategory_id', $request->examCategory_id)
            ->where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->where('student_id', $request->student_id)
            ->first();

        if ($existingMark) {
            $existingMark->update($request->all());
            return response()->json([
                'status' => 200,
                'message' => 'Mark updated successfully.',
                'marks' => $existingMark
            ], 200);
        } else {
            $mark = marks::create($request->all());
            return response()->json([
                'status' => 200,
                'message' => 'Mark recorded successfully.',
                'marks' => $mark
            ], 200);
        }
    }

    public function show($id)
    {
        $mark = Marks::with(['examCategory', 'class', 'subject', 'student'])->find($id);

        if (!$mark) {
            return response()->json(['message' => 'Mark not found'], 404);
        } else {
            return response()->json($mark, 200);
        }
    }

    // public function edit($id)
    // {
    //     $mark = Marks::with(['examCategory', 'class', 'subject', 'student'])->find($id);

    //     if (!$mark) {
    //         return response()->json(['message' => 'Mark not found'], 404);
    //     } else {
    //         return response()->json($mark, 200);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     $updateData = $request->all();
    //     $validator = Validator::make($updateData, [
    //         'examCategory_id' => 'required|exists:add_exam_categories,id',
    //         'class_id' => 'required|exists:classes,id',
    //         'subject_id' => 'required|exists:subjects,id',
    //         'student_id' => 'required|exists:students,id',
    //         'mark' => 'required|integer',
    //         'comment' => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 422,
    //             'errors' => $validator->messages()
    //         ], 422);
    //     }

    //     $mark = Marks::find($id);
    //     if (!$mark) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Mark not found'
    //         ], 404);
    //     } else {
    //         $mark->update($updateData);
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Mark updated successfully.',
    //             'marks' => $mark
    //         ], 200);
    //     }
    // }

    public function destroy($id)
    {
        $mark = Marks::find($id);

        if (!$mark) {
            return response()->json(['message' => 'Mark not found'], 404);
        } else {
            $mark->delete();
            return response()->json(['message' => 'Mark deleted successfully'], 200);
        }
    }

}