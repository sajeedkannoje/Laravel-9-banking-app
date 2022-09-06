<?php

namespace App\Repository\StarryInterfaces;

use App\Models\MakeResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface MakeResponseRepositoryInterface
{
   public function sendResponse( $result, $message, $code = 200 ):JsonResponse;
   public function sendError( $error, $errorMessages = [], $code = 404 ):JsonResponse;
}