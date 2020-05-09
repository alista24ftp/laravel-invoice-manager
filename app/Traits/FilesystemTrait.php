<?php
namespace App\Traits;

trait FilesystemTrait
{
    // eg. $filenames -> ['/uploads/fromdirA/a.png', '/uploads/fromdirB/b.jpg']
    // eg. $toDirPath -> '/uploads/todir'
    public function moveFilesToDir(array $filenames, string $toDirPath)
    {
        $result = [
            'newPaths' => [],
            'failedPaths' => []
        ];
        if(!$toDirPath || empty($toDirPath)) {
            foreach($filenames as $i => $file){
                array_push($result['failedPaths'], ['path' => $file, 'index' => $i]);
            }
            return $result;
        }
        // create destination directory if doesn't exist
        $fullToDirPath = public_path() . $toDirPath;
        if(!file_exists($fullToDirPath) || !is_dir($fullToDirPath)){
            if(!mkdir($fullToDirPath, 0777, true)){
                foreach($filenames as $i => $file){
                    array_push($result['failedPaths'], ['path' => $file, 'index' => $i]);
                }
                return $result;
            }
        }
        if(count($filenames) == 0) {
            return [
                'newPaths' => [],
                'failedPaths' => []
            ]; // default
        }

        // check to see if files from source directory exist
        $validFiles = [];
        foreach($filenames as $i => $file){
            $fullFilePath = public_path() . $file;
            if(file_exists($fullFilePath) && is_file($fullFilePath)){
                array_push($validFiles, ['path' => $file, 'index' => $i]);
            }else{
                array_push($result['failedPaths'], ['path' => $file, 'index' => $i]);
            }
        }
        // Move valid files to destination directory
        foreach($validFiles as $file){
            $fullFilePath = public_path() . $file['path'];
            $filename = pathinfo($fullFilePath, PATHINFO_BASENAME); // eg. 1234567890_abcdefghij.png
            $newFilePath = $toDirPath . '/' . $filename; // eg. /uploads/todir/1234567890_abcdefghij.png
            $newFullFilePath = public_path() . $newFilePath;
            if(strcmp($fullFilePath, $newFullFilePath) == 0){
                // if original and new file paths are the same, then they're automatically assume to be moved successfully
                array_push($result['newPaths'], ['path' => $newFilePath, 'index' => $file['index']]);
            }
            elseif(rename($fullFilePath, $newFullFilePath)){
                // successfully moved
                array_push($result['newPaths'], ['path' => $newFilePath, 'index' => $file['index']]);
            }else{
                // unable to move original file
                array_push($result['failedPaths'], ['path' => $file['path'], 'index' => $file['index']]);
            }
        }

        return $result;
    }
}
