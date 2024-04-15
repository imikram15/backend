<?php

namespace App\Http\Controllers;

use App\Models\classes;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function index()
    {
        
        // $classes = classes::paginate(10);
        $classes = classes::with('sections')->paginate(10);
        if (count($classes) > 0) {
            return response()->json([
                'status' => 200,
                'classes' => $classes
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:classes',
            // 'section' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $classes = classes::create($request->all());
        return response()->json($classes, 201);
    }

    public function show($id)
    {
        $classes = classes::find($id);

        if (!$classes) {
            return response()->json(['message' => 'Class not found'], 404);
        } else {
            return response()->json(
                [
                    'status' => 200,
                    'classes' => $classes
                ],
                200
            );
        }
    }

    public function edit($id)
    {
        $classes = classes::find($id);
        return response()->json($classes, 200);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:classes,title,' . $id,
            // 'section' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $classes = classes::find($id);
        if (!$classes) {
            return response()->json([
                'status' => 404,
                'message' => 'Class not found'
            ], 404);
        } else {
            $classes->update($request->all());
            return response()->json($classes, 200);
        }

    }

    public function destroy($id)
    {
        $classes = classes::find($id);
        if (!$classes) {
            return response()->json([
                'status' => 404,
                'message' => 'Class not found'
            ], 404);
        }

        $classes->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Class deleted successfully'
        ], 200);
    }

}