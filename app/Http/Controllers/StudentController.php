<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\students;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    
    public function index(){
        $students = students::all();
        if(count($students) > 0){
            return response()->json([
                'status' =>200,
                'students'=> $students
            ], 200);
        }else{
            return response()->json([
                'status' =>404,
                'message'=> "No Record Found."
            ], 404);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'roll_no' => 'required|integer|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'class' => 'required|string|max:255',
            'dob' => 'required|date',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            $student = students::create($request->all());
    
            if ($student) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Student created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to create Student'
                ], 500);
            }
         }
    }

    public function show($id){
        $student = students::find($id);
    
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        } else {        
        return response()->json($student);
        }
       }

    public function edit($id){
        $student = students::find($id);     
        return response()->json($student);            
        }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
        'roll_no' => 'required|integer|max:20',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'gender' => 'required|in:male,female,other',
        'class' => 'required|string|max:255',
        'dob' => 'required|date',
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
            
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
    
        $students = students::find($id);
    
        if (!$students) {
            return response()->json([
                'status' => 404,
                'message' => 'Student not found'
            ], 404);
        }
    
        $students->update($request->all());
    
        return response()->json($students, 200);
    }
    public function destroy($id)
{
    $students = students::find($id);

    if (!$students) {
        return response()->json(['message' => 'Student not found'], 404);
    }else{
        $students->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }   
}

}
