<?php 
session_start()
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Theme Made By www.w3schools.com - No Copyright -->
  <title>Wikipedia Olympics Analysis</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
        body {
            font: 400 15px Lato, sans-serif;
            line-height: 1.8;
            color: #818181;
        }
        h2 {
            font-size: 24px;
            
            color: #303030;
            font-weight: 600;
            margin-bottom: 30px;
        }
        h4 {
            font-size: 19px;
            line-height: 1.375em;
            color: #303030;
            font-weight: 400;
            margin-bottom: 30px;
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
        .jumbotron {
            background-color: #fff;
            color: #000;
            padding: 0px 250px 0 250px;
            font-family: Montserrat, sans-serif;
        }
        .container-fluid {
            padding: 60px 50px;
        }
        
        .item h4 {
            font-size: 19px;
            line-height: 1.375em;
            font-weight: 400;
            font-style: italic;
            margin: 70px 0;
        }
        .item span {
            font-style: normal;
        }
        .panel {
            
            border-radius:0 !important;
            transition: box-shadow 0.5s;
                padding-bottom:60px;
        }
        .panel:hover {
            box-shadow: 5px 0px 40px rgba(0,0,0, .2);
        }
        
        .panel-heading {
            color: #fff !important;
            background-color: #f4511e !important;
            padding: 25px;
            border-bottom: 1px solid transparent;
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }
        

       @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
    <script>
        $(document).ready(function(){
            $("#submit").click(function(event){
            
                var url = $("#scrap_url").val();
                url = url.trim();
                $('#stgSuccess').html("");
                $('#stgError').html("");
                $('#showData').hide();
                function now() { return (new Date).getTime(); }

                function millisToMinutesAndSeconds(millis) {
                var minutes = Math.floor(millis / 60000);
                var seconds = ((millis % 60000) / 1000).toFixed(0);
                return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
                }
               
                if(url == "" || url== null){
               
                    $('#stgError').html("Please insert a link and try again ");
                    return false;
                }
                
                if(url.search("https://en.wikipedia.org/wiki/") != -1){
                    if(url.search(/olympics_medal_table/i) != -1){
                        var dataString = 'action=save&url='+url;
                        var before = now();
                        $(".loader").show();
                        $.ajax({
                            type: "POST",
                            url: "scrap_data.php",
                            data: dataString,
                            success: function(result) {   
                                $(".loader").hide();                         
                                result = JSON.parse(result);    
                                var elapsed = now() - before;                        
                                $('#stgSuccess').html(result.msg+ ' in  "'+ millisToMinutesAndSeconds(elapsed)+ ' Seconds "');
                                $('#showData').show();
                                $('#showData').attr("href", "visual_data.php?header="+result.header);
                            
                            },
                            error: function(result){
                                $(".loader").hide();
                                $('#showData').hide();
                                $('#showData').attr("href","");
                                
                                $('#stgError').html("Opps...  Server Error.<br> Please insert correct link and try again ");

                            }
                        });
                    }else{
                        $('#stgError').html("Please enter a valid wikipedia olympic medal table link and try again");
                        return false;
                    }
                   
                }else{
                   
                    $('#stgError').html("Please enter a wikipedia link and try again ");
                    return false;
                }
                
                
            });

        });
  
  </script>
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

    <!-- Container (Pricing Section) -->
    <div  class="container">
    <div class="text-center">
        <h2>Wikipedia Olympics Analysis</h2>
        
    </div>
    <div class="row ">
        
        <div class="col-sm-6 col-xs-12 col-md-offset-3">
            <div class="panel panel-default ">
                
                <div class="row">
                    <div class="item text-center">
                        <label style="margin-top:20px">
                        <img src="assets/img/073e750081760206230ba18a5aa24c81.png" alt="" style="height: 250px; width: 250px;"> <br> <br>
                        
                        
                        </label>
                       
                    </div>
                    <div class="col-md-6 col-md-offset-5">
                     <div class="loader "></div>
                    </div>
                    
                    <div class="col-md-8 col-md-offset-2">                           
                        
                        <form method="" action>
                            <div class="form-group col-sm-10">
                                
                                <input type="text" id="scrap_url" name="url" class="form-control"  placeholder="Enter the link/URL to scrape the data" required>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="submit" class="btn btn-primary">Submit</button>
                            </div>
                                                     
                            
                        </form>
                        <br>
                    </div>
                    
                    <div class="col-md-8 col-md-offset-2">  
                        <div class=" col-sm-12" id="stgError" style="color: red; font-size: 15px;"></div>
                        
                        <div class=" col-sm-12" id="stgSuccess" style="color: green; font-size: 15px; "></div>
                        <div class="col-sm-12" style="text-align:center">
                        <br>
                             <a  href=" " id="showData" style="display:none;"><button  class="btn btn-success" >Visualize Data</button></a>
                       
                        </div>
                        
                        
                        <hr>
                                               
                    </div>
                </div>        
            </div>      
        </div>       
        
    </div>

</body>
</html>
