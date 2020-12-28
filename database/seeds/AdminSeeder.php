<?php

use Illuminate\Database\Seeder;
use App\Common\Constants\StatusConst;
use App\Common\Constants\DeleteConst;
use App\Modules\Admin\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return mixed
     */
    public function run()
    {
        //
        $username = '10086';
        if (!Admin::byUsername($username)->notDelete()->exists()) {
            $model = new Admin();
            $model->fill([
                'username' => $username,
                'password' => 'a123456',
                'status_id' => StatusConst::NORMAL,
                'delete_id' => DeleteConst::NOT,
                'login_count' => 0,
                'login_at' => 0,
            ]);
            $model->encryptPassword();
            return $model->save();
        }
    }
}
