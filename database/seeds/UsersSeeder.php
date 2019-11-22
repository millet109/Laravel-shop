<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //通过factory方法生成100个用户并且保存在数据库中
        factory(\App\Models\User::class,100)->create();
    }
}
