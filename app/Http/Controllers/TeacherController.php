<?php

namespace App\Http\Controllers;

use App\Models\teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = teachers::with(['department', 'category', 'designation'])->paginate(10);
        if ($teachers->count() > 0) {
            return response()->json([
                'status' => 200,
                'teachers' => $teachers
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Records Found."
            ], 404);
        }
    }

    public function countCheck()
    {
        $teachersCount = teachers::count();
        if ($teachersCount > 0) {
            return response()->json([
                'status' => 200,
                'teachers' => $teachersCount
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Records Found."
            ], 404);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'address' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'blood_group' => 'required|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }

        $fileName = Str::random(10) . '.' . $request->file('image')->extension();
        $request->file('image')->move(public_path('images/profile'), $fileName);

        $teacher = teachers::create(array_merge($request->all(), ['image' => $fileName, 'password' => Hash::make($request->input('password'))]));

        return $teacher
            ? response()->json(['status' => 200, 'message' => 'Teacher created successfully', 'teacher' => $teacher], 200)
            : response()->json(['status' => 500, 'message' => 'Failed to create teacher.'], 500);
    }


    public function show($id)
    {
        $teachers = teachers::find($id);

        if (!$teachers) {
            return response()->json(['message' => 'Teacher not found'], 404);
        } else {
            return response()->json($teachers);
        }
    }

    public function edit($id)
    {
        $teachers = teachers::find($id);
        return response()->json($teachers, 200);
    }

    public function update(Request $request, $id)
    {

        $updateData = $request->except('image');

        $validator = Validator::make($updateData, [
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'email' => 'required|email|unique:teachers,email,' . $id,
            'phone' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'address' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'category_id' => 'required|exists:categories,id',
            'blood_group' => 'required|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $teachers = teachers::find($id);

        if (!$teachers) {
            return response()->json([
                'status' => 404,
                'message' => 'Teacher not found'
            ], 404);
        } else {

            $teachers->update($updateData);

            return response()->json($teachers, 200);
        }
    }

    public function destroy($id)
    {
        $teachers = teachers::find($id);

        if (!$teachers) {
            return response()->json(['message' => 'Teacher not found'], 404);
        } else {
            $teachers->delete();
            return response()->json(['message' => 'Teacher deleted successfully']);
        }
    }

}
