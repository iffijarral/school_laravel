<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\CreateState;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;

class PaymentController extends Controller
{
    use CreateState;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        
        Stripe::setApiKey(env('STRIPE_API_KEY'));
        
        $packageId = $request->packageID;
        $receipt_email = $request->receipt_email;
        
        /** @var \App\Models\Package $package */
        $package = Package::find($packageId);

        $amout = $package->price;        
        
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amout*100,
            'currency' => 'dkk',
            'receipt_email' => $receipt_email            
        ]);

        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $packageId = $request->get('packageId');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var \App\Models\Payment $payment */
        $payment = Payment::create([
            'txn_id' => $request->get('txn_id'),
            'payment_gross' => $request->get('payment_gross'),
            'currency_code' => 'dkk',
            'payer_email' => $user->email,  
            'payment_status' => $request->get('payment_status'),
            'PackageId' => $packageId,
            'UserId' => $user->id                        
        ]);

        // if payment saved, now save purchased package info into userpackage table
        if($payment) {
            // store userId and packageId into UserPackages table
            $user->packages()->attach($packageId);

            // Verify that data has been stored successfully into UserPackages table
            $connectionTable = DB::table('UserPackages')
            ->where('UserId', $user->id)
            ->where('PackageId', $packageId)
            ->get();

            if ($connectionTable->isNotEmpty()) {
                // update user status after user package has been saved
                $user->status = 1;
                
                // Following save method updates Users table, moreover Auth::user is updated automatically.
                $user->save();

                // $userPackages = $user->packages;
            
                // $numOfTests = 0;

                // if (isset($userPackages) && count($userPackages) > 0) {

                //     $length = count($userPackages);

                //     $numOfTests = $userPackages[$length-1]->numberoftests;                
                // } 
                
                // $state = [
                //     'name'       => $user->name,
                //     'email'      => $user->email,
                //     'noOfTests'  => $numOfTests,
                //     'status'     => $user->status,
                //     'isLoggedIn' => true    
                // ];
                
                // Get state from CreateState trait
                $state = $this->createUserState($user);

                $message = 'User Package saved & status updated successfully';                
    
                return response(compact('state', 'message'));            
    
            } else {
                // Attachment failed
                return response()->json(['message' => 'UserPackage couldn\'t be saved'], 401);
            }
        } else {
            return response()->json(['message' => 'Payment couldn\'t be saved'], 401);
        }        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
