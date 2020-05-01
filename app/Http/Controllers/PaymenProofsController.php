<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\PaymentProof;
use App\Models\Invoice;
use App\Handlers\ImageUploadHandler;

class PaymentProofsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, ImageUploadHandler $uploader)
    {
        $validatedReq = $request->validate([
            'file' => 'required|file|mimes:jpg,png,jpeg,gif',
            'invoice_no' => 'nullable|exists:invoices,invoice_no'
        ]);
        $res = [
            'success' => false,
            'msg' => 'Unable to upload file',
            'imgPath' => '',
            'imgFullPath' => ''
        ];
        if($file = $validatedReq->upload_file){
            if($invoice_no = $validatedReq->invoice_no){ // associated with invoice
                $upload_res = $uploader->save($file, 'payment_proofs', ['invoice_no' => $invoice_no], true);
                if($upload_res){ // upload success
                    // save payment proof to database
                    $proof = new PaymentProof([
                        'invoice_no' => $invoice_no,
                        'path' => $upload_res['path'],
                        'create_time' => now()
                    ]);
                    $proof->save();

                    // return success response
                    $res['success'] = true;
                    $res['msg'] = 'Upload success';
                    $res['imgPath'] = $upload_res['path'];
                    $res['imgFullPath'] = $upload_res['full_path'];
                    $res['id'] = $proof->id;
                }
            }else{
                $upload_res = $uploader->save($file, 'temp', [], false);
                if($upload_res) {
                    $res['success'] = true;
                    $res['msg'] = 'Upload success';
                    $res['imgPath'] = $upload_res['path'];
                    $res['imgFullPath'] = $upload_res['full_path'];
                    $res['id'] = 0;
                }
            }
        }

        return response($res);
    }

    public function delete(Request $request, PaymentProof $proof)
    {
        $proof_full_path = public_path() . '/' . $proof->path;
        $res = [
            'success' => false,
            'msg' => 'Delete failed'
        ];
        if($delete_res = $proof->delete()){
            if(file_exists($proof_full_path)){
                if(unlink($proof_full_path)){
                    $res['success'] = true;
                    $res['msg'] = 'Delete success';
                }
            }
        }

        return response($res);
    }

    public function deleteTemp(Request $request)
    {
        $proof_full_path = public_path() . '/' . $request->path;
        $res = [
            'success' => false,
            'msg' => 'Delete failed'
        ];
        if(file_exists($proof_full_path)){
            if(unlink($proof_full_path)){
                $res['success'] = true;
                $res['msg'] = 'Delete success';
            }
        }
        return response($res);
    }
}
