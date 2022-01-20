<?php
//We used array_unique to quickly get all unique values.
//We then isolated all duplicates more than 1 by using array_diff_assoc.
//This reduced the size of the file we iterate on a great deal.
//then we iterated using array_filter only over the rows of the unique file and searched for those items in the duplicates file

// Get Argument from CMD
$read_file_name = $argv[1]; 
ini_set('auto_detect_line_endings', true);
$objects = array();
$countArr = array();
$allArray=array();
if (($open = fopen($read_file_name, "r")) !== FALSE) 
{
  $i = 0;
    $row=fgetcsv($open, 1, ",");
    $myunique = array_unique(file($read_file_name));
    
    $myunique = array_values($myunique);
    $myunique2 = array_diff($myunique,$row);
    $duplicates = array_diff_assoc(file($read_file_name), $myunique);
    $duplicates = array_values($duplicates);
   
    $count = array(count($myunique));

    $count = array_fill(0, count($myunique), 1);

    for($i=0; $i < count($myunique)-1; $i++)
    {
      
       $value = $myunique[$i];
      
       $cnt = count(array_filter($duplicates,function($a) use ($value) {return $a== $value;}));
      
       $count[$i] = $count[$i] + $cnt;

       $value = explode('","' , trim($value));

       
       
      //  print_r($row);die;
     
      
       if($i == 0)
       {
        $value = implode(',' , $value);
        $value = str_replace('"', "", $value);;
        $value = explode(',' , trim($value));
        array_push($value,'count');
      }
      else{
        
        $length = count($value);
        $heading_array = count($row);
        // filling the empty cell with empty value 
        $value1 = array_fill($length ,$heading_array-$length,'');
       
        $value = array_merge($value,$value1);
        // print_r($value);die;
        array_push($value,$count[$i]);

        
      }

      
      $data[] = $value;
    
      //make objects of products
      
      $obj = new stdClass();

      for($j=0; $j<count($row); $j++)
      {
        if($i == 0)
        {
          continue;
        }
        $head = $row[$j];
        array_pop($value);
        print_r($value);
       
        $obj->$head = $data[$i][$j];
        $objects[] = $obj;

      }

    }

    echo "<pre>";print_r($objects);echo "</pre>";

    //writing the file combination_count.csv
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="combination_count.csv"');

    $fp = fopen('combination_count.csv', 'w');
   
    if ($fp != false){
    foreach ($data as $line ) {
      fputcsv($fp, $line);
    }

    fclose($fp);
  }

  }
  
?>