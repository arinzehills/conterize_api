<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plan;
use Validator;
use Illuminate\Support\Facades\Mail;

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