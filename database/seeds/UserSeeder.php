<?php

use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;

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
        if (!User::where('username', $username)->exists()) {
            $model = new User();
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
