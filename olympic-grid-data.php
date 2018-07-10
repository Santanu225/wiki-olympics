<?php

    include('db.php');
    error_reporting(0);  
    
    $conn = connect();
        
   
    /* Database connection end */
    $header ='';
    if($_GET != null){
        $header= $_GET['header'];
    }
    
    // storing  request (ie, get/post) global array to a variable  
    $requestData= $_REQUEST;


    $columns = array( 
    // datatable column index  => database column name
         
        0 => 'rank',
        1=> 'country',
        2=>'gold',
        3=>'silver',
        4=>'bronze',
        5=>'total'
    );

    // getting total number records without any search
    $sql = "SELECT rank, country,gold,silver,bronze,total ";
    $sql.=" FROM olympic_data WHERE header ='".$header."'";
    //print_r($sql);
    $query=mysqli_query($conn, $sql) or die("sensor-grid-data.php: get date");
    $totalData = mysqli_num_rows($query);
    
    $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

    $sql = "SELECT rank,flag_url,noc,country,gold,silver,bronze,total ";
    $sql.=" FROM olympic_data WHERE header = '".$header."'";
    if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
        $sql.=" AND ( rank LIKE '".$requestData['search']['value']."' ";    
        $sql.=" OR country LIKE '".$requestData['search']['value']."' ";    
        $sql.=" OR gold LIKE '".$requestData['search']['value']."' ";
        $sql.=" OR silver LIKE '".$requestData['search']['value']."' ";
        $sql.=" OR bronze LIKE '".$requestData['search']['value']."' ";
        $sql.=" OR total LIKE '".$requestData['search']['value']."' )";
        
        
       //print_r($sql);exit;
    }
    //print_r($sql);exit;
    $query=mysqli_query($conn, $sql) or die("sensor-grid-data.php: get sensor");
    $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
    $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
    /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
    $query=mysqli_query($conn, $sql) or die("sensor-grid-data.php: get employees");


    $data = array();
    while( $row=mysqli_fetch_array($query,MYSQLI_ASSOC) ) {  // preparing an array
        $nestedData=array(); 
        
        $nestedData[] = $row["rank"];
        $nestedData[] = $row["country"]." ".$row['noc'];
        $nestedData[] = $row["gold"];
        $nestedData[] = $row["silver"];
        $nestedData[] = $row["bronze"];
        $nestedData[] = $row["total"];
        
        $data[] = $nestedData;
    }

    $json_data = array(
        "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        "recordsTotal"    => intval( $totalData ),  // total number of records
        "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "data"            => $data   // total data array
    );

    echo json_encode($json_data);  // send data as json format
?>