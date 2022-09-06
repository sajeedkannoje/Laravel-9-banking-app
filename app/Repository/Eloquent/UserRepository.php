<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\StarryInterfaces\MakeResponseRepositoryInterface;
use App\Repository\StarryInterfaces\UserRepositoryInterface;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    /**
     * Display a listing of the resource.
     *
     * @param  \User  $user
     * 
     */
    protected $makeResponse;
    public function __construct(User $user, MakeResponseRepositoryInterface  $makeResponseRepository)
    {
        $this->model = $user;
        $this->makeResponse = $makeResponseRepository;
    }




}
