<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class UserAuth extends Controller
{
    //
    function userLogin(Request $request){

        $count = DB::table('member')
                    ->where('member_phone',$request->post('tel'))
                    ->where('member_type','1')
                    ->where('member_pass',$request->post('password'))->count();
                    if ($count>0) {
                        $user = DB::table('member')
                                    ->where('member_phone',$request->post('tel'))
                                    ->where('member_type','1')
                                    ->where('member_pass',$request->post('password'))
                                    ->select('member_name')->first();


                                    $data= $request->post();
                                    $request->session()->put('user',$data['tel']);
                                    $request->session()->put('username',$user->member_name);
                                    return redirect('profile');
                    }
                    return view('loginadmin');

    }
}
