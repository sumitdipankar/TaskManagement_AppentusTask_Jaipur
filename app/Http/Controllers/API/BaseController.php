<?php
 
namespace App\Http\Controllers\API;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
 
class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $statusCode = 200)
    {
    	$response = [
            'success' => true,
            'status'  => $statusCode,
            'data'    => $result,
            'message' => $message,
        ];
 
        return response()->json($response, $statusCode);
    }
 
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'status'  => $code,
            'message' => $error,
        ];
 
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
 
        return response()->json($response, $code);
    }

    public function sendResponseWithPagination($result, $message, $paginatedData = null)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
    
        if ($paginatedData) {
            $response['pagination'] = [
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage(),
                'per_page' => $paginatedData->perPage(),
                'total' => $paginatedData->total(),
                'next_page_url' => $paginatedData->nextPageUrl(),
                'prev_page_url' => $paginatedData->previousPageUrl(),
            ];
        }
    
        return response()->json($response, 200);
    }
}
