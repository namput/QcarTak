<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarcareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

	//ลงชื่อเข้าใช้
    public function login(Request $request){
        $phone=$request->input('phone');
        $pass=$request->input('pass');
        $type=$request->input('type');
		if($type==2){
        $user = DB::table('member')
        ->where('member_phone', $phone)
        ->where('member_pass', $pass)
        ->where('member_type', $type)
        ->select('member_id')
        ->first();
		}
        if(!empty($user)){

           return response()->json($user);
        }
        else{
            return "no";
        }

    }
		//สมัครสมาชิก
    public function create(Request $request){
        $phone=$request->input('phone');
        $pass=$request->input('pass');
        $type=$request->input('type');

        if (!empty($phone)&&!empty($pass)&&$type==2) {
           $user=DB::table('member')
        ->where('member_phone', $phone)
        ->count();
        if ($user>0) {
            return false;
        }
        else{
            $user=DB::table('member')
            ->insertGetId([
                'member_phone'=>$phone,
                'member_pass'=>$pass,
                'member_type'=>$type
            ]);
            $user=DB::table('member')->where('member_id',$user)
            ->select($member_id='member_id',$member_phone='member_phone',$member_pass='member_name')
            ->first();
            return response()->json($user);
        }
        }
        else{
            return false;
        }
    }

	//เพิ่มร้าน
public function addcarcare(Request $request){
        $carcare_name = $request->input("carcare_name");
        $member_id = $request->input("member_id");
        $member_name = $request->input("member_name");
        $member_type = $request->input("member_type");
       //update name
        $savename = DB::table('member')
        ->where('member_id',$member_id)
        ->update(['member_name'=>$member_name]);

        if (!empty($savename)) {
            $data = DB::table('carcare')
            ->where('member_id',$member_id)
            ->orWhere('carcare_name',$carcare_name)
            ->count();
            if ($data <= 0) {
                $data = DB::table('carcare')
                            ->whereNotIn('member_id',[$member_id])
                            ->orWhereNotIn('carcare_name',[$carcare_name])
                            ->insertGetId([
                                'carcare_name' => $carcare_name,
                                'member_id' => $member_id,
                                'carcare_opent' => date("8:30:00"),
                                'carcare_close' => date("18:00:00"),
                                'carcare_status' => 'I'

                            ]);
                            return $data;
            }
            else {
                return null;
            }
        }

        return null;

    }
	public function addname(Request $request){
        $member_id = $request->input("member_id");
        $member_name = $request->input("member_name");
        $member_type = $request->input("member_type");
       //update name
        $savename = DB::table('member')
        ->where('member_id',$member_id)
        ->update(['member_name'=>$member_name]);
        if ($savename==true) {
            return $member_id;
        }else{
            return null;
        }

    }
	public function menucarcare(Request $request){
        $member_id = $request->input("member_id");
        $data = DB::table('carcare')
                ->where('member_id',$member_id)
                ->count();

                if ($data>0) {
                    $data = DB::table('carcare')
                ->where('member_id',$member_id)
                ->select($carcare_id = 'carcare_id',$carcare_name = 'carcare_name',$carcare_opent = 'carcare_opent',$carcare_close = 'carcare_close',$carcare_status = 'carcare_status',$carcare_lat = 'carcare_lat',$carcare_long = 'carcare_long')
                ->first();
                    return response()->json($data);
                }else {

                    $carcare_ids = DB::table('permissions')
                    ->where('member_id',$member_id)
                    ->where('permissions_1',"I")
                    ->where('permissions_status',"I")
                    ->select('carcare_id')
                    ->first();

                    if ($carcare_ids) {
                        $carcareid =response()->json($carcare_ids->carcare_id);
                        $ccid=$carcareid->original;
                        $datas = DB::table('carcare')
                                ->where('carcare_id',$ccid)
                                ->select($carcare_id = 'carcare_id',$carcare_name = 'carcare_name',$carcare_opent = 'carcare_opent',$carcare_close = 'carcare_close',$carcare_status = 'carcare_status',$carcare_lat = 'carcare_lat',$carcare_long = 'carcare_long')
                                ->first();
                                if ($datas) {
                                    return response()->json($datas);
                                }else {
                                    return null;
                                }
                    }
                    else {
                        return null;
                    }

                }

    }
    public function updatecarcare(Request $request){
        $status=$request->input("carcare_status");
        switch ($status) {
            case 'true':
                $status="I";
                break;

            case 'false':
                $status="O";
            break;
        }

        $member_id = $request->input('member_id');
        $carcare_id = $request->input('carcare_id');
        $carcare_name = $request->input('carcare_name');
        $carcare_opent = $request->input('carcare_opent');
        $carcare_close = $request->input('carcare_close');
        $carcare_lat = $request->input('carcare_lat');
        $carcare_long = $request->input('carcare_long');

        $data = DB::table('carcare')
                    ->where('carcare_id',$carcare_id)
                    ->where('member_id',$member_id)
                    ->update([
                        'carcare_name' => $carcare_name,
                        'carcare_opent' => $carcare_opent,
                        'carcare_close' => $carcare_close,
                        'carcare_status' => $status,
                        'carcare_lat' => $carcare_lat,
                        'carcare_long' =>  $carcare_long

                    ]);

    return $data;
    }
    public function member_carcare(){
        $data = DB::table('member')
                    ->where('member_type',2)
                    ->where('carcare_id',null)
                    ->select('member_id','member_name')
                    ->get();
                    return response()->json($data);
    }
    public function addmember(Request $request){

        $member_id = $request->post('member_id');
        $carcare_id = $request->post('carcare_id');
        $addmember_id = $request->post('addmember_id');
        $permission1 = $request->post('permission1');
        $permission2 = $request->post('permission2');
        $permission3 = $request->post('permission3');
        switch ($permission1) {
            case 'true':
                $permission1="I";
                break;

            case 'false':
                $permission1="O";
            break;
        }
        switch ($permission2) {
            case 'true':
                $permission2="I";
                break;

            case 'false':
                $permission2="O";
            break;
        }
        switch ($permission3) {
            case 'true':
                $permission3="I";
                break;

            case 'false':
                $permission3="O";
            break;
        }

        $data = DB::table('permissions')
                    ->insert([
                        'carcare_id' => $member_id,
                        'member_id' => $addmember_id,
                        'create_member_id' => $carcare_id,
                        'permissions_1' => $permission1,
                        'permissions_2' => $permission2,
                        'permissions_3' => $permission3
                    ]);
                    if ($data) {
                        $datas = DB::table('member')
                                    ->where('member_id',$addmember_id)
                                    ->update(['carcare_id'=> $carcare_id]);
                        return $datas;
                    }

    }









    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
