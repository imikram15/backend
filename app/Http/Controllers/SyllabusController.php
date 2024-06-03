<?php

namespace App\Http\Controllers;

use App\Models\Syllabus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SyllabusController extends Controller
{
    public function getsyllabusByClass($classid)
    {
        $validator = Validator::make([
            'class_id' => $classid,
        ], [
            'class_id' => 'required|integer|exists:classes,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $syllabuses = Syllabus::with('classes', 'subjects')
            ->where('class_id', $classid)
            ->paginate(10);
    
        if ($syllabuses->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Record Found.'
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'syllabus' => $syllabuses
        ], 200);
    }

        public function index()
    {
        $syllabuses = Syllabus::with('classes', 'subjects')->paginate(10);

        if ($syllabuses->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Record Found.'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'syllabuses' => $syllabuses
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'syllabus_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $fileName = Str::random(10);
        $fileExtension = $request->file('syllabus_file')->getClientOriginalExtension();
        $allowedExtensions = ['pdf', 'doc', 'docx'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid file type. Only PDF, DOC, and DOCX files are allowed.'
            ], 422);
        }

        $fileNameWithExtension = $fileName . '.' . $fileExtension;
        $filePath = $request->file('syllabus_file')->storeAs('public/syllabusFiles',$fileNameWithExtension);

        $syllabus = Syllabus::create([
            'title' => $request->input('title'),
            'class_id' => $request->input('class_id'),
            'subject_id' => $request->input('subject_id'),
            'syllabus_file' => $fileNameWithExtension,
        ]);

        if ($syllabus) {
            return response()->json([
                'status' => 200,
                'message' => 'Syllabus created successfully.'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to create Syllabus.'
            ], 500);
        }
    }
    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:syllabuses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid Syllabus ID'], 400);
        }

        $syllabus = Syllabus::with('classes', 'subjects')->find($id);

        if (!$syllabus) {
            return response()->json(['message' => 'Syllabus not found'], 404);
        }
        return response()->json([
            'status' => 200,
            'syllabus' => $syllabus,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'syllabus_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $syllabus = Syllabus::find($id);

        if (!$syllabus) {
            return response()->json([
                'status' => 404,
                'message' => 'Syllabus not found'
            ], 404);
        }

        $syllabus->title = $request->input('title');
        $syllabus->class_id = $request->input('class_id');
        $syllabus->subject_id = $request->input('subject_id');

        if ($request->hasFile('syllabus_file')) {
            $fileName = Str::random(10);
            $fileExtension = $request->file('syllabus_file')->getClientOriginalExtension();
            $allowedExtensions = ['pdf', 'doc', 'docx'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Invalid file type. Only PDF, DOC, and DOCX files are allowed.'
                ], 422);
            }

            $fileNameWithExtension = $fileName . '.' . $fileExtension;
        $filePath = $request->file('syllabus_file')->storeAs($fileNameWithExtension, 'public');

            $syllabus->syllabus_file = $filePath;
        }

        $syllabus->save();

        return response()->json([
            'status' => 200,
            'message' => 'Syllabus updated successfully.'
        ], 200);
    }

    public function destroy($id)
    {
        $syllabus = Syllabus::find($id);

        if (!$syllabus) {
            return response()->json([
                'status' => 404,
                'message' => 'Syllabus not found'
            ], 404);
        }

        $syllabus->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Syllabus deleted successfully'
        ], 200);
    }


}
