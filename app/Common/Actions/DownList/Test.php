<?php

namespace App\Common\Actions\DownList;

class Test
{
    /**
     *
     * @author zxf
     * @date   2021年6月17日
     * @param \Illuminate\Http\Request  $request
     * @return void
     */
    public function handle($request)
    {
        $data = [];
        $items = ['key1' => 'value1', 'key2' => 'value2'];
        if ($items) foreach ($items as $key => $item) {
            $data[] = [
                'id' => $key,
                'name' => $item
            ];
        }
        return $data;
    }
}