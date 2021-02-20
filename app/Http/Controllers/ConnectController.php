<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ConnectController extends Controller

{
    public function menu(Request $request){
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
            return $user;
           return view('menu',['data'=>$user]);

        }
        else{
            return false;
        }

    }

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
            ->insert([
                'member_phone'=>$phone,
                'member_pass'=>$pass,
                'member_type'=>$type
            ]);

            return true;
        }
        }
        else{
            return false;
        }
    }

    public function showprofile(Request $request){
        $id=$request->input('id');
        $datas=DB::table('member')
        ->leftJoin('car_member','member.member_id','=','car_member.member_id')
        ->where('member.member_id',$id)
        ->select([
            'member_name'=>'member.member_name',
            'car_member_id'=>'car_member.car_member_id',
            'car_service_id' =>'car_member.car_service_id',
            'car_member_number' =>'car_member.car_member_number',
            'color_id' =>'car_member.color_id',
            'car_member_brand' =>'car_member.car_member_brand'
            ])->get();

            return $datas;
    }

    public function history(Request $request){
        $id=$request->input('id');
        $datas=DB::table('member')
        ->leftJoin('queue','member.member_id','=','queue.member_id')
        ->leftJoin('carcare','queue.carcare_id','=','carcare.carcare_id')
        ->where('member.member_id',$id)
        ->select([
            'carcare_name' => "carcare.carcare_name",
            'create_date' => "queue.create_date"
        ])->get();
        return $datas;
    }

    public function listcarcare(Request $request){
        $id=$request->input('id');
        $datas=DB::table('carcare')
        ->leftJoin('queue','carcare.carcare_id','=','queue.carcare_id')
        ->select([
            'carcare_id' =>"carcare.carcare_id",
            'carcare_name' =>"carcare.carcare_name",
            'score' =>"queue.score",
            'all_time' => "queue.all_time",
            'status_id' => "queue.status_id"
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
                    $num=0;
                    $score=0;
                    $status=0;
                    $name=null;
                    $alltime=0;
                        foreach($data as $items){

                               // เอาชุดข้อมูลมาวนลูปใหม่
                                if ($item == $items['carcare_id']) {
                                $num++;
                                $score+= (int)$items['score'];

                                if (empty($name)) {
                                    $name=$items['carcare_name'];
                                }
                                if ($items['status_id']!='3') {
                                    $status++;
                                    $alltime+=$items['all_time'];
                                }
                            }

                        }
                        $carcare[$i][$item]['id']=$item;
                        $carcare[$i][$item]['name']=$name;
                        $carcare[$i][$item]['num']=$num;
                        $carcare[$i][$item]['score']=$score/$num;
                        $carcare[$i][$item]['alltime']=$alltime;
                        //$carcare[$i][$item]['status']=$status;
                }
                $i++;
            }
            $i=0;
            $datass=[];
            foreach ($carcare as $key => $value) {
                if ($key) {
                    foreach ($value as $k => $v) {

                       $datass[]= $v;
                    }
                }
            }
            $b=[];
            foreach ($datass as $value) {
            $test=[];
                foreach ($value as $key => $v) {
                   $test[]=$v;
                }
                $b[]=$test;
            }
            $c["data"]=$b;
              return $c;
    }
    public function car_member(Request $request){
        $id=$request->input('id');

        $datas=DB::table('car_member')
        ->leftJoin('car_service','car_member.car_service_id','=','car_service.car_service_id')
        ->leftJoin('color','car_member.color_id','=','color.color_id')
        ->where('member_id',$id)
        ->get();
        return $datas;

    }
    public function attribute(Request $request){
        $id=$request->input('id_member');
        $id_carcare=$request->input('id_carcare');
        $id_member_car=$request->input('id_member_car');
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
    public function conform(Request $request){
        $id=$request->input('id_member');
        $id_member_car=$request->input('id_member_car');
        $id_carcare=$request->input('id_carcare');
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
            foreach ($request->input('sw') as $value) {
                DB::table('queue_list')->insert([
                    'list_id'=>$value,
                    'queue_id'=>$saves
                ]);

            }
              $data=DB::table('attribute')
              ->whereIn('attribute.attribute_id',$request->input('sw'))
              ->select($size='attribute.'.$size)
              ->get();
              $time=0;
              foreach ($data as  $value) {
                  foreach ($value as $v) {
                    $time+=$v;
                  }

              }
              $datas=DB::table('queue')
              ->where('queue_id',$saves)
              ->update(['all_time',$time]);

            return $datas;
        }
    }
}
