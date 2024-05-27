<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{public function index()
    {
        $classrooms = Classroom::paginate(10);

        if (count($classrooms) > 0) {
            return response()->json([
                'status' => 200,
                'classrooms' => $classrooms
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Record Found.'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:classrooms',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        try {
            $classroom = Classroom::create($request->all());
            return response()->json([
                'status' => 201,
                'classroom' => $classroom
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to create classroom',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $classes = Classroom::find($id);
        return response()->json($classes, 200);

    }

    public function show($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            return response()->json([
                'status' => 404,
                'message' => 'Classroom not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 200,
                'classroom' => $classroom
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:classrooms,title,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $classroom = Classroom::find($id);

        if (!$classroom) {
            return response()->json([
                'status' => 404,
                'message' => 'Classroom not found'
            ], 404);
        } else {
            try {
                $classroom->update($request->all());
                return response()->json([
                    'status' => 200,
                    'classroom' => $classroom
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to update classroom',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function destroy($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            return response()->json([
                'status' => 404,
                'message' => 'Classroom not found'
            ], 404);
        }

        try {
            $classroom->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Classroom deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to delete classroom',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
