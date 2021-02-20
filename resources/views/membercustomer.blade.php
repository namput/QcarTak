@extends('layouts.master')
@section('membercustomer', 'active')
@section('sectionmenu')

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        .pagination>li {
            display: inline;
            padding:0px !important;
            margin:0px !important;
            border:none !important;
            }
            .modal-backdrop {
              z-index: -1 !important;
            }
            /*
            Fix to show in full screen demo
            */
            iframe
            {
                height:700px !important;
            }

            .btn {
            display: inline-block;
            padding: 6px 12px !important;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            }

            .btn-primary {
            color: #fff !important;
            background: #428bca !important;
            border-color: #357ebd !important;
            box-shadow:none !important;
            }
            .btn-danger {
            color: #fff !important;
            background: #d9534f !important;
            border-color: #d9534f !important;
            box-shadow:none !important;
            }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{
            color: #333 !important;
    border: 1px solid #979797;
    background-color: white;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fff), color-stop(100%, #dcdcdc));
    background: -webkit-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -moz-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -ms-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -o-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: linear-gradient(to bottom, #fff 0%, #dcdcdc 100%);
        }
    </script>
@if ($message = Session::get('success'))
<div class="alert alert-success">
<p>{{ $message }}</p>
</div>
@endif
<!-- DATA TABLE-->
            <section class="p-t-20">
                <div class="container" style="margin-top: 10%;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-data__tool">
                                <div class="table-data__tool-left">
                                    <h3 class="title-5">สมาชิกทั้งหมด</h3>
                                </div>
                                <div class="table-data__tool-right">
                                    <button class="au-btn au-btn-icon au-btn--green au-btn--small" onclick="add()">
                                        <i class="zmdi zmdi-plus"></i>เพิ่มสมาชิก</button>
                                </div>

                            </div>
                            @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                            <p>{{ $message }}</p>
                            </div>
                            @endif
                            <div class="table-responsive table-responsive-data2">

                                <table class="table table-bordered table-hover" id="membercustomer">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; width: 10%;">ID</th>
                                            <th style="text-align: center; width: 30%;">ชื่อ</th>
                                            <th style="text-align: center; width: 20%;">เบอร์</th>
                                            <th style="text-align: center; width: 15%;">ประเภทสมาชิก</th>
                                            <th style="text-align: center; width: 15%;" >ชื่อคาร์แคร์</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>


                            <!-- modal scroll -->
                            <div class="modal fade" id="scrollmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true" >
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header " id="titlemain"><i class="fa fa-plus" aria-hidden="true"></i> หน้าเพิ่มสมาชิก</div>
                                                    <div class="card-body">
                                                        <div class="card-title">
                                                            <h3 class="text-center title-2" id="titlemember">เพิ่มข้อมูล</h3>
                                                        </div>
                                                        <hr>
                                                        <form action="#" method="post" novalidate="novalidate" id="memberForm">

                                                            <input type="hidden" name="member_id" id="member_id" value=null>
                                                            <div class="form-group">
                                                                <label for="cc-name" class="control-label mb-1">ชื่อ</label>
                                                                <input id="member_name" name="member_name" type="text" class="form-control" placeholder="Name" aria-required="true" aria-invalid="false" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cc-tel" class="control-label mb-1">เบอร์</label>
                                                                <input id="member_phone" name="member_phone" type="tel" class="form-control" placeholder="Tel" aria-required="true" aria-invalid="false" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                    <label for="password-input" class=" form-control-label">Password</label>
                                                                    <input type="password" id="member_pass" name="member_pass" placeholder="Password" class="form-control">
                                                            </div>
                                                            <div class="row form-group">
                                                                <div class="col col-md-3">
                                                                    <label for="select" class=" form-control-label">ประเภทสมาชิก</label>
                                                                </div>
                                                                <div class="col-12 col-md-9">
                                                                    <select name="member_type" id="member_type" class="form-control">
                                                                        <option value="0">เลือกประเภทสมาชิก <?php echo (empty($a)) ? '' : 'No'; ?></option>
                                                                        <option value="1">ผู้ดูแลระบบ</option>
                                                                        <option value="2">ผู้ใช้คาร์แคร์</option>
                                                                        <option value="3">ผู้ใช้ทั่วไป</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <button id="btn-save" type="submit" class="btn btn-lg btn-info btn-block" >
                                                                    <i class="fa fa-user fa-lg"></i>&nbsp;
                                                                    <span id="adduser">เพิ่มสมาชิก</span>
                                                                    <span id="sending" style="display:none;"></span>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal scroll -->

                        </div>
                    </div>
                </div>
            </section>
                <script type="text/javascript">
                    $(document).ready( function () {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                       $('#membercustomer').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('membercustomer') }}",
                            columns: [
                                { data: 'member_id', name: 'member_id'},
                                { data: 'member_name', name: 'member_name' },
                                { data: 'member_phone', name: 'member_phone' },
                                {data: 'action2', name: 'action'},
                                {data: 'action3', name: 'action'},
                                {data: 'action', name: 'action', orderable: false,},
                                ],
                            order: [[0, 'desc']]
                        });

                    });
                    {{--  $( "#member-button" ).click(function() {
                        $('#scrollmodal').modal('hide');
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'เพิ่มข้อมูลสำเร็จ',
                            showConfirmButton: false,
                            timer: 1500
                          })
                      });  --}}
                    function add(){
                        $('#memberForm').trigger("reset");
                        $('#titlemember').html("เพิ่มรายการสมาชิก");
                        $('#titlemain').html("<i class='fa fa-plus' aria-hidden='true'></i>&nbsp แก้ไขข้อมูลสมาชิก");
                        $('#scrollmodal').modal('show');
                        $('#member_id').val('');
                    }

                    function editFunc(member_id){

                        $.ajax({
                        type:"POST",
                        url: "{{ url('edit-member') }}",
                        data: { member_id: member_id },
                        dataType: 'json',
                        success: function(res){
                            $('#titlemember').html("แก้ไขข้อมูลสมาชิก");
                            $('#titlemain').html("<i class='fa fa-pencil-square-o' aria-hidden='true'></i>&nbsp แก้ไขข้อมูลสมาชิก");
                            $('#scrollmodal').modal('show');
                            $('#member_id').val(res.member_id);
                            $('#member_name').val(res.member_name);
                            $('#member_phone').val(res.member_phone);
                            $('#member_pass').val(res.member_pass);
                            $('#member_type').val(res.member_type);
                            }
                            });
                        }

                    function deleteFunc(member_id){
                        Swal.fire({
                            title: 'คุณแน่ใจใช่ไหม?',
                            text: "คุณต้องการลบผู้ใช้รายนี้จริงหรือไม่!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ใช่ ลบเลย!'
                          }).then((result) => {
                            if (result.isConfirmed) {

                               // ajax
                               $.ajax({
                                   type:"POST",
                                   url: "{{ url('delete-member') }}",
                                   data: { member_id: member_id },
                                   dataType: 'json',
                                   success: function(res){
                                       var oTable = $('#membercustomer').dataTable();
                                       oTable.fnDraw(false);
                                       successFunction(1);

                                   },
                                   error: function (jqXHR, textStatus, errorThrown) {
                                        errorFunction(1);
                                    }
                               });

                            }
                          })
                    }

                    $('#memberForm').submit(function(e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        $.ajax({
                        type:'POST',
                        url: "{{ url('store-member')}}",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'บันทึกข้อมูลสำเร็จ',
                                showConfirmButton: false,
                                timer: 1500
                              })
                            $("#scrollmodal").modal('hide');
                            var oTable = $('#membercustomer').dataTable();
                            oTable.fnDraw(false);
                            $("#btn-save").html('ส่งแล้ว');
                            $("#btn-save"). attr("disabled", false);
                        },
                        error: function(data){
                        console.log(data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'ไม่สามารถบันทึกข้อมูลได้!',
                            footer: 'อาจป้อนข้อมูลไม่ครบ'
                          });
                        }
                    });
                });
                function successFunction(data){
                    Swal.fire(
                            'ลบเรียบร้อย!',
                            'คุณได้ลบสมาชิกออกแล้ว',
                            'success'
                          )
                }
                function errorFunction(data){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'เกิดข้อผิดพลาด! ไม่สามารถลบสมาชิกได้',
                        footer: '<a href>ทำไมฉันถึงพบปัญหานี้?</a>'
                      })
                }
            </script>


            <!-- END DATA TABLE-->
            @endsection
