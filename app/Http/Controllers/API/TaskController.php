<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $user_id = Auth::id();
            $relation = ['getUsers','getCategory'];
            $query = Task::where('user_id',$user_id)->with($relation);
            if(isset($request->status)){
                $query->where('status',$request->status);
            }
            if(isset($request->category)){
                $query->where('category_id',$request->category);
            }
            if (isset($request->start_date) && isset($request->end_date)) {
                $query->whereBetween('due_date', [$request->start_date, $request->end_date]);
            }
            $perPage = $request->has('per_page') ? $request->per_page : 2;
            $task = $query->paginate($perPage);
            return $this->sendResponseWithPagination($task,
                'task retrieved successfully.',       
                $task                                 
            );
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
            'user_id'   => 'required|exists:users,id',
            'category_id' => 'exists:categories,id',
            'title'  => 'required',
            'description' => 'required',
            'status'    => 'required',
            'due_date'  => 'required'
        ]);
        if($validate->fails()){
             return $this->sendError('Validation Error.', $validate->errors(), 400);
        }

        try{
            Task::create($request->all());
            return $this->sendResponse([], 'Task Created successfully.',201);
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
            $relation = ['getUsers','getCategory'];
            $task = Task::with($relation)->find($id);
            return $this->sendResponse($task, 'Task details successfully.',200);
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
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(),[
            'user_id'   => 'required|exists:users,id',
            'category_id' => 'exists:categories,id',
            'title'  => 'required',
            'description' => 'required',
            'status'    => 'required',
            'due_date'  => 'required'
        ]);
        if($validate->fails()){
             return $this->sendError('Validation Error.', $validate->errors(), 400);
        }

        try{
            $task = Task::find($id);
            if(!empty($task)){
                $data = [
                    'user_id'   => $request->user_id,
                    'category_id'  => $request->category_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    'due_date'  => $request->due_date
                ];
                $task->update($data);
                return $this->sendResponse([], 'Task Updated successfully.',200);
            }else{
                return $this->sendResponse([], 'Task not  found.',200);   
            }
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $task = Task::find($id);
            $task->delete();
            return $this->sendResponse([], 'Task deleted successfully.',200);
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500); 
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'status' => 'required'
        ]);
        if($validate->fails()){
            return $this->sendError('Validation Error.', $validate->errors(), 400);
        }
        try{
            $task = Task::find($id);
            if(!empty($task)){
                $data = [
                    'status' => $request->status
                ];
                $task->update($data);
                return $this->sendResponse([], 'Task status has been changed successfully.',200);
            }else{
                return $this->sendResponse([], 'Task not  found.',200);  
            }
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
        }
    }

    public function taskSearch(Request $request)
    {
        try{
            $relation = ['getUsers', 'getCategory'];
            $query = Task::with($relation);
            if(isset($request->title)){
                $query->where('title','LIKE','%'.$request->title.'%');
            }
            if(isset($request->description)){
                $query->where('description','LIKE','%'.$request->description.'%');
            }
            $perPage = $request->has('per_page') ? $request->per_page : 2;
            $task = $query->paginate($perPage);
            return $this->sendResponseWithPagination($task,
                'task retrieved successfully.',       
                $task                                 
            );
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
        }
    }
}
