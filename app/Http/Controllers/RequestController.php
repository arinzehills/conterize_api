<?php

namespace App\Http\Controllers;
use App\Models\Request as RequestModel;
use App\Models\RequestDetail;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
             $uploadedFileUrls=[];  
             $create_request= RequestModel::create([
                            'submitted_by'=>$submitted_by,
                            'status'=>'active'
                            ]+$request->all());//normal request table
             $req_id=$create_request->id;
             if($request->hasFile('supporting_materials')){

                 foreach($request->file('supporting_materials') as $file) {
                             $fileext = $file->getClientOriginalExtension();
                             $filename = $file->getClientOriginalName();
                            //  $file->move('uploads/', $filename);
                            //  array_push($filenames,$filename);
                            $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath())->getSecurePath();
                            array_push($uploadedFileUrls,$uploadedFileUrl);
                            array_push($filenames,$filename);
                            }
             }

                        // $request_detail=
                        RequestDetail::create(
                            ['request_id'=>$req_id,
                            'supporting_materials'=>$filenames,
                            'uploaded_file_urls'=>$uploadedFileUrls,
                            'submitted_by'=>$submitted_by,
                            ]+
                            $request->all()
                        );

                    return response()->json([
                        'success'=>true,
                        'message'=>'Your request has been placed successfully',
                        // 'info'=>$create_request
                    ]);
         }
    }

    public function getAllRequest(){
        // $companies = User::join('companies', 'users.id', '=', 'companies.user_id')
        //        ->get();
            return 
            RequestModel::
            orderBy('created_at','desc')->
            get();
    }
    public function getUserRequests(Request $request){
        $id=$request->user_id;
        $rules = ['user_id'=>'required:requests,user_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
                $requests=RequestModel::
                orderBy('created_at','desc')
                ->where('user_id',$id)
                ->get();
                $draft_requests=RequestModel::
                orderBy('created_at','desc')
                ->where('user_id',$id)
                ->where('is_draft','yes')
                ->get();
                $totalRequests=RequestModel::
                where('user_id',$id)
                ->count();
                $graphics=RequestModel::
                where('category','graphics')
                ->where('user_id',$id)
                ->count();
                $video=RequestModel::
                where('category','video')
                ->where('user_id',$id)
                ->count();
                $contentWriting=RequestModel::
                where('category','content')
                ->where('user_id',$id)
                ->count();
                return response()->json([
                    'success'=>true,
                    'message'=>'Your request has been placed successfully',
                    'requests'=>$requests,
                    'draft'=>$draft_requests,
                    'total_request'=>$totalRequests,
                    'graphics'=>$graphics,
                    'content_writing'=>$contentWriting,
                    'video'=>$video,
                ]);
         }
        }
    public function getUserTotalRequests(Request $request){
        $id=$request->user_id;
        $rules = ['user_id'=>'required:requests,user_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
                $totalRequests=RequestModel::
                where('user_id',$id)
                ->count();
                $graphics=RequestModel::
                where('category','graphics')
                ->where('user_id',$id)
                ->count();
                $video=RequestModel::
                where('category','video')
                ->where('user_id',$id)
                ->count();
                $contentWriting=RequestModel::
                where('category','content')
                ->where('user_id',$id)
                ->count();
                return response()->json([
                    'success'=>true,
                    'message'=>'Your request has been placed successfully',
                    'total_request'=>$totalRequests,
                    'graphics'=>$graphics,
                    'content_writing'=>$contentWriting,
                    'video'=>$video,
                ]);
         }
        }

        public function getUserRequestDetail(Request $request){
            $user_id=$request->user_id;
            $request_id=$request->request_id;
            $rules = ['user_id'=>'required:requests,user_id',
            'request_id'=>'required:requests,request_id'];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                // handler errors
                $erros = $validator->errors();
                // echo $erros;
                return $erros;
             }else{
                 //query the request detail of the given request id and check the 
                 //matching column id in the request table and join them
                 $request_detail=RequestDetail::where('request_id',$request_id)
                                ->join('requests', 'requests.id', '=', 'request_details.request_id')->get();
                 $supporting_materials= RequestDetail::all(['supporting_materials'])->
                 where('request_id',$request_id)->toArray();
                    return 
                    
                    response()->json([
                        'success'=>true,
                        'message'=>'Your request has been placed successfully',
                        // 'supporting_materials'=>$supporting_materials,
                        'requests'=>$request_detail
                    ]);
             }
            }
}