<!doctype html>
<html lang="fr">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

		<title>Creer un groupe</title>

		<!-- JS -->
		<script>
			//vide pour le moment ..
		</script>

		<!-- PHP -->
		<?php
			//insertion du fichier permettant la connection à la BDD
			require_once('Database.php');

			//insertion du fichier permettant la gestion des MDP
			require_once('Mdp.php');

			//insertion du fichier permettant la gestion des eleve
			require_once('Perso.php');

			//Connexion à la BDD :
			//$database = new Database();
			//$conn = $database->getPDO(); //mise en commentaire pour laisser le site visitable pendant la maintenance de BDD

			//creationn de l'objet mots de passe :
			$mdp = new Mdp();

			$range_mdp[] = array ('Nom : ', 'Prenom : ', 'ID de connection : ', 'Mot de passe : ');

			//reception du fichier :
			function verifsEntreBase() {
				global $range_mdp; //$conn

				//echo '<h1>YOP1</h1>';  //Yop de secours

				if (!fichierVide() && !nomVide() && !niveauVide()) {
					//verif du fichier :
					$extension_upload = strtolower(substr(strrchr($_FILES['fichierEleve']['name'], '.'),1));
					if ($_FILES['fichierEleve']['error'] > 0) {
						echo '<h1>Erreur lors du transfert</h1>';
					} else if ($extension_upload != 'csv') {
						echo '<h1>Extension incorrecte</h1>';
					} else {
						//ajout dans la base d'élèves :
						if (($handle = fopen($_FILES['fichierEleve']['tmp_name'], "r")) !== FALSE) {
							while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
								if (!empty($data[0]) && !empty($data[1]) && ($data[1] != 'Classe')) {
									ajouterEleve($data);
								}
							}
							fclose($handle);
						}
					}
					creationFichierMDP();
				}
			}

			function creationFichierMDP() {
				global $range_mdp;

				$chemin = 'listeMDP.csv';
				$delimiteur = ';';
				$fichier_csv = fopen($chemin, 'w+');
				fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));

				foreach($range_mdp as $eleve){
					fputcsv($fichier_csv, $eleve, $delimiteur);
				}

			}

			function ajouterEleve($data) {
				global $conn,$range_mdp;
				$i=0; //permet d'avoir des id différents
				$eleve = new Perso($data); //creer un eleve où cera automatique rangé nom, prenom, classe
				$promo = $_POST['gridRadios']; //récupére le niveau de l'eleve

				//Création du num_eleve :
				$res = $conn->query('select max(ELEV_NUM) from EURO_ELEVE');
				$ligne = $res->fetch();
				$numEleve = ($ligne['max(ELEV_NUM)'] + 1);

				//récupération Nom / Prenom / Classe :
				$nom = $eleve -> nom();
				$prenom = $eleve -> prenom();
				$classe = $eleve -> classe();

				//génération de l'id :
				$eleve -> genererId($conn);
				$eleve -> id();

				//generation du mdp :
				$mdp = genererMDP();

				//insertion dans le tableau permetant de creer le csv :
				$idCSV = 'Identifiant : '.$id;
				$mdpCSV = 'Mots de passe : '. $mdp;
				$range_mdp[] = array ($nom, $prenom, $idCSV, $mdpCSV);

				//hashage du mdp pour insertion dans la base :
				$mdp = hasher($mdp);

				//choix de la promo :
				if ($promo == 'prem')
					$numClasse = 1;
				else if ($promo == 'term')
					$numClasse = 0;

				//insertion :
				$conn->query('INSERT INTO `euro_eleve` (`ELEV_NUM`, `CLASSE_NUM`, `GROU_NUM`, `ELEV_NOM`, `ELEV_PRENOM`, `ELEV_MDP`, `ELEV_CLASSE`, `ELEV_ID`)
									VALUES (
									'.$numEleve.',
									'.$numClasse.',
									0,
									\''.$nom.'\',
									\''.$prenom.'\',
									\''.$mdp.'\',
									\''.$classe.'\',
									\''.$id.'\'
									)');
			}

			function dansTableau($string, $tableau) {
				for ($i=0 ; $i<count($tableau) ; $i++) {
					if ($tableau[$i][2] == $string) {
						return true;
					}
				}
				return false;
			}

			function genererMDP() {
				global $mdp;
				return $mdp->generationMDP();
			}

			function hasher($string) {
				global $mdp;
				return $mdp->hashage($string);
			}

			function fichierVide() {
				if (empty($_FILES['fichierEleve']['name'])) {
					return true;
				}
				return false;
			}

			function afficherFichierVide() {
				if (fichierVide() && (!nomVide() || !niveauVide())) {
					echo '<p style="color : red">Veuillez choisir un fichier !</p>';
				}
			}

			function nomVide() {
				if (empty($_POST['nom'])) {
					return true;
				}
				return false;
			}

			function afficherNomVide() {
				if (nomVide() && (!fichierVide() || !niveauVide())) {
					echo '<p style="color : red">Veuillez entrer un nom !</p>';
				}
			}

			function niveauVide() {
				if (empty($_POST['gridRadios'])) {
					return true;
				}
				return false;
			}

			function afficherNiveauVide() {
				if (niveauVide() && (!fichierVide() || !nomVide())) {
					echo '<p style="color : red">Veuillez choisir un niveau !</p>';
				}
			}
		?>
	</head>
	<body>
		<!-- Barre de Navigation -->
		<nav class="navbar navbar-expand-lg navbar-light bg-primary">
			<a class="navbar-brand" href="Accueil.php">Accueil</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Groupes
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" href="CreerGroupe.php">Creer</a>
							<a class="dropdown-item" href="#">Supprimer</a>
							<a class="dropdown-item" href="#">Gerer</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Activitées
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" href="#">Creer</a>
							<a class="dropdown-item" href="#">Supprimer</a>
							<a class="dropdown-item" href="#">Gerer</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Élèves
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" href="#">Creer</a>
							<a class="dropdown-item" href="#">Supprimer</a>
							<a class="dropdown-item" href="#">Gerer</a>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Bilan</a>
					</li>
				</ul>
			</div>
		</nav>

		<!-- Moulinage des infos précédentes et explication des potentiels erreurs -->
		<?php verifsEntreBase(); ?>

		<!-- Corps -->
		<form style="margin : 5%" method="post" action="CreerGroupe.php" enctype="multipart/form-data">
			<div class="form-group row">
				<label for="nomGroupe" class="col-sm-2 col-form-label">Nom</label>
				<div class="col-sm-10">
					<?php afficherNomVide(); ?>
					<input type="text" class="form-control" id="nomGroupe" name="nom" value="" placeholder="Nom">
				</div>
			</div>
			<div class="form-group row">
				<label for="nomGroupe" class="col-sm-2 col-form-label">Fichier contenant les élèves</label>
				<div class="col-sm-10">
					<div class="form-group">
						<?php afficherFichierVide(); ?>
						<input type="file" class="form-control-file" id="fichierEleve" name="fichierEleve"/>
					</div>
				</div>
			</div>
			<fieldset class="form-group">
				<div class="row">
					<legend class="col-form-label col-sm-2 pt-0">Quel niveau ?</legend>
					<div class="col-sm-10">
						<div class="form-check">
							<?php afficherNiveauVide(); ?>
							<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="prem">
							<label class="form-check-label" for="gridRadios1">
								Première
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="term">
							<label class="form-check-label" for="gridRadios2">
								Terminale
							</label>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="form-group row">
				<div class="col-sm-10">
					<button type="submit" class="btn btn-primary">Valider</button>
				</div>
			</div>
		</form>

		<?php
			if (!empty($_FILES)) {
				echo '<button type="button" style="display:block;margin:auto;" class="btn btn-outline-success btn-lg">Télécharger la liste des mots de passe</button>';
			} else {
				echo '<button type="button" style="display:block;margin:auto;" class="btn btn-outline-success btn-lg" disabled>Télécharger la liste des mots de passe</button>';
			}
		?>

		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	</body>
</html>
