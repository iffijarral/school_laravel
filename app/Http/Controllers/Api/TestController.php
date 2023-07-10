<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // Fetch all tests
         $data = Test::all();
         if($data) 
            return response()->json($data);
        else 
            return response()->json(['message' => 'No record found'], 404);
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

        /** @var \App\Models\Test $test */
        $test = Test::find($id);
        
        $test->questions;

        if (!$test) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($test);
    }

    /**
     * Display the specified resource.
     */
    public function show2(string $id)
    {      
        if($id != 1)
            return response()->json(['error' => 'Unauthorized request'], 401);                    
        /** @var \App\Models\Test $test */
        $test = Test::find(4); // 4 is example test id

        $test->questions;

        if (!$test) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($test);
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
