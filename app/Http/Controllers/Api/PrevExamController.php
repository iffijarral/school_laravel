<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrevExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrevExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $data = DB::table('PrevExams')         
            ->select('year', 'season', DB::raw('MAX(id) as id'))
            ->groupBy(['year', 'season'])
            ->orderBy('year', 'desc')
            ->get();

        return response()->json($data);
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
        /** @var \App\Models\PrevExam $prevExam */

        // Fetch prevExam by given id
        $prevExam = PrevExam::find($id);
        
        if($prevExam && $prevExam->exists) {
            
            // fetch all questions belong to this test. 
            $season = $prevExam->season;
            $year = $prevExam->year;

            $questions = $prevExam->questions;
                        
            if($questions) {

                return response(compact('questions', 'season', 'year'));

            } else {

                return response()->json(['message' => 'No question found for given prevExamId'], 404);    

            }
            
        }            
        else {
            return response()->json(['message' => 'Invalid prevExamId'], 404);
        } 
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

    /**
     * Get exam By Year And Season
     */
    public function getByYearAndSeason($year, $season)
    {
        $data = PrevExam::select('id','year', 'season')
            ->distinct('year')
            ->get();

        return response()->json($data);
    }
}
