<?php

namespace App\Repository\Eloquent;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\StarryInterfaces\MakeResponseRepositoryInterface;
use Illuminate\Http\JsonResponse;

class MakeResponseRepository implements MakeResponseRepositoryInterface
{      /**
    * success response method.
    *
    * @return \Illuminate\Http\Response
    */
   public function sendResponse($result, $message, $code = 200 ): JsonResponse
   {
       $response = [
           'success' => true,
           'data'    => $result,
           'message' => $message,
       ];
       return response()->json($response, $code);
   }
   /**
    * return error response.
    *
    * @return \Illuminate\Http\Response
    */
   public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
   {
       $response = [
           'success' => false,
           'message' => $error,
       ];
       if(!empty($errorMessages)){
           $response['data'] = $errorMessages;
       }
       return response()->json($response, $code);
   }

}
