<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plan;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\UserCredits;

use App\Mail\SubSuccessMail;


class PaymentController extends Controller
{   
    protected $stripe;
    //stripe global varible
    public function _construct(){
        $this->stripe=new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }
    
    public function createPlan(Request $request){
        // die($this->stripe);
        $data=$request->all();
        $data['slug']=strtolower($data['plan_name']);
        $data['stripe_plan']= $data['plan_name'];
        
        
        // $price= $request->price *100;
        // $price= $data['price']*100;

        // create product on dashboard
        // $stripeProduct=$this->stripe->products->create([
        //     'name'=>$data['plan_name'],
        // ]);
        // //create plan on dashboard
        // $stripeCreatePlan=$this->stripe->plans-create([
        //     'amount'=>$price,
        //     // 'currency'
        //     'interval'=>'month',//it can be day week, month or year
        //     'product'=>$stripeProduct->id,

        // ]);
        // $request->stripe_plan=$stripePlanCreation->id;

        Plan::create($data);
        return response()->json([
            'success' => true, 
            'message' => 'plan created!',
            // 'payment'=>$payment,
        ]);
    }

    //make a payment
    public function subscribe(Request $request){
        // $user=User::firstOrCreate(['email'=>$request->input(key:'email')])
        $rules = ['email'=>'required:users,email'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
           // handler errors
           $erros = $validator->errors();
           // echo $erros;
           return $erros;
        }else{
        $user =  User::find($request->user_id);
            // return $user;
        try {
            // $payment=$user->charge(
            //     $request->amount,
            //     $request->payment_method_id
            // );
            $plan=Plan::where('plan_name', $request->plan_name)->first();

            $payment=$user->newSubscription(
                $plan->plan_name,$plan->stripe_price_id,
            )->create($request->payment_method_id);
            if($payment){
                $user->plan=$request->plan_name;
                $user->payment_status='paid';
                $user->save();  
                $price=$plan->price/100;
                Mail::to($user->email)->send(new SubSuccessMail($plan->plan_name,$price));
                // Mail::to("hello@conterize.com")->send(new SubSuccessMail($plan->plan_name,$price));
            }
            // $payment=$payment->asStripePaymentIntent();
            return response()->json([
                'success' => true, 
                'message' => 'Made a successful successful subscription!',
                // 'plan'=>$plan,
            ]);
        } catch (\Exception $e) {
            //throw $e
            return response()->json(['message'=>$e->getMessage()],500);

        }
    }
    }
    //make one time payment
    public function makeOneTimePayment(Request $request){
        $rules = ['email'=>'required:users,email','content_type'=>'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
           // handler errors
           $erros = $validator->errors();
           // echo $erros;
           return $erros;
        }else{
        $user =  User::find($request->user_id);
        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($request->payment_method_id);
            $payment=$user->charge(
                $request->amount * 100,
                $request->payment_method_id
            );
            if($payment){
                $user->plan='Customize';
                $user->payment_status='paid';
                $user->invoiceFor('One Time Fee'.$request->content_type,$request->amount);
                $user->save();  
                
                $price=$request->amount *100;
                $user_credits=UserCredits::where('user_id', '=', $user->getKey())->first();
                // $user_credits->forceFill(['content_writing_credits->total_credits' => 10]);
                if($request->content_type=='Content Writing'){
                    $user_credits->forceFill(['total_purchased_credits->content_writing' => $request->credits])->save();
                    $user_credits->forceFill(['content_writing_credits->total_credits' => $request->credits])->save();
                    $user_credits->forceFill(['content_writing_credits->leftover_credits' => $request->credits])->save();
                    // return response()->json([
                    //     'success' => false, 
                    //     'message' => 'Made a successful successful subscription!',
                    //     'credits'=>$user_credits,
                    // ]);
                }else if($request->content_type=='Graphics Design'){
                    $user_credits->forceFill(['total_purchased_credits->graphics' => $request->credits])->save();
                    $user_credits->forceFill(['graphics_credits->total_credits' => $request->credits])->save();
                    $user_credits->forceFill(['graphics_credits->leftover_credits' => $request->credits])->save();
                    
                    // return response()->json([
                    //     'success' => false, 
                    //     'message' => 'Made a successful successful subscription!',
                    //     'credits'=>$user_credits,
                    // ]);
                }else{
                    $user_credits->forceFill(['total_purchased_credits->video' => $request->credits])->save();
                    $user_credits->forceFill(['video_credits->total_credits' => $request->credits])->save();
                            $user_credits->forceFill(['video_credits->leftover_credits' => $request->credits])->save();
                            
                    // return response()->json([
                    //     'success' => false, 
                        // 'message' => 'Made a successful successful subscription!',
                    //     'credits'=>$user_credits,
                    // ]);
                }
                // Mail::to($user->email)->send(new SubSuccessMail("Customize Plan",$price));
                // Mail::to("hello@conterize.com")->send(new SubSuccessMail($plan->plan_name,$price));
            }
            $payment=$payment->asStripePaymentIntent();
            return response()->json([
                'success' => true, 
                'message' => 'Made a successful successful subscription!',
                'plan'=>$user_credits,
                'user'=>$user,
            ]);
        } catch (\Exception $e) {
            //throw $e
            return response()->json(['message'=>$e->getMessage()],500);

        }
    }
    }
    //make one time payment
    public function addCreditsPayment(Request $request){
        $rules = ['email'=>'required:users,email','content_type'=>'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
           // handler errors
           $erros = $validator->errors();
           // echo $erros;
           return $erros;
        }else{
        $user =  User::find($request->user_id);
        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($request->payment_method_id);
            $payment=$user->charge(
                $request->amount * 100,
                $request->payment_method_id
            );
            if($payment){
                $user->invoiceFor('One Time Fee'.$request->content_type,$request->amount);
                $user->save();  
                $price=$request->amount *100;
                $user_credits=UserCredits::where('user_id', '=', $user->getKey())->first();
                if($request->content_type=='Content Writing'){
                    $user_credits->forceFill(['total_purchased_credits->content_writing' =>$user_credits->total_purchased_credits['content_writing'] + $request->leftover_credits])->save();
                    // if($user_credits->content_writing_credits['leftover_credits']<=0){
                    //     $user_credits->forceFill(['content_writing_credits->total_credits' => $request->credits])->save();
                    // }else 
                    if($user_credits->content_writing_credits['used_credits']== $user_credits->content_writing_credits['total_credits']){
                        $user_credits->forceFill(['content_writing_credits->total_credits' => $request->credits])->save();
                        $user_credits->forceFill(['content_writing_credits->leftover_credits' => $request->credits])->save();
                        $user_credits->forceFill(['content_writing_credits->used_credits' => 0])->save();
                    }else{  
                        $user_credits->forceFill(['content_writing_credits->total_credits' => $user_credits->content_writing_credits['total_credits'] + $request->credits])->save();
                        $user_credits->forceFill(['content_writing_credits->leftover_credits' => $user_credits->content_writing_credits['leftover_credits'] + $request->credits])->save();
                    }
                    // $user_credits->forceFill(['content_writing_credits->used_credits' => $user_credits->content_writing_credits['used_credits'] + $request->credits])->save();
                }else if($request->content_type=='Graphics Design'){
                    $user_credits->forceFill(['total_purchased_credits->graphics' =>$user_credits->total_purchased_credits['graphics'] + $request->leftover_credits])->save();
                    if($user_credits->graphics_credits['used_credits']== $user_credits->graphics_credits['total_credits']){
                        $user_credits->forceFill(['graphics_credits->total_credits' => $request->credits])->save();
                        $user_credits->forceFill(['graphics_credits->leftover_credits' => $request->credits])->save();
                        $user_credits->forceFill(['graphics_credits->used_credits' => 0])->save();
                    }else{  
                        $user_credits->forceFill(['graphics_credits->total_credits' => $user_credits->graphics_credits['total_credits'] + $request->credits])->save();
                        $user_credits->forceFill(['graphics_credits->leftover_credits' => $user_credits->graphics_credits['leftover_credits'] + $request->credits])->save();
                    }// $user_credits->forceFill(['graphics_credits->used_credits' => $user_credits->graphics_credits['used_credits'] + $request->credits])->save();
                }else{
                    $user_credits->forceFill(['total_purchased_credits->video' =>$user_credits->total_purchased_credits['video'] + $request->leftover_credits])->save();
                    if($user_credits->video_credits['used_credits']== $user_credits->video_credits['total_credits']){
                        $user_credits->forceFill(['video_credits->total_credits' => $request->credits])->save();
                        $user_credits->forceFill(['video_credits->leftover_credits' => $request->credits])->save();
                        $user_credits->forceFill(['video_credits->used_credits' => 0])->save();
                    }else{  
                        $user_credits->forceFill(['video_credits->total_credits' => $user_credits->video_credits['total_credits'] + $request->credits])->save();
                        $user_credits->forceFill(['video_credits->leftover_credits' => $user_credits->video_credits['leftover_credits'] + $request->credits])->save();
                    }
                    // $user_credits->forceFill(['video_credits->used_credits' => $user_credits->video_credits['used_credits'] + $request->credits])->save();
                }
                // Mail::to($user->email)->send(new SubSuccessMail("Customize Plan",$price));
                // Mail::to("hello@conterize.com")->send(new SubSuccessMail($plan->plan_name,$price));
            }
            $payment=$payment->asStripePaymentIntent();
            return response()->json([
                'success' => true, 
                'message' => 'Made a successful successful payment!',
                'plan'=>$user_credits,
                'user'=>$user,
            ]);
        } catch (\Exception $e) {
            //throw $e
            return response()->json(['message'=>$e->getMessage()],500);

        }
    }
    }
    public function showSubscription() {
        $plans = $this->retrievePlans();
        $user = Auth::user();
        
        return json([
            'user'=>$user,
            'intent' => $user->createSetupIntent(),
            'plans' => $plans
        ]);
    }
    public function retrievePlans() {
        $key = \config('services.stripe.secret');
        $stripe = new \Stripe\StripeClient($key);
        $plansraw = $stripe->plans->all();
        $plans = $plansraw->data;
        
        foreach($plans as $plan) {
            $prod = $stripe->products->retrieve(
                $plan->product,[]
            );
            $plan->product = $prod;
        }
        return $plans;
    }
}