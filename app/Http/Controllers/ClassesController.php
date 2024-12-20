<?php

namespace App\Http\Controllers;

use App\Models\classes;
use App\Models\classRoutine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{

    public function getClassesByType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_type' => 'required|string|in:teachers,students',
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
        } else {
            return response()->json([
                'status' => 422,
                'errors' => 'Invalid member type'
            ], 422);
        }

        $routines = $query->with('classes')->get();

        $classes = $routines->pluck('classes')->unique('id')->values();

        if ($classes->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'classes' => $classes
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No classes found for this member."
            ], 404);
        }
    }

    public function index()
    {
        $classes = classes::paginate(10);
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
        //transaction , foriegn key
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:classes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $classes = classes::create($request->all());
        // $id = DB::table('classes')->orderBy('created_at', 'desc')->value('id');
        // DB::table('classes_and_sections')->insert(
        //     ['class_id' => $id, 'section_id' => 1]
        // );


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
