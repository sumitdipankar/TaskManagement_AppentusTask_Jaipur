<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $category = Category::all();
            return $this->sendResponse(CategoryResource::collection($category), 'Category retrieved successfully.',200);
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name'  => 'required'
        ]);
        if($validate->fails()){
             return $this->sendError('Validation Error.', $validate->errors(), 400);
        }

        try{
            Category::create($request->all());
            return $this->sendResponse([], 'Category Created successfully.',201);
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $category = Category::find($id);
            return $this->sendResponse(new CategoryResource($category), 'Category retrieved successfully.',200);
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'name'  => 'required'
        ]);
        if($validate->fails()){
             return $this->sendError('Validation Error.', $validate->errors(), 400);
        }

        try{
            $category = Category::find($id);
            if(!empty($category)){
                $category->update($request->all());
            return $this->sendResponse([], 'Category Updated successfully.',200);
            }else{
                return $this->sendResponse([], 'Category not found.',200); 
            }
            
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $category = Category::find($id);
            $category->delete();
            return $this->sendResponse([], 'Category Deleted successfully.',200);
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
        }
    }
}
