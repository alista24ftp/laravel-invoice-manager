<?php

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Traits\FilesystemTrait;

class CompaniesTableSeeder extends Seeder
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
        $companies = factory(Company::class)->times(1)->make();
        Company::insert($companies->toArray());
    }

    protected function cleanUp()
    {
        $res = ['removed'=>[], 'failed'=>[]];
        $this->removeFilesFromDir('/uploads/images/company_logos', [
            'is_file' => function($fullFilePath){
                return is_file($fullFilePath);
            },
            'is_img' => function($fullFilePath){
                return in_array(strtolower(pathinfo($fullFilePath, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'gif']);
            }
        ], $res);
    }
}
