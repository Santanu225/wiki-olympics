<?php
     session_start();
    include("simple_html_dom.php");
    include('db.php');
    
    //error_reporting(0);

    $scrap_url = $_POST['url'];
    $scrap_url =  trim($scrap_url," ");
    $scrap_header = substr($scrap_url,30);
    $msg;
       
    function urlExists($url=NULL)  
    {  
        if($url == NULL) return false;  
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        $data = curl_exec($ch);  
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
        curl_close($ch);  
        if($httpcode>=200 && $httpcode<300){  
            return true;  
        } else {  
            return false;  
        }  
    }
   
     
    function readTable($url){

        $html = file_get_html($url);     
     
        $dataTable = [];              
            
        foreach($html->find('table[class=wikitable sortable plainrowheaders]') as $table){
            
            foreach($table->find('tr') as $key => $trow){
                $tableData =[] ;
                
                if($key > 0 && $key <=20 ){
                    $tableData['rank'] = $trow->find('td',0)->plaintext;                  
                    $tableData['flag_url'] = $trow->find('img',0)->src;                  
                    $tableData['country'] = $trow->find('a[title]',0)->plaintext;
                    $tableData['noc'] = $trow->find('span',0)->plaintext;
                    $tableData['gold'] = $trow->find('td',0)->next_sibling ()->next_sibling ()->plaintext;
                    $tableData['silver'] = $trow->find('td',0)->next_sibling ()->next_sibling ()->next_sibling()->plaintext;
                    $tableData['bronze'] = $trow->last_child ()->prev_sibling ()->plaintext;
                    $tableData['total'] = $trow->last_child ()->plaintext;
                    array_push($dataTable,$tableData);
                }
                        
            }      
        }
               
        return $dataTable;

    } 
  
    function getChartData($header){
        $conn = connect();

        $sql="SELECT country,gold,silver,bronze,total,noc from olympic_data where  header = '".$header."' AND rank > 0 AND rank < 11 ORDER BY rank ASC";
       
        $resultData = mysqli_query($conn,$sql);
        $values = array();
        $values['gold'] = [];
        $values['silver']=[];
        $values['bronze']=[];
        $values['labels']=[];
        while( $row=mysqli_fetch_array($resultData,MYSQLI_ASSOC) ) {  // preparing an array
         
          array_push($values['gold'],(int)$row["gold"]);
          array_push($values['silver'],(int)$row["silver"]);
          array_push($values['bronze'],(int)$row["bronze"]);
          array_push($values['labels'],($row["country"].$row["noc"]));           
            
        }
        return $values;
    }

    if($_POST['action']=="save"){

        if(!urlExists($scrap_url)){
            $msg = "Please insert correct URL and try again";
            header('HTTP/1.1 500 Internal Server Booboo');
            header('Content-Type: application/json; charset=UTF-8');
            $res['msg']=$msg;
            //echo json_encode($res);
            echo(json_encode($res));
                                
        }else{

        $rData = readTable($scrap_url);

        $conn = connect();
        
        $query1="SELECT * from olympic_data where header = '".$scrap_header."'";
        
            $check = mysqli_query($conn,$query1);
           
            $checkrows=mysqli_num_rows($check);
            
            if($checkrows>0) {
                 $msg = "We have successfully completed data scrapping from given link ";
                 
             } else {

                foreach ($rData as $k) {

                    $query = "INSERT INTO olympic_data (header,rank,flag_url,country,noc,gold,silver,bronze,total
                            ) VALUES ('".$scrap_header."',".$k["rank"].",'".$k["flag_url"]."','".$k["country"]."','".$k["noc"]."',".$k["gold"].",".$k["silver"].",".$k["bronze"].",".$k["total"].")";
                    
                    $r = mysqli_query($conn,$query);
                


                    if(!$r)
                    {
                                              
                    
                        header('HTTP/1.1 500 Internal Server Booboo');
                        header('Content-Type: application/json; charset=UTF-8');
                        $res['msg']=mysqli_error($conn);
                        //echo json_encode($res);
                        echo(json_encode($res));
                    }
                   // $msg = "Data Saved Successfully";                        
                }

                mysqli_close($conn);
                  $msg = "We have successfully completed data scrapping from given link ";
            }
            $res['msg']=$msg;
            $res['header'] = $scrap_header;
            echo json_encode($res);
    
        }
        
        
    }

    if($_POST['action']=='show'){
        $chartData = getChartData($scrap_header);
        $result = [];
        //$result['msg']= $b;
        $result['scrap_header'] = $scrap_header;
        $result['value'] = $chartData;
        $result['year']= substr($scrap_header,0,4);
        echo json_encode($result);
    }

    
   
?>
			