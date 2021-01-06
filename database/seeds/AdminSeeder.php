<?php

use Illuminate\Database\Seeder;
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
        if (!Admin::byUsername($username)->exists()) {
            $model = new Admin();
            $model->fill([
                'username' => $username,
                'password' => 'a123456',
                'login_count' => 0,
                'login_at' => 0,
            ]);
            $model->loadDefaultValue()->encryptPassword();
            return $model->save();
        }
    }
}
