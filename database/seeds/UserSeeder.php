<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator=new \App\User;
        $administrator->username='administrator';
        $administrator->name = "Site Administrator";
        $administrator->email = "administrator@larashop.test";
        $administrator->roles = "ADMIN";
        $administrator->password = \Hash::make("larashop");
        $administrator->avatar = "avatar/myAvatar.png";
        $administrator->address = "Sarmili, Bintaro, Tangerang Selatan";
        $administrator->phone = "0855444666";
        $administrator->save();

        $this->command->info("User Admin berhasil diinsert");
    }
}
