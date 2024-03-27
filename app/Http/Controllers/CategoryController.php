<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        if(count($category) > 0){
            return response()->json([
                'status' =>200,
                'category'=> $category
            ], 200);
        }else{
            return response()->json([
                'status' =>404,
                'message'=> "No Record Found."
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:categories',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        } else {        
            return response()->json([
                   'status'=> 200,
                    'category'=>$category], 200
                );
        }
    }

    public function edit($id){
        $category = Category::find($id);      
        return response()->json($category ,200);
       }
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {          
     $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255|unique:categories,title,' . $id,
      ]);
  
      if ($validator->fails()) {
          return response()->json([
              'status' => 422,
              'errors' => $validator->messages()
          ], 422);
      }
  
          $category = Category::find($id);      
      if (!$category) {
          return response()->json([
              'status' => 404,
              'message' => 'Cateogry not found'
          ], 404);
      }
  
      $category->update($request->all());
          return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category ) {
            return response()->json([
                'status' => 404,
                'message' => 'Category not found'
            ], 404);
        }
    
        $category ->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
