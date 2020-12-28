<?php

namespace App\Common\Illuminate;

use Illuminate\Support\Arr;

class BaseIlluminate
{
        /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @author zxf
     * @date    2020年12月25日
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     *
     * @author zxf
     * @date    2020年12月25日
     * @return number
     */
    public function getValue()
    {
        return $this->id;
    }

    /**
     *
     * @author zxf
     * @date    2020年12月25日
     * @return string
     */
    public function getName()
    {
        return Arr::get(static::fetchNameItems(), $this->getValue(), '');
    }

    /**
     *
     * @author zxf
     * @date    2020年12月25日
     * @return array
     */
    public static function fetchItems()
    {
        return array_keys(static::fetchNameItems());
    }

    /**
     *
     * @author zxf
     * @date    2020年12月25日
     * @return string[]
     */
    public static function fetchNameItems()
    {
        return [
            //
        ];
    }
}
