<?php

namespace App\Repository\StarryInterfaces;

use App\Models\TwoFactorAuthentication;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface TwoFactorAuthenticationRepositoryInterface
{
   public  function generateCode(User $user): JsonResponse;
}