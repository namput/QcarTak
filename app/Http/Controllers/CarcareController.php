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
	public function __construct()
    {
        $this->api_url = "https://fcm.googleapis.com/fcm/send";
        $this->server_key = "key=AAAAR3-D5NQ:APA91bG-cF8IpLDzLSttTFv02ivuPhM9zmW8M2ZLYNYUsFbsVgK_hZqp9ddLNUo98SZh3-o5yobnZoPGjIH2Dd637esykLJQlauHbgzb60ydqPlPJRGaMphOJK0JPFv3HTXzNgZiZ1DG";
    }
   public function index()
    {
        $color = "#004578";
        $title ="ขอให้พลังสถิตกับเจ้า";
        $body = "และสถิตย์กับเจ้าด้วย";
  $token_target =array("etSOMnYVeyqGjEQT24ecSW:APA91bEoCjTE7eWRVRheupEQuiciWTfWLDX1M3VW71rt-P7bjxhmDw4NSTUiTgf39utEMBYhrnEACq6kFvGCl8UzTbXFsbcScMkdc5dWonwcqX0NhVFZyv0U27XvuuWhT0PXkc-cSudB","eSgJ_CaKuHc:APA91bE_5RN8tfB77tU8jtWrqnSwTZr-CRDBY7t2v_3hSTpzMJUKMKjQxH3rH2VkZIOQoY1mrNnHdlz_3xESvdszMzNwH46fSrmGGUh_NM4RZypD--hZqzx7jubzKkOamb_4fvX2rW_V");
      $t=null;
foreach ($token_target as $key => $value) {
	if($t==null){
	 $t='"'.$value.'"';
	}else{
		$t.=',"'.$value.'"';
	}
}
	 $t="[".$t."]";
        $json = "{
                \"registration_ids\" :$t,
                \"priority\" : \"high\",
                \"notification\" : {
                  \"body\"  : \"$body\",
                  \"title\" : \"$title\",
                  \"icon\"  : \"myicon\"
                  \"color\" : \"$color\"
                  }
            }";

            $context = stream_context_create(array(
                'http' => array(
                    'method' => "POST",
                    'header' => "Authorization: ".$this->server_key."\r\n".
                                "Content-Type: application/json\r\n",
                    'content' => "$json"
                )
            ));
            $response = file_get_contents($this->api_url, FALSE, $context);

            if($response === FALSE){
                die('Error');
            }else{
                return $response;
            }

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
        ->select('member_id','carcare_id')
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
				//update carcare_id

        $savename = DB::table('member')
        ->where('member_id',$member_id)
        ->update(['carcare_id'=>$data]);


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
	//เช็คสิทธิ์การเข้าเมนู
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
	//อัพเดตร้าน
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
                        'carcare_id' => $carcare_id,
                        'member_id' => $addmember_id,
                        'create_member_id' => $member_id,
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
	public function listmembercarcare(Request $request){

        $carcare_id = $request->post('carcare_id');
        $data = DB::table('permissions')
                    ->leftJoin('member','permissions.member_id','=','member.member_id')
                    ->where('permissions.carcare_id',$carcare_id)
                    ->select('permissions_id','member_name')
                    ->get();
                    return response()->json($data);

    }
	public function gettoken(Request $request){
        $member_token = $request->post("token");
        $member_id = $request->post("member_id");
        return DB::table('member')
            ->where("member_id",$member_id)
            ->update(['token'=>$member_token]);

    }
	public function listattribute(Request $request){
        $carcare_id = $request->post('carcare_id');
        $data = DB::table('attribute')
                ->where('carcare_id',$carcare_id)
                ->get();
                return response()->json($data);
    }
public function addattribute(Request $request){
        $carcare_id = $request->post("carcare_id");
        $name = $request->post("name");
        $sizes = $request->post("sizes");
        $sizem = $request->post("sizem");
        $sizel = $request->post("sizel");
        $sizexl = $request->post("sizexl");
        $sizexxl = $request->post("sizexxl");
        $data = DB::table('attribute')
                    ->insert([
                        'attribute_name' => $name,
                        'carcare_id' => $carcare_id,
                        'attribute_s' => $sizes,
                        'attribute_m' => $sizem,
                        'attribute_l' => $sizel,
                        'attribute_xl' => $sizexl,
                        'attribute_xxl' => $sizexxl,
                    ]);
                   return $data;


    }
	public function listreportyear(Request $request){
        $carcare_id = $request->post("carcare_id");
        $data = DB::table('queue')
                    ->where('carcare_id',$carcare_id)
                    ->where('status_id',3)
                    ->select('create_date')
                    ->get();

       return $data;

    }
	public function listreportqueue(Request $request){
        $carcare_id = $request->post("carcare_id");
        $data = DB::table('queue')
                    ->where('carcare_id',$carcare_id)
                    ->where('status_id',3)
                    ->orderByDesc('create_date')
                    ->select('create_date')
                    ->get();
                $name=[];
                   foreach ($data as $value) {
                    $name[$value->create_date]=0;
                   }
                   foreach ($name as $key =>$v) {
                    foreach ($data as $value) {
                        if ($key==$value->create_date) {
                            $name[$key]++;

                        }
                       }
                   }
                   $data=[];
                   $i=0;
                   foreach ($name as $key =>$v) {
					   $data[$i]=[];
                    $data[$i]["dates"] = $key;
                    $data[$i]["num"] = $v;
                    $i++;
                   }
        return $data;

    }
	//เช็คสิทธิ์การเข้าเมนูดูประวัติ
public function menureport(Request $request){
    $member_id = $request->input("member_id");
    $data = DB::table('carcare')
            ->where('member_id',$member_id)
            ->count();

            if ($data>0) {
                return $data;
            }else {

                $carcare_ids = DB::table('permissions')
                ->where('member_id',$member_id)
                ->where('permissions_3',"I")
                ->where('permissions_status',"I")
                ->select('carcare_id')
                ->first();

                if ($carcare_ids) {
                    $carcareid =response()->json($carcare_ids->carcare_id);
                    $ccid=$carcareid->original;
                    $datas = DB::table('carcare')
                            ->where('carcare_id',$ccid)
                            ->count();
                            return $datas;
                }
                else {
                    return $data;
                }

            }
}
	//เช็คสิทธิ์การเข้าเมนูรายการ
public function menuattribute(Request $request){
    $member_id = $request->input("member_id");
    $data = DB::table('carcare')
            ->where('member_id',$member_id)
            ->count();

            if ($data>0) {
                return $data;
            }else {

                $carcare_ids = DB::table('permissions')
                ->where('member_id',$member_id)
                ->where('permissions_2',"I")
                ->where('permissions_status',"I")
                ->select('carcare_id')
                ->first();

                if ($carcare_ids) {
                    $carcareid =response()->json($carcare_ids->carcare_id);
                    $ccid=$carcareid->original;
                    $datas = DB::table('carcare')
                            ->where('carcare_id',$ccid)
                            ->count();
                            return $datas;
                }
                else {
                    return $data;
                }

            }
}
//เช็คสิทธิ์การเข้าเมนูคิว
public function menuqueue(Request $request){
    $member_id = $request->input("member_id");
    $data = DB::table('carcare')
            ->where('member_id',$member_id)
            ->count();

            if ($data>0) {
                return $data;
            }else {

                $carcare_ids = DB::table('permissions')
                ->where('member_id',$member_id)
                ->where('permissions_4',"I")
                ->where('permissions_status',"I")
                ->select('carcare_id')
                ->first();

                if ($carcare_ids) {
                    $carcareid =response()->json($carcare_ids->carcare_id);
                    $ccid=$carcareid->original;
                    $datas = DB::table('carcare')
                            ->where('carcare_id',$ccid)
                            ->count();
                            return $datas;
                }
                else {
                    return $data;
                }

            }
}
//การจองคิว
public function queueing(Request $request){

        $carcare_id = $request->post("carcare_id");
        $datenow =$request->input("datenow");
        $old_date = explode('/',$datenow);
        if($old_date[1]<=9){
			$old_date[1] ="0".$old_date[1];
		    }
		    if($old_date[0]<=9){
			$old_date[0] ="0".$old_date[0];
		    }
        $datenow = '20'.$old_date[2].'-'.$old_date[1].'-'.$old_date[0];

        $data = DB::table('queue')
                    ->where('create_date',$datenow)
                    ->where('carcare_id',$carcare_id)
                    ->whereNotIn('status_id',["3","4"])
                    ->get();
                    return response()->json($data);

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
