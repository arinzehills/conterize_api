<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Deliveries;
use Validator;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DeliveriesController extends Controller
{
    //

    public function deliver(Request $request){
        $senders_id=$request->senders_id;
        $request_id=$request->request_id;
        $rules = ['senders_id'=>'required:users,user_id','request_id'=>'required:requests,request_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
             $filenames=[];
             $uploadedFileUrls=[];  
             if($request->hasFile('uploads_materials')){
                 foreach($request->file('uploads_materials') as $file) {
                     $fileext = $file->getClientOriginalExtension();
                     $filename = $file->getClientOriginalName();
                     // $file->move('uploads/deliveries', $filename);
                     $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath())->getSecurePath();
                     array_push($uploadedFileUrls,$uploadedFileUrl);
                     array_push($filenames,$filename);
                            }
                           
                }
                
                $deliveries=Deliveries::
                                    create([
                                                'uploads_materials'=>$filenames,
                                                'uploaded_file_urls'=>$uploadedFileUrls,
                                                ]+$request->all()
                                            );
                    
                    return response()->json([
                        'success'=>true,
                        'filenames'=>$filenames,
                        'uploaded_file_urls'=>$uploadedFileUrls,
                        'delivery'=> $deliveries,
                    ], 200);
         }
    }
    public function getRequestDeliveries(Request $request){
        $deliveries=Deliveries::where('request_id',$request->request_id)
        ->get();
        
        return response()->json([
            'success'=>true,
            'deliveries'=> $deliveries,
        ], 200);
    }
    public function acceptDelivery(Request $request){
        $request_id=$request->request_id;
        $rules = ['request_id'=>'required:requests,request_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }
         $getrequest=RequestModel::find($request_id);
         $getrequest->status='archived';
         $getrequest->save();

         return response()->json([
            'success'=>true,
            'message'=>'You have accepted your delivery',
            'request'=> $getrequest,
        ], 200);

    }
    public function requestRevision(Request $request){
        $request_id=$request->request_id;
        $rules = ['request_id'=>'required:requests,request_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }
         $getrequest=RequestModel::find($request_id);
         $getrequest->status='under review';
         $getrequest->save();

         return response()->json([
            'success'=>true,
            'message'=>'You have accepted your delivery',
            'request'=> $getrequest,
        ], 200);   
    }
}