<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\FilesystemTrait;
use Carbon\Carbon;

class DeleteTempFiles extends Command
{
    use FilesystemTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:temp_imgs {older_than=86400} {--timeunits=s}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove images stored under /uploads/images/temp folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $amountTime = $this->argument('older_than');
        $timeUnits = $this->option('timeunits');
        $curr_ts = time();

        $tempdir = '/uploads/images/temp';
        $criteria = [
            'is_file' => function($fullFilePath){
                return is_file($fullFilePath);
            },
            'is_img' => function($fullFilePath){
                return in_array(strtolower(pathinfo($fullFilePath, PATHINFO_EXTENSION)), ['jpg', 'png', 'jpeg', 'gif']);
            },
            'older_than' => function($fullFilePath) use ($amountTime, $timeUnits, $curr_ts){
                $file_ts = filemtime($fullFilePath); // eg. 1588407040
                $dtFile = Carbon::createFromTimestamp($file_ts);
                $dtNow = Carbon::createFromTimestamp($curr_ts);
                switch($timeUnits){
                    case 'y':
                        return $dtNow->diffInYears($dtFile) >= $amountTime;
                    case 'M':
                        return $dtNow->diffInMonths($dtFile) >= $amountTime;
                    case 'd':
                        return $dtNow->diffInDays($dtFile) >= $amountTime;
                    case 'h':
                        return $dtNow->diffInHours($dtFile) >= $amountTime;
                    case 'm':
                        return $dtNow->diffInMinutes($dtFile) >= $amountTime;
                    default:
                        return $dtNow->diffInSeconds($dtFile) >= $amountTime;
                }
            }
        ];
        $res = ['removed'=>[], 'failed'=>[]];
        $removeResult = $this->removeFilesFromDir($tempdir, $criteria, $res);
        // Output log file removal results
        foreach($res['removed'] as $filename){
            $this->info('Successfully removed ' . $filename);
        }
        foreach($res['failed'] as $filename){
            $this->error('Failed to remove ' . $filename);
        }
    }
}
