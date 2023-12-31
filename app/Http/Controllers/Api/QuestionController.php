<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($testId)
    {
        
        /** @var \App\Models\Test $test */

        // Fetch test by given id
        $test = Test::find($testId);
        
        if($test && $test->exists) {
            
            // fetch all questions belong to this test. 
            $questions = $test->questions;
                        
            if($questions) {

                return response()->json($questions);

            } else {

                return response()->json(['message' => 'No question found for given testId'], 404);    

            }
            
        }            
        else {
            return response()->json(['message' => 'Invalid testId'], 404);
        }        
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
