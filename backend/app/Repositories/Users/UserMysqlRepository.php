<?php

namespace App\Repositories\Users;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserMysqlRepository implements UserRepositoryInterface
{
    
    /**
     * model
     *
     * @var User
     */
    protected $model;
    
    /**
     * __construct
     *
     * @param  User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }
        
    /**
     * getAuthUser
     *
     * @return Model
     */
    public function getAuthUser(): Model
    {
        try {
            $user = $this->model->findOrFail(Auth::id());

            return $user;
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }
}