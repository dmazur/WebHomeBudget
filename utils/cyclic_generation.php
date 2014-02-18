<?php 
date_default_timezone_set('Africa/Lagos');
require_once '../config.php';
require_once '../Service/Tools/DataAccess.php';
echo "Welcome to cyclic generator, now I will populate database";
$db = new DataAccess();
$sql = 'SELECT * FROM "Cyclic"';
$dbs = $db->prepare($sql);
$dbs->execute();

if (!$dbs->execute()) {
  echo "\nCould not connect to database when try to fetch Cyclic";
}
$data = $dbs->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as &$value) {
    $when_date = $value['when'];
    $from = $value['from'];
    $to = $value['to'];
    $today = date('m/d');
    echo "\nToday is: ";
    print_r($today);
    echo " Cyclic date is: ";
    print_r($when_date_without_year = date("m/d",strtotime($when_date)));
    echo " Are they equal: ";
    print_r(strcmp($today, $when_date_without_year));

    #compare with from and to dates
    $from_date =  strtotime($from);
    $to_date =  strtotime($to);
    $todays = date("Y-m-d"); 
    $today_d = strtotime($todays);
    if(($from_date <= $today_d) && ($today_d <= $to_date)){
      echo "\n\tToday is between Cyclic from and to date";
      if(strcmp($today, $when_date_without_year) == 0){
        echo "\n\t\tI create Bill";
        $db_new_bill = new DataAccess();   
        $sql = 'INSERT INTO "Bills" ("description", "value", "category", "when")
              VALUES (:description, :value, :category, :when)';
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":category", $value['category'], PDO::PARAM_INT);
        $dbs->bindValue(":value", $value['value'], PDO::PARAM_STR);
        $dbs->bindValue(":description", $value['description'], PDO::PARAM_STR);
        $dbs->bindValue(":when", $value['when'], PDO::PARAM_STR);
        $dbs->execute();
        if ($dbs->rowCount() > 0) {
          echo "\n\t\tCreate new Bill from Cyclic";
        }
        else {
          echo "\n\t\tProblem with creating Bill from Cyclic";
        }
      }
    }
    else{
      echo "\n\tToday is not between Cyclic from and to date";
    }
}

?>
