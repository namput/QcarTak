<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use Yajra\DataTables\Facades\DataTables;


class DashboardController extends Controller
{
  public function checklogin(Request $request){
      $tel = $request->post("tel");
      return $tel;
  }
  public function demo(Request $request){
    if(request()->ajax()) {
        $data =Member::select('*')->get();
        return DataTables::of($data);
    }
    return view('membercustomer');
}
  public function getlistcount(Request $request){
    if (session()->has('user')) {
        $allmember =DB::table('member')->whereNotIn('member_type',['1'])->count();
        $member_type2 =DB::table('member')->where('member_type','2')->count();
        $member_type3 =DB::table('member')->where('member_type','3')->count();
        $numcancel =DB::table('queue')->where('status_id','4')->count();
        $numsuccess =DB::table('queue')->where('status_id','3')->count();
        $carcare =DB::table('carcare')->count();
        $data=[];
        $data['allmember'] = $allmember;
        $data['member_type2'] = $member_type2;
        $data['member_type3'] = $member_type3;
        $data['carcare'] = $carcare;
        $data['numcancel'] = $numcancel;
        $data['numsuccess'] = $numsuccess;
        return $data;

    }
    return redirect('login');
  }
  public function index()
  {
        if (session()->has('user')) {
        if(request()->ajax()) {
            $data =DB::table('member')
            ->leftJoin('member_type','member.member_type','=','member_type.member_type')
            ->leftJoin('carcare','member.carcare_id','=','carcare.carcare_id')
            ->select([
                'member_id'=>'member.member_id',
                'member_name'=>'member.member_name',
                'member_type'=>'member.member_type',
                'member_pass'=>'member.member_pass',
                'member_type_name'=>'member_type_name',
                'member_phone'=>'member.member_phone',
                'carcare_id'=>'carcare.carcare_id',
                'carcare_name'=>'carcare.carcare_name'
                ])
            ->get();
        // return DataTables::of($data);
            return datatables()->of($data)
            ->addColumn('action2', function($data){
                $buttons='';
                if ($data->member_type==3) {
                    $buttons='<a href="javascript:void(0)" class="btn btn-primary" id="membertype" >'.$data->member_type_name.'</a>';
                }else if($data->member_type==2){
                    $buttons='<a href="javascript:void(0)" class="btn btn-success" id="membertype">'.$data->member_type_name.'</a>';
                }else if($data->member_type==1){
                    $buttons='<a href="javascript:void(0)" class="btn btn-warning" id="membertype">'.$data->member_type_name.'</a>';
                }
                return $buttons;

            })->addColumn('action3', function($data){
                $buttons='';
                if ($data->carcare_id) {
                    $buttons='<a href="javascript:void(0)" class="btn btn-primary" id="membertype" >'.$data->carcare_name.'</a>';
                }
                return $buttons;

            })
            ->addColumn('action', 'listaction')
            ->rawColumns(['action','action2','action3'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('membercustomer');
    }
    return redirect('login');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      $member_id = $request->member_id;



        $company  =  DB::table('member')->updateOrInsert(
            [
             'member_id' => $member_id
            ],
            [
            'member_name' => $request->member_name,
            'member_phone' => $request->member_phone,
            'member_type' => $request->member_type,
            'member_pass' =>$request->member_pass,
            ]);

      return Response()->json($company);

  }

  public function edit(Request $request)
  {
      $where = array('member_id' => $request->member_id);
      $company  = DB::table('member')->where($where)->first();

      return Response()->json($company);
  }

  public function destroy(Request $request)
  {
      $company = DB::table('member')->where('member_id',$request->member_id)->delete();

      return Response()->json($company);
  }

}
