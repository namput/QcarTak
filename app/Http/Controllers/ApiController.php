<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $member=DB::table('member')->get();
        return response()->json($member);
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

	//ลงชื่อเข้าใช้
    public function login(Request $request){
        $phone=$request->input('phone');
        $pass=$request->input('pass');
        $type=$request->input('type');
        $user = DB::table('member')
        ->where('member_phone', $phone)
        ->where('member_pass', $pass)
        ->where('member_type', $type)
        ->select('member_id')
        ->first();
        if(!empty($user)){
           // return $user;
           //return view('menu',['data'=>$user]);
           //return $user;
           return response()->json($user);
        }
        else{
            return false;
        }

    }

	//สมัครสมาชิก
    public function create(Request $request){
        $phone=$request->input('phone');
        $pass=$request->input('pass');
        $type=$request->input('type');

        if (!empty($phone)&&!empty($pass)) {
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
//แสดงข้อมูลโปรไฟล์
    public function showprofile(Request $request){
        $id=$request->input('id');
        $type=$request->input('type');

        $user=DB::table('member')
        ->where('member_id',$id)
        ->where('member_type',$type)
        ->select($member_id='member_id',$member_phone='member_phone',$member_pass='member_name')
        ->first();
        return response()->json($user);
    }

	//แก้ไขชื่อ
    public function updatename(Request $request,$values = []){
        $id=$request->input('id');
        $name=$request->input('name');
        $type=$request->input('type');

        $user=DB::table('member')
        ->where('member_id',$id)
        ->where('member_type',$type)
        ->update(['member_name'=>$name]);

        if ($user==true) {
            $user=DB::table('member')
            ->where('member_id',$id)
            ->where('member_type',$type)
            ->select($member_id='member_id',$member_phone='member_phone',$member_pass='member_name')
            ->first();
            return response()->json($user);
        }
        else{
            return false;
        }
    }

	//แสดงประเภทรถ
    public function showcar(Request $request){
       $data=DB::table('car_service')
        ->select($car_service_id='car_service_id',$car_service_name='car_service_name')
        ->get();
        return response()->json($data);
    }

	//แสดงสีรถ
    public function showcolor(Request $request){
        $data=DB::table('color')
         ->select($color_id='color_id',$color_name='color_name')
         ->get();
         return response()->json($data);
     }

	//บันทึกรถสมาชิก
public function carmember(Request $request){

        if($request->input('id')!=null&&$request->input('car_service')!=null&&$request->input('color')!=null&&$request->input('car_number')!=null&&$request->input('brand')!=null&&$request->input('type')!=null){
            $id=$request->input('id');
            $carser=$request->input('car_service');
            $color=$request->input('color');
            $carnumber=$request->input('car_number');
            $brand=$request->input('brand');
            $type=$request->input('type');
            $save=DB::table('car_member')
            ->insertGetId([
                'car_service_id'=>$carser,
                'member_id'=>$id,
                'car_member_number'=>$carnumber,
                'color_id'=>$color,
                'car_member_brand'=>$brand
            ]);
            if($save){
                return "ok";
            }else{
            return null;
        }
        }else{
            return null;
        }
     }

	//ประว้ติรายการจองคิว
	 public function history(Request $request){
        $id=$request->input('id');
        $datas=DB::table('queue')
        ->leftJoin('carcare','queue.carcare_id','=','carcare.carcare_id')
        ->leftJoin('car_member','queue.car_member_id','=','car_member.car_member_id')
        ->leftJoin('car_service','car_member.car_service_id','=','car_service.car_service_id')
        ->where('queue.member_id',$id)
        ->where('queue.status_id','3')
        ->select([
            'carcare_name' => "carcare.carcare_name",
            'create_date' => "queue.create_date",
            'car_member_number' => "car_member.car_member_number",
            'car_service_name' => 'car_service.car_service_name'
        ])->get();

        return $datas;
    }

	//รายการร้านคาร์แคร์
	public function listcarcare(Request $request){
        $datas=DB::table('carcare')
        ->leftJoin('queue','carcare.carcare_id','=','queue.carcare_id')
        // ->where('queue.create_date',date('Y-m-d'))
        ->select([
            'carcare_id' =>"carcare.carcare_id",
            'carcare_name' =>"carcare.carcare_name",
            'score' =>"queue.score",
            'all_time' => "queue.all_time",
            'status_id' => "queue.status_id",
            'create_date' => 'queue.create_date'
        ])->get();
            $data=[];
            $carcare[][]=[];
        $data = json_decode(json_encode($datas), true);

            foreach($data as $item){
                //เอาไอดีที่ซ้ำกันมารวมเป็นหนึง
                   $carcare[$item['carcare_id']]=[];
            }

           $i=0;
            foreach($carcare as $item=>$v){
                //เอาไอดีมาวนลูปเพื่อเก็บค่าเป็นชุดๆ
                if (!empty($item)) {
                    //เช็คข้อมูลว่าถ้าไม่ว่างให้ทำในนี้
                    $num=1;
                    $score=0;
                    $status=0;
                    $name=null;
                    $alltime=0;
                        foreach($data as $items){

                             // เอาชุดข้อมูลมาวนลูปใหม่
                                if ($item == $items['carcare_id']) {
                                    if ($items['status_id']!=null&&$items['create_date']==date('Y-m-d')&&$items['status_id']!='3') {
                                        $num++;
                                        $score+= (int)$items['score'];
                                        $name=$items['carcare_name'];
                                        $status++;
                                    $alltime+=$items['all_time'];
                                    }

                                if (empty($name)) {
                                    $name=$items['carcare_name'];
                                }
                            }

                        }
                        $carcare[$i][$item]['id']=$item;
                        $carcare[$i][$item]['name']=$name;
                        $carcare[$i][$item]['num']=$num-1;
                        $carcare[$i][$item]['score']=$score/$num;
					$h=0;
					while ($alltime >= 60) {
                            $h++;
                            $alltime=$alltime-60;
                        }

                        $carcare[$i][$item]['hour']=$h;
                        $carcare[$i][$item]['minute']=$alltime;
                }
                $i++;
            }
            $i=0;
            $datass=[];
            foreach ($carcare as $key => $value) {
                if ($key) {
                    foreach ($value as $v) {

                       $datass[]= $v;
                    }
                }
            }
              return $datass;
    }
	//แสดงรายการของรถของสมาชิก
	  public function listcarmember(Request $request){
        $id=$request->input('id');
      $data=DB::table('car_member')
       ->leftjoin('color','car_member.color_id','=','color.color_id')
       ->leftjoin('car_service','car_member.car_service_id','=','car_service.car_service_id')
       ->where('member_id',$id)
       ->select(
           $carid='car_member_id',
           $carser='car_service_name',
          $color='color_name',
           $car_number='car_member_number',
           $brand='car_member_brand'
           )
       ->get();
    $datas=[];
       $i=0;
       foreach ($data as $value) {
        $datas[$i]["id"]=$value->car_member_id;
        $datas[$i]["sername"]=$value->car_service_name;
        $datas[$i]["color"]=$value->color_name;
        $datas[$i]["carnumber"]=$value->car_member_number;
        $datas[$i]["brand"]=$value->car_member_brand;
        $i++;
       }
       return response()->json($datas);

    }
	//แสดงรายการของร้านคาร์แคร์
	public function attribute(Request $request){
        $id=$request->input('id');
        $id_carcare=$request->input('cid');
        $id_member_car=$request->input('cmid');
        $datas=DB::table('car_member')
        ->leftJoin('car_service','car_member.car_service_id','=','car_service.car_service_id')
        ->where('member_id',$id)
        ->where('car_member_id',$id_member_car)
        ->select('car_service_size')
        ->get();
        $size=$datas[0]->car_service_size ;
        switch ($size) {
            case 's':
                $size="attribute_s";
                break;
            case 'm':
                $size="attribute_m";
                break;
            case 'l':
                $size="attribute_l";
                break;
            case 'xl':
                $size="attribute_xl";
                break;
            case 'xxl':
                $size="attribute_xxl";
                break;
        }

        $data=DB::table('carcare')
        ->leftJoin('attribute','carcare.carcare_id','=','attribute.carcare_id')
        ->where('carcare.carcare_id',$id_carcare)
        ->select([
            'attribute_id'=>'attribute.attribute_id',
            'attribute_name'=>'attribute.attribute_name',
             $size => 'attribute.'.$size
        ])
        ->get();
        $j=0;
        $getdata[]=[];

        foreach ($data as $key => $value) {
            $i=0;
            foreach ($value as $k => $v) {
                if ($i==2) {
                    switch ($k) {
                        case 'attribute_s':
                            $getdata[$j]['size']='s';
                            break;
                        case 'attribute_m':
                            $getdata[$j]['size']='m';
                            break;
                        case 'attribute_l':
                            $getdata[$j]['size']='l';
                            break;
                        case 'attribute_xl':
                            $getdata[$j]['size']='xl';
                            break;
                        case 'attribute_xxl':
                            $getdata[$j]['size']='xxl';
                            break;
                    }
                    $getdata[$j]['time']=$v;
                }
                else{
                    $getdata[$j][$k]=$v;
                }
            $i++;
            }
        $j++;
        }
        return $getdata;

    }
//จองคิว
    public function queue(Request $request){
       $main = $request->input(0);
       $num = count($request->input());
       $data=[];
       for($i=1;$i<$num;$i++){
            $vl= $request->input($i);
            $data[$i-1]=$vl["attribute"];
       }


        $id = $main["id"];
        $id_member_car = $main["cmid"];
        $id_carcare = $main["cid"];

        //ดึงไซต์ออกมา
        $attr=DB::table('car_member')
        ->leftJoin('car_service','car_member.car_service_id','=','car_service.car_service_id')
        ->where('car_member_id',$id_member_car)
        ->select('car_service.car_service_size')
        ->get();
        $size=$attr[0]->car_service_size;
        switch ($size) {
            case 's':
                $size="attribute_s";
                break;
            case 'm':
                $size="attribute_m";
                break;
            case 'l':
                $size="attribute_l";
                break;
            case 'xl':
                $size="attribute_xl";
                break;
            case 'xxl':
                $size="attribute_xxl";
                break;
        }

         //บันทึกqueue
         $saves=DB::table('queue')
         ->insertGetId([
             'member_id'=>$id,
             'car_member_id'=>$id_member_car,
             'carcare_id'=>$id_carcare,
             'create_date'=>now()
         ]);

         if ($saves) {

            // บันทึกรายการคิว
            foreach ($data as $value) {

                    DB::table('queue_list')->insert([
                        'list_id'=>$value,
                        'queue_id'=>$saves
                    ]);

            }
            //เอาเวลาแต่ละรายการมาบวก
            $getdata=DB::table('attribute')
              ->whereIn('attribute.attribute_id',$data)
              ->select($size='attribute.'.$size)
              ->get();

              $time=0;
              foreach ($getdata as  $value) {
                foreach ($value as $v) {
                  $time+=$v;
                }

            }
			 //นับลำดับ
            $count = DB::table('queue')
            ->where('create_date',date('Y-m-d'))
            ->where('carcare_id',$id_carcare)
            ->count('*');

			 //ใส่ลำดับ
            $count = DB::table('queue')
            ->where('queue_id',$saves)
            ->update(['all_time'=>$time,'queue_order'=>$count]);


            $getId=null;
            $data=DB::table('queue')
            ->where('member_id',$id)
            ->whereNotIn('status_id', [3])
            ->where('create_date',date('Y-m-d'))
            ->select('queue_id','carcare_id')->get();
            if (count($data)>0) {
                $getId = $data[0]->queue_id;

            }

        $getvalue=DB::table('queue')
            ->leftJoin('status','queue.status_id','=','status.status_id')
            ->where("queue_id",$getId)
            ->limit(1)
            ->select("queue_id","all_time","status.status_name","status.status_id","carcare_id","queue_order")
            ->get();

		  $status = DB::table('queue')
            ->where('create_date',date('Y-m-d'))
            ->where('carcare_id',$getvalue[0]->carcare_id)
			  ->where('status_id',1)
			  ->where('queue_order',"<",$getId)
			  ->whereNotIn("queue_id",[$getId])
            ->count('*');
            $progress = DB::table('queue')
            ->where('create_date',date('Y-m-d'))
            ->where('status_id',2)
			->whereNotIn("queue_id",[$getId])
				->where('queue_order',"<",$getId)
			->where('carcare_id',$getvalue[0]->carcare_id)
            ->count('*');
            $getvalue[0]->queue=$status;
            $getvalue[0]->progress=$progress;
            return $getvalue;

         }
    }
	public function checkstatus(Request $request){
        $id =$request->input("id");
        $data=null;
        $data=DB::table('queue')
        ->where('member_id',$id)
        ->whereNotIn('status_id', [3])
        ->where('create_date',date('Y-m-d'))
        ->select('status_id')->get();
        if (count($data)>0) {
            return $data[0]->status_id;
        }
        else{
            return;
        }
        return;
    }
	public function checkqueue(Request $request){
        $id =$request->input("id");
        $data=null;

            $getId=null;
            $data=DB::table('queue')
            ->where('member_id',$id)
            ->whereNotIn('status_id', [3])
            ->where('create_date',date('Y-m-d'))
            ->select('queue_id','carcare_id')->get();
            if (count($data)>0) {
                $getId = $data[0]->queue_id;

            }

        $getvalue=DB::table('queue')
            ->leftJoin('status','queue.status_id','=','status.status_id')
            ->where("queue_id",$getId)
            ->limit(1)
            ->select("queue_id","all_time","status.status_name","status.status_id","carcare_id","queue_order")
            ->get();

		  $status = DB::table('queue')
            ->where('create_date',date('Y-m-d'))
            ->where('carcare_id',$getvalue[0]->carcare_id)
			  ->where('status_id',1)
			  ->where('queue_order',"<",$getId)
			  ->whereNotIn("queue_id",[$getId])
            ->count('*');
            $progress = DB::table('queue')
            ->where('create_date',date('Y-m-d'))
            ->where('status_id',2)
			->whereNotIn("queue_id",[$getId])
				->where('queue_order',"<",$getId)
			->where('carcare_id',$getvalue[0]->carcare_id)
            ->count('*');
            $getvalue[0]->queue=$status;
            $getvalue[0]->progress=$progress;
            return $getvalue;
    }
	 public function updatequeue(Request $request){
        $id = $request->input("qid");
        $statusId = $request->input("statusid");
        $datas=DB::table('queue')
        ->where('queue_id',$id)
        ->update(['status_id'=>$statusId]);

        $data=DB::table('queue')
        ->where('queue_id',$id)
        ->where('status_id',3)
        ->where('create_date',date('Y-m-d'))
        ->select('status_id')->get();
        if (count($data)>0) {
             return $data[0]->status_id;
        }
        else{
            return;
        }
        return $datas;
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
