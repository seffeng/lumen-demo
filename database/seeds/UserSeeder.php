<?php

use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;
use App\Common\Constants\StatusConst;
use App\Common\Constants\DeleteConst;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $username = '10086';
        if (!User::where('username', $username)->notDelete()->exists()) {
            $model = new User();
            $model->fill([
                'username' => $username,
                'password' => 'a123456',
                'status_id' => StatusConst::NORMAL,
                'delete_id' => DeleteConst::NOT,
                'login_count' => 0,
                'login_at' => 0,
                'login_ip' => 0,
            ]);
            $model->encryptPassword();
            return $model->save();
        }
    }
}
