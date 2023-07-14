<?php

namespace App\Http\Traits;

trait CreateState {    

     public function createUserState($user) {        

         /** @var \App\Models\User $user */
        //  $userPackages = $user->packages;

        // Following query will fetch latest purchased package for a user
        $userPackages = $user->packages()
        ->orderBy('UserPackages.id', 'desc')
        ->get();
            
         $numOfTests = 0;
         
         if (isset($userPackages) && count($userPackages) > 0) {                          

             // Fetch last purchased package info
             $numOfTests = $userPackages[0]->numberoftests;             
         } 
         
         $state = [
             'name'       => $user->name,
             'email'      => $user->email,
             'noOfTests'  => $numOfTests,
             'status'     => $user->status,
             'isLoggedIn' => true    
         ];

         return $state;
     }     
    
}