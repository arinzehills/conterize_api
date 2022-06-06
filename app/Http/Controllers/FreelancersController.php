<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Request as RequestModel;

use Illuminate\Http\Request;

class FreelancersController extends Controller
{
    //
    public function getAllFreelancers(Request $request){
        $freelancers=User::
            where('user_type','content_creator')
            ->get();
            return $freelancers;
    }
    public function assignFreelancer(Request $request){
        $request_id=$request->id;
        $freelancer_name=$request->freelancer_name;
        $user_request =  RequestModel::find($request->id);
        $user_request ->assign_to=$freelancer_name;
        $user_request->save();

        return response()->json([
            'success' => true, 
            'message' => 'Password has been updated successfully!',
           'user_request'=> $user_request]);

    }
}