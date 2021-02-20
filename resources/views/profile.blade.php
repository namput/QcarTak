@extends('layouts.master')
@section('Dashboard', 'active')
@section('sectionmenu')


<div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <!-- WELCOME-->
            <section class="welcome p-t-10">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="title-4">Welcome back
                                <span>{{ session('username') }}!</span>
                            </h1>
                            <hr class="line-seprate">
                        </div>
                    </div>
                </div>
            </section>
            <!-- END WELCOME-->
                        <!-- STATISTIC-->
                        <section class="statistic statistic2">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 col-lg-3">
                                        <div class="statistic__item statistic__item--green">
                                            <h2 class="number" ><span id="countmember">0</span> </h2>
                                            <span class="desc">สมาชิกทั้งหมด</span>
                                            <div class="icon">
                                                <i class="zmdi zmdi-account-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="statistic__item statistic__item--orange">
                                            <h2 class="number"><span id="countcarcare">0</span></h2>
                                            <span class="desc">ร้านคาร์แคร์</span>
                                            <div class="icon">
                                                <i class="zmdi zmdi-car-wash"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="statistic__item statistic__item--blue">
                                            <h2 class="number"><span id="member_type2">0</span></h2>
                                            <span class="desc">สมาชิกร้านคาร์แคร์</span>
                                            <div class="icon">
                                                <i class="zmdi zmdi-account-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="statistic__item statistic__item--red">
                                            <h2 class="number"><span id="member_type3">0</span></h2>
                                            <span class="desc">สมาชิกลูกค้า</span>
                                            <div class="icon">
                                                <i class="zmdi zmdi-account-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- END STATISTIC-->
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="au-card chart-percent-card">
                                    <div class="au-card-inner">
                                        <h3 class="title-2 tm-b-5">กราฟแสดงสถานะที่ทำรายการสมบูรณ์</h3>

                                        <!-- Styles -->
                                        <style>
                                        #chartdiv {
                                          width: 100%;
                                          height: 500px;
                                        }

                                        </style>

                                        <!-- Chart code -->
                                        <script>
                                        am4core.ready(function() {

                                        // Themes begin
                                        am4core.useTheme(am4themes_material);
                                        am4core.useTheme(am4themes_animated);
                                        // Themes end

                                        // Create chart instance
                                        var chart = am4core.create("chartdiv", am4charts.PieChart);
                                            var allmember =0;
                                        // Add data
                                        chart.data = [ {
                                          "country": "ยกเลิก",
                                          "litres": allmember
                                        }, {
                                          "country": "ล้างเสร็จ",
                                          "litres": 5
                                        }];

                                        // Set inner radius
                                        chart.innerRadius = am4core.percent(50);

                                        // Add and configure Series
                                        var pieSeries = chart.series.push(new am4charts.PieSeries());
                                        pieSeries.dataFields.value = "litres";
                                        pieSeries.dataFields.category = "country";
                                        pieSeries.slices.template.stroke = am4core.color("#fff");
                                        pieSeries.slices.template.strokeWidth = 2;
                                        pieSeries.slices.template.strokeOpacity = 1;

                                        // This creates initial animation
                                        pieSeries.hiddenState.properties.opacity = 1;
                                        pieSeries.hiddenState.properties.endAngle = -90;
                                        pieSeries.hiddenState.properties.startAngle = -90;

                                        }); // end am4core.ready()
                                        </script>

                                        <!-- HTML -->
                                        <div id="chartdiv"></div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <script type="text/javascript">
                let _token   = $('meta[name="csrf-token"]').attr('content');

                $(document).ready(function(){
                    $.ajax({
                        url: "{{ url('getlistcount') }}",
                        type:"GET",
                        data:{
                          _token: _token
                        },
                        success:function(response){
                            $('#countmember').text(response.allmember);
                            $('#countcarcare').text(response.carcare);
                            $('#member_type2').text(response.member_type2);
                            $('#member_type3').text(response.member_type3);
                            numcancel = response.numcancel;
                            numsuccess = response.numsuccess;
                            am4core.ready(function() {

                                // Themes begin
                                am4core.useTheme(am4themes_material);
                                am4core.useTheme(am4themes_animated);
                                // Themes end

                                // Create chart instance
                                var chart = am4core.create("chartdiv", am4charts.PieChart);
                                    var allmember =0;
                                // Add data
                                chart.data = [ {
                                  "country": "ยกเลิก",
                                  "litres": numcancel
                                }, {
                                  "country": "ล้างเสร็จ",
                                  "litres": numsuccess
                                }];

                                // Set inner radius
                                chart.innerRadius = am4core.percent(50);

                                // Add and configure Series
                                var pieSeries = chart.series.push(new am4charts.PieSeries());
                                pieSeries.dataFields.value = "litres";
                                pieSeries.dataFields.category = "country";
                                pieSeries.slices.template.stroke = am4core.color("#fff");
                                pieSeries.slices.template.strokeWidth = 2;
                                pieSeries.slices.template.strokeOpacity = 1;

                                // This creates initial animation
                                pieSeries.hiddenState.properties.opacity = 1;
                                pieSeries.hiddenState.properties.endAngle = -90;
                                pieSeries.hiddenState.properties.startAngle = -90;

                                }); // end am4core.ready()
                        },
                       });
                });

            </script>

            @endsection
