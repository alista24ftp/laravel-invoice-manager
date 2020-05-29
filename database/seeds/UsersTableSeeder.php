<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Traits\FilesystemTrait;

class UsersTableSeeder extends Seeder
{
    use FilesystemTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->cleanUp();
        $users = factory(User::class)->times(1)->make();
        User::insert($users->makeVisible([
            'password', 'remember_token'
        ])->toArray());
    }

    protected function cleanUp()
    {
        $res = ['removed'=>[], 'failed'=>[]];
        $this->removeFilesFromDir('/uploads/images/profile_pics', [
            'is_file' => function($fullFilePath){
                return is_file($fullFilePath);
            },
            'is_img' => function($fullFilePath){
                return in_array(strtolower(pathinfo($fullFilePath, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'gif']);
            }
        ], $res);
    }
}
