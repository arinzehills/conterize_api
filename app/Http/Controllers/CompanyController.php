<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Validator;

class CompanyController extends Controller
{
    //
    public function updateCompany(Request $request){
        $user_id=$request->user_id;
        $rules = ['user_id'=>'required:companies,user_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
            $user_info=User::find($user_id);
            $user_info->nationality=$request->nationality;
            $user_info->save();
        Company::updateOrCreate(['user_id'=>$user_id],array_filter($request->all()));
        $company_info=Company::
        where('user_id',$user_id)
        ->get();

        
        
        return response()->json([
            'success'=>true,
            'company_info'=> $company_info,
        ], 422);
         }
    }
    public function getUserCompany(Request $request){
        $user_id=$request->user_id;
        $rules = ['user_id'=>'required:companies,user_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
            $company_info=Company::
            where('user_id',$user_id)
            ->get();
            return response()->json([
                'success'=>true,
                'company_info'=> $company_info[0],
            ], 422);
         }
    }
    public function getAllCompanies(Request $request){
        $companies = User::leftJoin('companies', 'users.id', '=', 'companies.user_id')
               ->get();

        return
         $companies;
        // User::all();
    }
}