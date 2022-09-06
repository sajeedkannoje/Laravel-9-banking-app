<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserCode;
use App\Repository\Eloquent\TwoFactorAuthenticationRepository;
use App\Repository\StarryInterfaces\MakeResponseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

use Illuminate\Support\Facades\Validator;


class TwoFactorAuthController extends BaseController
{
    private $makeResponse, $twoFactorAuthentication;
    public function __construct(MakeResponseRepositoryInterface  $makeResponseRepository, TwoFactorAuthenticationRepository $twoFactorAuthenticationRepository)
    {
        $this->makeResponse = $makeResponseRepository;
        $this->twoFactorAuthentication = $twoFactorAuthenticationRepository;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'   => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->makeResponse->sendError('Validation Error.', $validator->errors(), 422);
        }

        $find = UserCode::where('user_id', auth('sanctum')->user()->id)
            ->where('code', $request->code)
            // ->where('updated_at', '>=', now()->subMinutes(2))
            ->first();

        if (!is_null($find)) {
            Session::put('user_2fa', auth('sanctum')->user()->id);
            $find->delete();
            return $this->makeResponse->sendResponse('code verified successfully', auth('sanctum')->user(), 200);
        } else {
            return $this->makeResponse->sendError('error', 'You entered wrong code.', 401);
        }
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resend(Request $request)
    {
     
    }
}
