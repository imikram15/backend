<?php

namespace App\Http\Controllers;

use App\Models\addExamCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamCategoryController extends Controller
{
    public function index()
    {
        
        $examCategory = addExamCategories::paginate(10);
        if (count($examCategory) > 0) {
            return response()->json([
                'status' => 200,
                'examCategory' => $examCategory
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
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        $examCategory = addExamCategories::create($request->all());

        return $examCategory 
        ? response()->json(['status' => 200, 'message' => 'Exam Category created successfully', 'examCategory' => $examCategory], 200)
        : response()->json(['status' => 500, 'message' => 'Failed to create Exam Category.'], 500);
    }

    public function show($id)
    {
        $examCategory = addExamCategories::find($id);

        if (!$examCategory) {
            return response()->json(['message' => 'Exam Category not found'], 404);
        } else {
            return response()->json(
                [
                    'status' => 200,
                    'examCategory' => $examCategory
                ],
                200
            );
        }
    }

    public function edit($id)
    {
        $examCategory = addExamCategories::find($id);
    
        if ($examCategory) {
            return response()->json(['success' => true, 'examCategory' => $examCategory], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Exam category not found'], 404);
        }
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

        $examCategory = addExamCategories::find($id);
        if (!$examCategory) {
            return response()->json([
                'status' => 404,
                'message' => 'Exam Category not found'
            ], 404);
        } else {
            $examCategory->update($request->all());
            return response()->json($examCategory, 200);
        }

    }

    public function destroy($id)
    {
        $examCategory = addExamCategories::find($id);
        if (!$examCategory) {
            return response()->json([
                'status' => 404,
                'message' => 'Exam Category not found.'
            ], 404);
        }

        $examCategory->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Exam Category deleted successfully'
        ], 200);
    }
}
