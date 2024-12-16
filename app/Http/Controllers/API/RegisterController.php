<?php
     
namespace App\Http\Controllers\API;
     
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
     
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);       
        }
     
       try{
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['userData'] =  $user;
   
        return $this->sendResponse($success, 'User register successfully.', 201);
       }catch(Exception $ex){
        return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
       }
    }
     
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        try{
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                $success['userData'] =  $user;
       
                return $this->sendResponse($success, 'User login successfully.', 200);
            } 
            else{ 
                return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
            } 
        }catch(Exception $ex){
            return $this->sendError('Internal Server Error.', $ex->getMessage(), 500);
        }
    }
}
