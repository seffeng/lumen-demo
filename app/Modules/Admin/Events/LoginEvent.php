<?php

namespace App\Modules\Admin\Events;

use App\Modules\Admin\Models\Admin;

class LoginEvent
{
    /**
     *
     * @var Admin
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
    public function __construct(Admin $user, array $data = [])
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     *
     * @author zxf
     * @date    2019年10月21日
     * @return Admin
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月11日
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
