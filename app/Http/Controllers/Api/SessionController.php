<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\CreateState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    // Following is a trait, which has a function to generate user state
    use CreateState;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        /** @var \App\Models\User $user */
        $user = Auth::user();                
        
        // $numberOfTests = 0;
        // // Load packages info in $user object
        // $packages = $user->packages;

        // if(isset($packages) && count($packages) > 0) {
        //     $numberOfTests = $packages[0]->numberoftests;
        // }

        // $state = [
        //     'name'       => $user->name,
        //     'email'      => $user->email,
        //     'noOfTests'  => $numberOfTests,
        //     'status'     => $user->status,
        //     'isLoggedIn' => true    
        // ];
        
        // Get state from CreateState trait
        $state = $this->createUserState($user);

        return response()->json($state);
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
