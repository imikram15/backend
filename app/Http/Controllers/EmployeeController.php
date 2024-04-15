<?php

namespace App\Http\Controllers;

use App\Models\employees;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {

        $employees = employees::paginate(10);
        // $employees = employees::all();
        if (count($employees) > 0) {
            return response()->json([
                'status' => 200,
                'employees' => $employees
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
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'address' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            $fileName = Str::random(10) . '.' . 'png';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $uploadPath = "images/profile";
                $file->move($uploadPath, $fileName);
            }
            $postObj = $request->all();
            $postObj['image'] = $fileName;
            $employee = employees::create($postObj);


            if ($employee) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Employee Created Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to create employee'
                ], 500);
            }
        }
    }

    public function show($id)
    {
        $employee = employees::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        } else {
            return response()->json($employee);
        }
    }

    public function edit($id)
    {
        $employee = employees::find($id);
        return response()->json($employee, 200);

    }

    public function update(Request $request, $id)
    {

        $updateData = $request->except('image');

        $validator = Validator::make($updateData, [
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'email' => 'required|email|unique:employees,email,' .$id,
            'phone' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'address' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'category_id' => 'required|exists:categories,id',
            //    'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',       
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $employee = employees::find($id);

        if (!$employee) {
            return response()->json([
                'status' => 404,
                'message' => 'Employee not found'
            ], 404);
        } else {

            $employee->update($updateData);

            return response()->json($employee, 200);
        }
    }

    public function destroy($id)
    {
        $employee = employees::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        } else {
            $employee->delete();
            return response()->json(['message' => 'Employee deleted successfully']);
        }
    }



}
