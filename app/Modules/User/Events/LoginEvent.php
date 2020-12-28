<?php

namespace App\Modules\User\Events;

use App\Modules\User\Models\User;

class LoginEvent
{
    /**
     *
     * @var User
     */
    private $user;

    /**
     *
     * @var array
     */
    private $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, array $data = [])
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     *
     * @author zxf
     * @date    2019年10月21日
     * @return \App\Modules\User\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月28日
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
