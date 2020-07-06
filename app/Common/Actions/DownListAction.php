<?php

namespace App\Common\Actions;

use Illuminate\Http\Request;

class DownListAction
{
    const TYPE_TEST = 'test';

    /**
     *
     * @author zxf
     * @date    2019年12月25日
     * @param  string $type
     * @return array
     */
    public function run(Request $request)
    {
        try {
            $data = [];
            $type = $request->get('type');
            $type = str_replace(' ', '', $type);
            if (strpos($type, ',') !== false) {
                $typeList = explode(',', $type);
            } else {
                $typeList = [$type];
            }
            if ($typeList) foreach ($typeList as $type) {
                switch ($type) {
                    case self::TYPE_TEST : {
                        $data[$type] = ['key1' => 'value1', 'key2' => 'value2'];
                        break;
                    }
                }
            }
            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
