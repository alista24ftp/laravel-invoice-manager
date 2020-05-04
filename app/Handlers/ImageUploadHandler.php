<?php
namespace App\Handlers;

use Str;

class ImageUploadHandler
{
    // only allow images with following extensions
    protected $allowed_exts = ['png', 'jpg', 'jpeg', 'gif'];

    public function save($file, $folder, $file_prefixes, $date_prefix=false)
    {
        // file extension (since images copy-paste from clipboard might remove extension)
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // Abort if uploaded file is not an image (extension not a part of allowed_ext)
        if(!in_array($extension, $this->allowed_exts)){
            return false;
        }

        // create saved directory pattern, such as: uploads/images/payment_proofs/201709/21
        // directory name separation allows for faster searching
        $folder_name = "uploads/images/$folder" . ($date_prefix ? '/' . date("Ym/d", time()) : "");

        // absolute upload path, `public_path()` gets `public` directory's absolute path
        // eg. /home/vagrant/Code/invoicemanager/public/uploads/images/payment_proofs/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // concat filename (adding prefix to improve readability, prefix can correspond to model ID)
        // eg. 1_1493521050_7BVc9v9ujP.png (<invoice_no>_time()_random(10).png)
        $prefix_str = '';
        foreach($file_prefixes as $prefix) {
            $prefix_str = $prefix_str . $prefix . '_';
        }
        $filename = $prefix_str . time() . '_' . Str::random(10) . '.' . $extension;

        // Move image to target location path
        $file->move($upload_path, $filename);

        return [
            'path' => "/$folder_name/$filename",
            'full_path' => config('app.url') . "/$folder_name/$filename"
        ];
    }
}
