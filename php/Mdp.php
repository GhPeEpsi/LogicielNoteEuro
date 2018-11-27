<?php
/**
*  Classe de creation et gestion de mdp
*/

class Mdp {
	
	private $salt = '@hP0K_çMe_oZEd8';

	public function __construct(){
	}

	public function generationMDP(){
		$mdp = chr(rand(49,122));
		
		for ($i=0 ; $i<5 ; $i++) {
			$a = chr(rand(49,122));
			$mdp = $mdp . $a;
		}
		
		return $mdp;
	}
	
	function salage($string) {
		return $this->salt.$string;
	}
	
	public function hashage($string) {
		return password_hash($this->salage($string), PASSWORD_DEFAULT);
	}
}

// Test
/*
$mdp = new Mdp();
$test = $mdp->generationMDP();
$test1 = $mdp->salage($test);
echo '<h1>'.$test1.'</h1><br>';
$test2 = $mdp->hashage($test);
echo '<h1>'.$test2.'</h1>';
*/
?>