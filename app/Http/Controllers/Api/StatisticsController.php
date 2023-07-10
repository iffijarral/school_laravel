<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Statistics;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); 

        $statistics = User::join('Statistics', 'Users.id', '=', 'Statistics.UserId')
        ->join('Tests', 'Statistics.TestId', '=', 'Tests.id')
        ->where('users.id', $user->id)
        ->select('Statistics.*', 'Tests.title')
        ->get();

        return response()->json(['statistics' => $statistics]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // Validate the user's input
        $request->validate([
            'testID' => 'required|numeric',
            'rightAnswers' => 'required|numeric'            
        ]);


        /** @var \App\Models\User $user */
        $user = Auth::user(); 

        $statistics = Statistics::create([
            'UserId' => $user->id,
            'TestId' => $request->testID,   
            'answers' => $request->rightAnswers            
        ]);

        if($statistics) {
            return response()->json(['message' => 'Statistics Saved'], 201);
        }

        return response()->json(['error' => 'Statistics could not saved'], 422);
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
