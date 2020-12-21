<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

</head>
<body>
    <form id="form" action="listcarcare" method="GET" >
        <div class="form-group">
        @csrf

        <input type="number" class="form-control col-md-3" name="id" id="uid" value="{{ $data->member_id }}">
        <input type="button" class="btn btn-dark" name="profile" id="profile" value="โปรไฟล์">
        <input type="button" class="btn btn-dark" name="history" id="history" value="ประวัติ">
        <input type="button" class="btn btn-dark" name="listcarcare" id="listcarcare" value="คาร์แคร์">
        <input type="button" class="btn btn-dark" name="car_member" id="car_member" value="เลือกรถที่จะล้าง">
        <input type="submit" class="btn btn-dark" value="test">
    </div>
    </form>

    <form id="form2" action="attribute" method="POST" >
        <div class="form-group">
        @csrf
        รถที่เลือก<input type="number" name="id_member_car" id="id_member_car" value=1>
        ลูกค้า<input type="number" name="id_member" id="id_member" value="{{ $data->member_id }}">
       ร้านคาร์แคร์ <input type="number" name="id_carcare" id="id_carcare" value="{{ $data->member_id }}">
        <input type="button" class="btn btn-dark" name="attribute" id="attribute" value="เลือกรถ">
        <input type="button" class="btn btn-dark" name="conform_ok" id="conform_ok" value="ยืนยันรายการ">

        <input type="submit" class="btn btn-dark" value="test">
    </div>
    </form>
    <div id="content">รอแสดงข้อมูล</div>
    <table id="example" class="display table" style="width:100%">
        <thead>
            <tr>
                <th>id</th>
                <th>ชื่อ</th>
                <th>จำนวนคิว</th>
                <th>คะแนน</th>
                <th>เวลารวม</th>
            </tr>
        </thead>

    </table>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            // click on button submit
            $("#profile").on('click', function(){
                // send ajax
                $.ajax({
                    url: '{{ url('profile') }}', // url where to submit the request
                    type : "POST", // type of action POST || GET
                    dataType : 'json', // data type
                    data : $("#form").serialize(), // post data || get data
                    success : function(result) {
                        // you can see the result from the console
                        // tab of the developer tools
                        var b='';
                        for(i in result){
                            b+=result[i].member_name+"<br>";
                            b+=result[i].car_member_id+"<br>";
                            b+=result[i].car_service_id+"<br>";
                            b+=result[i].car_member_number+"<br>";
                            b+=result[i].color_id+"<br>";
                            b+=result[i].car_member_brand+"<br>";
                        }
                      $('#content').html(b);
                    },
                    error: function(xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }
                })
            });
            $("#history").on('click', function(){
                // send ajax
                $.ajax({
                    url: '{{ url('history') }}', // url where to submit the request
                    type : "POST", // type of action POST || GET
                    dataType : 'json', // data type
                    data : $("#form").serialize(), // post data || get data
                    success : function(result) {
                        // you can see the result from the console
                        // tab of the developer tools
                        var b='';
                        for(i in result){
                            b+=result[i].carcare_name+"<br>";
                            b+=result[i].create_date+"<br>";
                        }
                      $('#content').html(b);
                    },
                    error: function(xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }
                })
            });
            $("#listcarcare").on('click', function(){

                $('#example').DataTable( {
                    "ajax": 'listcarcare'
                } );
            });
            $("#car_member").on('click', function(){
                // send ajax
                $.ajax({
                    url: '{{ url('car_member') }}', // url where to submit the request
                    type : "POST", // type of action POST || GET
                    dataType : 'json', // data type
                    data : $("#form").serialize(), // post data || get data
                    success : function(result) {
                        // you can see the result from the console
                        // tab of the developer tools
                        var b='';
                        for(i in result){
                            b+=result[i].car_member_id+"<br>";
                            b+=result[i].member_id+"<br>";
                            b+=result[i].car_member_number+"<br>";
                        }

                      $('#content').html(b);
                    },
                    error: function(xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }
                })
            });
            $("#attribute").on('click', function(){
                // send ajax
                $.ajax({
                    url: '{{ url('attribute') }}', // url where to submit the request
                    type : "POST", // type of action POST || GET
                    dataType : 'json', // data type
                    data : $("#form2").serialize(), // post data || get data
                    success : function(result) {
                        // you can see the result from the console
                        // tab of the developer tools


                        var b='<form id="form3" action="conform" method="POST" >@csrf รถที่เลือก<input type="number" name="id_member_car" id="id_member_car" value=1> ลูกค้า<input type="number" name="id_member" id="id_member" value="{{ $data->member_id }}"> ร้านคาร์แคร์ <input type="number" name="id_carcare" id="id_carcare" value="{{ $data->member_id }}">';
                        for(i in result){
                            b+='<div class="custom-control custom-switch">';
                            b+='<input type="checkbox" class="custom-control-input" id="sw['+result[i].attribute_id+']" name=sw[]" value='+result[i].attribute_id+'>';
                            b+='<label class="custom-control-label" for="sw['+result[i].attribute_id+']">'+result[i].attribute_name+result[i].attribute_id+'</label></div>';
                        }
                        b+='<input type="submit" class="btn btn-dark" value="test"></form>';
                      $('#content').html(b);
                    },
                    error: function(xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }
                })
            });
            $("#conform_ok").on('click', function(){

                // send ajax
                $.ajax({
                    url: '{{ url('conform') }}', // url where to submit the request
                    type : "POST", // type of action POST || GET
                    dataType : 'json', // data type
                    data : $("#form3").serialize(), // post data || get data
                    success : function(result) {
                        $('#content').html(result);

                    },
                    error: function(xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }
                })
            });

        });

    </script>
</body>
</html>
