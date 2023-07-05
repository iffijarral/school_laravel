<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all packages
        $packages = Package::all();
        return response()->json($packages);
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get requestId from request
        $packageId = $request->packageId;
        
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

            $message = 'User Package saved & status updated successfully';

            return response(compact('user', 'message'));            

        } else {
            // Attachment failed
            return response()->json(['message' => 'UserPackage couldn\'t be saved'], 401);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $record = Package::find($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($record);
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
