<?php

namespace App\Common\Illuminate;

use Illuminate\Support\Arr;

class TypeIlluminate
{
        /**
     *
     * @var integer
     */
    protected $typeId;

    /**
     *
     * @author zxf
     * @date    2019年10月10日
     * @param int $typeId
     */
    public function __construct(int $typeId)
    {
        $this->typeId = $typeId;
    }

    /**
     *
     * @author zxf
     * @date    2019年10月10日
     * @return number
     */
    public function getValue()
    {
        return $this->typeId;
    }

    /**
     *
     * @author zxf
     * @date    2019年12月6日
     * @return string
     */
    public function getName()
    {
        return Arr::get(static::fetchNameItems(), $this->getValue(), '');
    }

    /**
     *
     * @author zxf
     * @date    2019年9月25日
     * @return array
     */
    public static function fetchItems()
    {
        return array_keys(static::fetchNameItems());
    }

    /**
     *
     * @author zxf
     * @date    2019年9月25日
     * @return string[]
     */
    public static function fetchNameItems()
    {
        return [
            //
        ];
    }
}
