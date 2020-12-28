<?php
/**
 * 时代财经
 *
 * 本源代码由时代财经及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 *
 * @copyright Copyright (c) 2020. tfcaijing.com all rights reserved.
 */
namespace App\Modules\Admin\Events;

use App\Modules\Admin\Models\Admin;

class LogoutEvent
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
     * @date   2020年12月10日
     * @return Admin
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
