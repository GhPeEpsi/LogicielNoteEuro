<?php
/**
*  Classe de gestion d'un Eleve :
*  - recup prenom / nom / classe
*  - ..
*/

class Perso {

	private $nom = '';
	private $prenom = '';
	private $classe = '';
	private $id = '';

	public function __construct($data){
		global $nom, $prenom, $classe;
		$app = $this -> decortique($data);
		$nom = $app[0];
		$prenom = $app[1];
		$classe = $app[2];
	}

	function decortique($data) {
		//c=0 => NOM Prenom / c=1 => Classe
		$taille =
		$ptiBout = explode(' ', $data[0]);
		$appelation[0] = ''; $appelation[1] = '';

		//Séparation Nom Prenom
		$i=0;
		foreach ($ptiBout as $bout) {
			if (lcfirst($bout) != mb_strtolower($bout, 'UTF-8')) {
				if ($appelation[0] != '') {
					$appelation[0] = $appelation[0] . ' ' . $bout;
				}
				else { $appelation[0] = $bout; }
			}
			else {
				if ($appelation[1] != '') {
					$appelation[1] = $appelation[1] . ' ' . $bout;
				}
				else { $appelation[1] = $bout; }
			}

			$i++;
		}

		$appelation[2] = $data[1];

		return $appelation;
	}

	function genereId($conn) {
		global $id;

		//récupération de l'éventuel idx de pseudo :
		$id = strtoupper($nom).substr($prenom,0,1);
		$res = $conn->query('select max(ELEV_NUM) from EURO_ELEVE');

		//écriture du nouveau pseudo :
		//$id = $id . ;
	}

	function nom() {
		global $nom;
		return $nom;
	}

	function prenom() {
		global $prenom;
		return $prenom;
	}

	function classe() {
		global $classe;
		return $classe;
	}

	function id() {
		global $id;
		return $id;
	}
}

// Test
/*
$data[0] = 'LE TOURNEUR Jean Sebastien';$data[1] = '1 S si';
$test = new Perso($data);

$test -> afficheTout();
*/
?>
