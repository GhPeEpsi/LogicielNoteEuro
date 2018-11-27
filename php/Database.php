<?php
/**
 *  Classe de connexion à une base de donnée
 */
class Database
{
  private $db_host;
  private $db_name;
  private $db_user;
  private $db_password;
  private $pdo;

  public function __construct(){
    $this->db_host = 'localhost';
    $this->db_name = 'EURO';
    $this->db_user = 'root';
    $this->db_password = '';

    $this->pdo = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name, $this->db_user, $this->db_password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$this->pdo->query("SET NAMES UTF8");
  }

  public function getPDO(){
    return $this->pdo;
  }
}

// Test
/*
$database = new Database();
$pdo = $database->getPDO();

$sql =  'SELECT * FROM ELEVE';
foreach  ($pdo->query($sql) as $row) {
  echo $row['ELE_NUM'] . "\t";
  echo $row['ELE_NOM'] . "\t";
  echo $row['ELE_PRENOM'] . "\n";
}
*/

?>