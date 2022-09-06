<?php

namespace App\Repository\Eloquent;

use App\Models\TwoFactorAuthentication;
use App\Models\User;
use App\Models\UserCode;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\StarryInterfaces\MakeResponseRepositoryInterface;
use App\Repository\StarryInterfaces\TwoFactorAuthenticationRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class TwoFactorAuthenticationRepository  implements TwoFactorAuthenticationRepositoryInterface
{
    protected $makeResponse;
    public function __construct(MakeResponseRepositoryInterface  $makeResponseRepository)
    {
        $this->makeResponse = $makeResponseRepository;
    }
    public  function generateCode(User $user): JsonResponse
    {
       
        try {
            DB::beginTransaction();
            $code = rand(1000, 9999);

            UserCode::updateOrCreate(
                ['user_id' => $user->id],
                ['code' => $code]
            );
    
            $receiverNumber = $user->phone;
            $message = "2FA login code is " . $code;
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message
            ]);
            DB::commit();
            return $this->makeResponse->sendResponse('send', 'message sent', 200);
        } catch (Exception $e) {
            DB::rollBack();
            return  $this->makeResponse->sendError('internal server error', ['code' => 'something went wrong!'], 422);
            info("Error: " . $e->getMessage());
        }
    }
    public function FunctionName(User $user)
    {
        try {
            //code...
            $this->generateCode($user);
            return $this->makeResponse->sendResponse('success', 'We sent you code on your mobile number.', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->makeResponse->sendResponse('failed', 'something went worng.', 401);
        }
    }
}
