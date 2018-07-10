<?php
     session_start();
    include('db.php');
    error_reporting(0);  
      
     $conn = connect();

     $sql="SELECT DISTINCT header FROM olympic_data";
    
     $res = mysqli_query($conn,$sql);
     

    $row=mysqli_fetch_array(mysqli_query($conn,$sql),MYSQLI_ASSOC);
     
    if($_GET != null){
       
        $tag_name = $_GET['header'];
        
    }else{
        $tag_name = $row['header'];
    }
     
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Theme Made By www.w3schools.com - No Copyright -->
  <title>Wiki Olympics Analysis</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/series-label.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <style>
  body {
      //font: 400 15px Lato, sans-serif;
      line-height: 1.8;
      color: #818181;
  }
  
 
h4 {
      font-size: 19px;
      line-height: 1.375em;
      color: #303030;
      font-weight: 400;
      margin-bottom: 10px;
      padding-left:20px;
}  

  
h5 {
      font-size: 14px;
      line-height: 1.375em;
      color: #000;
      font-weight: 400;
      padding-left:20px;
} 
.loader {
    display:none;
    margin-bottom: 10px;
    border: 8px solid #f3f3f3;
    border-radius: 50%;
    border-top: 8px solid #3498db;
    width: 50px;
    height: 50px;
    -webkit-animation: spin 2s linear infinite; /* Safari */
    animation: spin 2s linear infinite;
}

.imgDiv {
    background-color: #fff;
    color: #000;
    padding:20px 60px 0px 0px;
    font-family: Montserrat, sans-serif;
}
.jumbotron {
    background-color: #fff;
    color: #000;
    padding: 0px 10px 0 250px;
    font-family: Montserrat, sans-serif;
}
  
.Middle,
.Left {
    overflow: auto;
    height: auto;
    //padding: 1rem;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: none;
}
.Left::-webkit-scrollbar,
.Middle::-webkit-scrollbar {
    display: none;
}
.Middle {
    flex: 1;
}

.Container {
    display: flex;
    overflow: hidden;
    height: calc(100vh - 100px);
    position: relative;
    width: 100%;
}
.container-fluid {
    padding: 5px 60px 10px 60px;
}  
 
.panel {
    border: 1px solid #f4511e; 
    border-radius:0 !important;
    transition: box-shadow 0.5s;
}
.panel:hover {
    box-shadow: 5px 0px 40px rgba(0,0,0, .2);
}


@media screen and (max-width: 768px) {
    .col-sm-4 {
    text-align: center;
    margin: 25px 0;
    }
    .btn-lg {
        width: 100%;
        margin-bottom: 35px;
    }
}
@media screen and (max-width: 480px) {
    .logo {
        font-size: 150px;
    }
}
  </style>
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">



<div class="imgDiv text-center">

    <a class="navbar-brand" href="index.php"><img src="assets/img/Olympic-logo.png" alt="" style="height: 60px; width: 100px; margin-left:20px"></a>
    <h2><?php 
    $yearheader = substr($tag_name,0,11);
    $yearheader =str_replace("_"," ",$yearheader) ;
    echo   $yearheader; ?> Olympics Analysis</h2><br>
</div>
<div class="row Container">
    <div class="col-sm-2 Left" id="olympicList" style="margin-left:10px">
        <h4>Olympics List</h4>
        
        <ul>    
            <?php  while( $row = mysqli_fetch_array($res,MYSQLI_ASSOC) ) {  

                echo '<li> <a href="visual_data.php?header='.$row['header'].'">'.substr($row['header'],0,4).' Olympics</a></li>';
            }
            ?> 
        </ul>
        <br>
        <h5><i class="fa fa-line-chart"></i><a class="nav-link js-scroll-trigger" href="#top">&nbsp;Graphical Data</a></h5>
        <h5><i class="fa fa-table"></i>&nbsp;<a class="nav-link js-scroll-trigger" href="#dataTable_container">&nbsp;Tabular Data</a></h5>
        
    </div>
    <div class="col-sm-9 Middle" >
        <div class="col-sm-12" id="top" style="margin-bottom:5px; padding-top:5px;">
            <form method="" action="" >
                <div class=" row">
                    <div class="col-sm-2">
                    
                        <input type="hidden" name="action" class="form-control" value="save" >
                    </div>
                    <div class="col-sm-6">
                        <input type="text" id="url" name="url" class="form-control"  placeholder="Enter a new URL to scrape more data. " required> 
                    </div>  
                    <div class=" col-sm-1 ">
                    <button type="button" id="submit" class="btn btn-danger">Submit</button>
                    
                    </div>
                    <div class=" col-sm-1 ">
                        <a href="index.php" class="btn btn-primary" >Back</a>
                    </div>
                    
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-10">
                        <div class="col-sm-12 " id="stgError" style="color: red; font-size: 15px; float:left"></div>
                        <div class="col-sm-12 " id="stgSuccess" style="color: green; font-size: 15px; float:left"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-12 " style="margin-bottom:20px">
                <input type="hidden" id="header_url" class="form-control" value="<?=$tag_name?>" >
                <div class="col-sm-12">

               
                    <div id="lineChart_container"></div>
                    <hr>
                </div>                  
                <div class="col-sm-12">
                    <h4 style="text-align: center; font-weight: 400; font-family: 'Roboto Condensed',Arial,sans-serif;"> <?=$yearheader?> Olympics medal table </h4>
                    <br>
                    <div id="dataTable_container">
                        <table id="sensor-grid"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%" >
                            <thead>
                            <tr>
                                    <th>Rank</th>
                                    <th>Country</th>
                                    <th>Gold</th>
                                    <th>Silver</th>
                                    <th>Bronze</th>
                                    <th>Total</th>
                            </tr>
                            </thead>
                        </table>
                    </div>            
                </div>
  
            </div>
    
    </div>
    <div class="col-sm-1 Left">
    
    </div>
   
</div>


<script>
$(document).ready(function(){  

    $("#submit").click(function(event){
        $('#stgSuccess').html("");
        $('#stgError').html("");
       var url = $("#url").val();
       url = url.trim();
       if(url == "" || url== null){
            
        $('#stgError').html("Please insert a link and try again ");
            return false;
        }
        if(url.search("https://en.wikipedia.org/wiki/") != -1){
            if(url.search(/olympics_medal_table/i) != -1){
                var dataString = 'action=save&url='+url;
                $.ajax({
                    type: "POST",
                    url: "scrap_data.php",
                    data: dataString,
                    success: function(result) {
                    console.log(result);
                    result = JSON.parse(result);
                    $('#stgError').html("");
                        

                        window.location.href = "visual_data.php?header="+url.substring(30);
                    
                    
                    
                    },
                    error: function(result){
                    
                        $('#stgError').html("Opps.. Server Error. Please insert correct link and try again ");

                    }
                });
            }else{
                $('#stgError').html("Please enter a valid wikipedia olympic medal table link and try again ");
                return false;
            }
            
        }else{
            $('#stgSuccess').html("");
                   
            $('#stgError').html("Please enter a wikipedia link and try again ");
            return false;
        }
      
     });
       
    var header = $("#header_url").val();

    if(header != null){
        var dataString = 'action=show&url=https://en.wikipedia.org/wiki/'+header;
        $.ajax({
            type: "POST",
            url: "scrap_data.php",
            data: dataString,
            success: function(result) {
                console.log(result);
            result = JSON.parse(result);
            
            lineChart(result.value);
            }
        });

            var dataTable = $('#sensor-grid').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"olympic-grid-data.php?header="+header, // json datasource
                type: "post",  // method  , by default get
                error: function(){  // error handling
                $(".sensor-grid-error").html("");
                $("#sensor-grid").append('<tbody class="sensor-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employee-grid_processing").css("display","none");
                
                }
            }
        } );
        
        header = header.substring(0,11).replace("_"," ");

    }else{
        $(".Right").html(" ");
        $("#stgError").html("<label>Please Enter an URL</label>");

    }
    
    function lineChart(values){
        
        Highcharts.chart('lineChart_container', {

            title: {
                text: header+" Olympics Medal Table Top 10",
            },

            xAxis: {
                categories: values.labels,
                title: {
                    text: 'Country'
                }, labels: {
                    rotation: -45
                }
            },
            yAxis: {
                title: {
                    text: 'Medal Count'
                }
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },

            series: [{
                name: 'Gold',
                color: '#D4AF37',
                data: values.gold
            }, {
                name: 'Silver',
                color: '#C0C0C0',
                data: values.silver
            }, {
                name: 'Bronze',
                color: '#CD7F32',
                data: values.bronze
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
    }
    
    })
</script>

</body>
</html>

