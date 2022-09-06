<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCode;
use App\Repository\Eloquent\MakeResponseRepository;
use App\Repository\Eloquent\TwoFactorAuthenticationRepository;
use App\Repository\StarryInterfaces\MakeResponseRepositoryInterface;
use App\Repository\StarryInterfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class AuthorizationController extends Controller
{
    private $makeResponse, $twoFactorAuthentication;
    public function __construct(MakeResponseRepositoryInterface  $makeResponseRepository, TwoFactorAuthenticationRepository $twoFactorAuthenticationRepository )
    {
        $this->makeResponse = $makeResponseRepository;  
        $this->twoFactorAuthentication= $twoFactorAuthenticationRepository;
    }
       /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function signIn(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $this->twoFactorAuthentication->generateCode($user);
            $success['token'] =  $user->createToken('api token')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->makeResponse->sendResponse($success, 'User login successfully.',200);
        } 
        else{ 
            return $this->makeResponse->sendError('Unauthorized.', ['error'=>'Unauthorized'],401);
        }
    }

    public function signUp(Request $request)
    {
        DB::beginTransaction();
        $validator = Validator ::make($request->all(), [
            'password'   => ['required', 'min:3', 'max:70'],
            'name'  => ['required', 'string', 'min:2', 'max:255'],
            'email'      => ['required', 'min:2', 'max:255', 'email'],
            'token'      => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
           return  $this->makeResponse->sendError('Validation Error.', $validator->errors(),422);    
        }

        try {
            $user =  User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'password' => Hash::make($request['password']),
            ]);
            $token = $user->createToken('api token')->plainTextToken;
            $this->twoFactorAuthentication->generateCode($user);
            DB::commit();
            return $this->makeResponse->sendResponse('registered',$token ,201 );
        } catch (\Throwable $th) {
            Log::debug( "Sign in Error:". $th );
            DB::rollBack();
            return$this->makeResponse->sendError('Internal Error.', $th,500);    
        }  
    }


}
