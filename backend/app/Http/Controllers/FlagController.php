<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Flag;
use App\AwardPoint;
use App\User;

class FlagController extends Controller
{
    public function store(Request $request){
        $this->validate($request,[
            'flag' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255']
        ]);

        $award_points = AwardPoint::where('flag',$request->flag)->first();
        if($award_points){
            $user = User::select('id')->where('email',$request->email)->first();

            $check = Flag::where('flag',$award_points->id)->where('user_id',$user->id)->first();
            if($check){
                $response = ['message' => 'The given data was invalid.','errors' =>['flag'=>['you have already submitted this flag']]];
                response($response, 422);

            }else{
                $flag = new Flag();
                $flag->flag = $award_points->id;
                $flag->user_id = $user->id;

                if($flag->save()){
                    $user_current_points = $user->points;
                    $user->points = $user_current_points + $award_points->points;
                    $user->save();
                    $response = ['message' => 'Flag accepted, you have been awarded '.$award_points->points.' points'];
                    return response($response, 201);
                }else{
                    $response = ['message' => 'Something went wrong'];
                    return response($response, 503);
                }

            }

        }else{
            $response = ['message' => 'Ops!, wrong flag.'];
            return response($response, 200);
        }
        
    }
}
