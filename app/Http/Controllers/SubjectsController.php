<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectsController extends Controller
{
    public function getSubjectsByClass(Request $request){

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
        $subjects = Subjects::where('class_id', $classId)->with('classes')->get();

        if ($subjects->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'subjects' => $subjects
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Subject found for this class."
            ], 404);
        }

    }
    public function index()
    {
        
        $subjects = Subjects::with('classes')->paginate(10);
        if (count($subjects) > 0) {
            return response()->json([
                'status' => 200,
                'subjects' => $subjects
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Record Found."
            ], 404);
        }
    }

    public function store(Request $request)
    {
        //transaction , foriegn key
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $subjects = Subjects::create($request->all());
        return response()->json($subjects, 201);
    }

    public function show($id)
    {
        $subjects = Subjects::find($id);

        if (!$subjects) {
            return response()->json(['message' => 'Class not found'], 404);
        } else {
            return response()->json(
                [
                    'status' => 200,
                    'subjects' => $subjects
                ],
                200
            );
        }
    }

    public function edit($id)
    {
        $subjects = Subjects::find($id);
        return response()->json($subjects, 200);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $subjects = Subjects::find($id);
        if (!$subjects) {
            return response()->json([
                'status' => 404,
                'message' => 'Class not found'
            ], 404);
        } else {
            $subjects->update($request->all());
            return response()->json($subjects, 200);
        }

    }

    public function destroy($id)
    {
        $subjects = subjects::find($id);
        if (!$subjects) {
            return response()->json([
                'status' => 404,
                'message' => 'Class not found'
            ], 404);
        }

        $subjects->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Class deleted successfully'
        ], 200);
    }
}
