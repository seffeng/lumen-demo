<?php
namespace App\Modules\Log\Events;

class OperateLogCreateEvent
{
    /**
     *
     * @var mixed
     */
    private $model;

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
    public function __construct($model, array $data = [])
    {
        //
        $this->model = $model;
        $this->data = $data;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
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
