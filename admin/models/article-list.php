<?php

//supprimer l'article dont l'ID est envoyé en paramètre URL
if(isset($_GET['article_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

	$query = $db->prepare('SELECT image FROM article WHERE id = ?');
	$query->execute(array($_GET['article_id']));
	$imageToDelete = $query->fetch();

	if($imageToDelete){ //si différent de NULL
		unlink('../img/article/' . $imageToDelete["image"]);
	}

	$query = $db->prepare('DELETE FROM article_category WHERE article_id = ?');
	$result = $query->execute(
		[
			$_GET['article_id']
		]
	);

	$query = $db->prepare('DELETE FROM article WHERE id = ?');
	$result = $query->execute(
		[
			$_GET['article_id']
		]
	);
	//générer un message à afficher plus bas pour l'administrateur
	if($result){
		$message = "Suppression efféctuée.";
	}
	else{
		$message = "Impossible de supprimer la séléction.";
	}
}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM article');
$articles = $query->fetchall();
?>
