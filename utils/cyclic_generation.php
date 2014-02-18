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
  echo "Nie udalo sie polaczyc aby pobrac dane cykliczne z bazy";
}
$data = $dbs->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as &$value) {
    $when_date = $value['when'];
    $from_date = $value['from'];
    $to_date = $value['to'];
    $today = date('m/d');
    echo "Dzisiaj jest: ";
    print_r($today);
    echo " Jaka data Cycklicznego jest: ";
    print_r($when_date_without_year = date("m/d",strtotime($when_date)));
    echo " Czy są równe: ";
    print_r(strcmp($today, $when_date_without_year));
    if(strcmp($today, $when_date_without_year) == 0){
      echo "\nTworze rachunek cykliczny";
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
        echo "\nStworzono nowy rachunek zwyczajny na podstawie cyklicznego";
      }
      else {
        echo "\nNie udało się wstawić rachunku cyklicznego do tablicy";
      }
    }
    echo "\n";
}

?>
