<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Deliveries;
use Validator;
use Illuminate\Http\Request;

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
            if($request->hasFile('uploads_materials')){

                foreach($request->file('uploads_materials') as $file) {
                            $fileext = $file->getClientOriginalExtension();
                            $filename = $file->getClientOriginalName();
                            $file->move('uploads/deliveries', $filename);
                            array_push($filenames,$filename);

                           }
            }
            $deliveries=Deliveries::
                                create([
                                        'uploads_materials'=>$filenames,
                                        ]+$request->all()
                                    );
            
            return response()->json([
                'success'=>true,
                'delivery'=> $deliveries,
            ], 422);
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
}