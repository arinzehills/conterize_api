<?php

namespace App\Http\Controllers;
use App\Models\Request as RequestModel;
use App\Models\RequestDetail;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    //
    public function addRequest(Request $request){
        $user_id=$request->user_id;
        $rules = ['user_id'=>'required:companies,user_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
             $date = Carbon::now();// will get you the current date, time 
            $submitted_by=$date->format("d/m/Y");
            //  create for detail table of the request
             $filenames=[];
             $create_request= RequestModel::create([
                            'submitted_by'=>$submitted_by
                            ]+$request->all());//normal request table
             $req_id=$create_request->id;
             if($request->hasFile('supporting_materials')){

                 foreach($request->file('supporting_materials') as $file) {
                             $fileext = $file->getClientOriginalExtension();
                             $filename = $file->getClientOriginalName();
                             $file->move('uploads/', $filename);
                             array_push($filenames,$filename);

                            }
             }

                        // $request_detail=
                        RequestDetail::create(
                            ['request_id'=>$req_id,
                            'supporting_materials'=>json_encode($filenames),
                            'submitted_by'=>$submitted_by
                            ]+
                            $request->all()
                        );

                    return response()->json([
                        'success'=>true,
                        'message'=>'Your request has been placed successfully',
                        // 'info'=>$create_request
                    ]);
           
          

            //  if($request->hasFile('supporting_materials')) {
                 
            //     foreach($request->file('supporting_materials') as $file) {
            //         $filename = $file->getClientOriginalExtension();
            //         $file->move('uploads/', $filename);
                    
                    
            //         return response()->json([
            //             'success'=>true,
            //             'filename'=>$filename
            //         ]);
            //         // Image::create([
            //         //     'image_name' => $filename
            //         // ]);
            //     }}else{
            //         return response()->json([
            //             'success'=>false,
            //         ]);
            //     }
            // echo $req_id;
            // create for detail table of the request
            //  $request_detail=RequestDetail::create($request->all()+['request_id'=>$req_id]);
            //  $company_info=RequestDetail::
            //  where('user_id',$user_id)
            //  ->get();
            //  return response()->json([
            //     'success'=>true,
            //     'message'=>'unsuccessufl',
            //     'company_info'=> $company_info,
            // ], 422);
         }
    }

    public function getAllRequest(){
        // $companies = User::join('companies', 'users.id', '=', 'companies.user_id')
        //        ->get();
            return 
            RequestModel::all();
    }
    public function getUserRequests(Request $request){
    $id=$request->user_id;
        $rules = ['user_id'=>'required:transactions,user_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
                $requests=RequestModel::
                orderBy('created_at')
                ->where('user_id',$id)
                ->get();
                $request->created_at= 'chris';
                return response()->json([
                    'success'=>true,
                    'message'=>'Your request has been placed successfully',
                    'requests'=>$requests
                ]);
         }
        }
}