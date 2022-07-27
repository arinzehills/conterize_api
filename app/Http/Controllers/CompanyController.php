<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Validator;

class CompanyController extends Controller
{
    //
    public function addCompany(Request $request){
        $user_id=$request->user_id;
        $company_id=$request->company_id;
        $rules = ['user_id'=>'required:companies,user_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
            $user_info=User::find($user_id);
            if($request->nationality){//check if is null
                $user_info->nationality=$request->nationality;
                $user_info->save();
            }
        Company::updateOrCreate(['id'=>$company_id],array_filter($request->all()));
        $company_info=Company::
        where('user_id',$user_id)
        ->get();

        // User::where('id', $user_id)->update(array_filter($request->all()));
        // $user =  User::find($user->id);
        
        
        return response()->json([
            'success'=>true,
            'company_info'=> $company_info,
        ], 422);
         }
    }
    public function getUserCompanies(Request $request){
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
                // 'company_info'=> $company_info[0],
                'company_info'=> $company_info,
            ], 422);
         }
    }
    public function getUserCompanyDetail(Request $request){
        $company_id=$request->company_id;
        $user_id=$request->user_id;
        $rules = ['company_id'=>'required:companies,company_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
            // $company_info=Company::
            // where('id',$company_id)
            // ->get();

            $companies = User::Join('companies', 'users.id', '=', 'companies.user_id')
            ->where('user_id',$user_id)->get();
            return response()->json([
                'success'=>true,
                'company_info'=> $companies[0],
            ], 422);
         }
    }
    public function deleteCompany(Request $request){
        $company_id=$request->company_id;
        $rules = ['company_id'=>'required:companies,company_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
            $company_info=Company::
            where('id',$company_id)
            ->delete();
            return response()->json([
                'success'=>true,
                'company_info'=> $company_info,
            ], 422);
         }
    }
    public function getAllCompanies(Request $request){
        // $companies = User::
        //             where('user_type','business_user')->
        //             leftJoin('companies', 'users.id', '=', 'companies.user_id')
        //             ->get();
                    $companies = User::
                    where('user_type','business_user')->
                    // leftJoin('companies', 'users.id', '=', 'companies.user_id')
                    get();

        return
         $companies;
        // User::all();
    }
}