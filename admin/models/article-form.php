<?php


function saveArticle (){
  $db = dbConnect();

  $query = $db->prepare('INSERT INTO article (title, content, summary, is_published, created_at) VALUES (?, ?, ?, ?, NOW())');
  $newArticle = $query->execute(
    [
      $_POST['title'],
      $_POST['content'],
      $_POST['summary'],
      $_POST['is_published']
    ]
  );

  $lastInsertedArticleId = $db->lastInsertId();

	foreach($_POST['categories'] as $category_id){
		$query = $db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
		$newArticle = $query->execute([
			$lastInsertedArticleId,
			$category_id,
		]);
	}

  if($newArticle){

    //upload de l'image si image envoyée via le formulaire
    if(!empty($_FILES['image']['name'])){

      //tableau des extentions que l'on accepte d'uploader
      $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
      //extension dufichier envoyé via le formulaire
      $my_file_extension = pathinfo( $_FILES['image']['name'] , PATHINFO_EXTENSION);

      //si l'extension du fichier envoyé est présente dans le tableau des extensions acceptées
      if ( in_array($my_file_extension , $allowed_extensions) ){

        //je génrère une chaîne de caractères aléatoires qui servira de nom de fichier
        //le but étant de ne pas écraser un éventuel fichier ayant le même nom déjà sur le serveur
        $new_file_name = md5(rand());

        //destination du fichier sur le serveur (chemin + nom complet avec extension)
        $destination = '../img/article/' . $new_file_name . '.' . $my_file_extension;

        //déplacement du fichier à partir du dossier temporaire du serveur vers sa destination
        $result = move_uploaded_file( $_FILES['image']['tmp_name'], $destination);

        //mise à jour de l'article enregistré ci-dessus avec le nom du fichier image qui lui sera associé
        $query = $db->prepare('UPDATE article SET
          image = :image
          WHERE id = :id'
        );

        $resultUpdateImage = $query->execute(
          [
            'image' => $new_file_name . '.' . $my_file_extension,
            'id' => $lastInsertedArticleId
          ]
        );
      }
    }

    //redirection après enregistrement
    header('location:article-list.php');
    exit;
    }
  else{ //si pas $newArticle => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
    $message = "Impossible d'enregistrer le nouvel article...";
  }
}


function updateArticle ()
{
  $query = $db->prepare('UPDATE article SET
		title = :title,
		content = :content,
		summary = :summary,
		is_published = :is_published
		WHERE id = :id'
	);

	//mise à jour avec les données du formulaire
	$resultArticle = $query->execute([
		'title' => $_POST['title'],
		'content' => $_POST['content'],
		'summary' => $_POST['summary'],
		'is_published' => $_POST['is_published'],
		'id' => $_POST['id'],
	]);

	$query = $db->prepare('DELETE FROM article_category WHERE article_id = ?');
	$result = $query->execute([
		$_POST['id']
	]);

	foreach($_POST['categories'] as $category_id){
		$query = $db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
		$newArticle = $query->execute([
			  $_POST['id'],
			  $category_id,
		]);
	}

	//si enregistrement ok
	if($resultArticle){
        if(!empty($_FILES['image']['name'])){

            $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $my_file_extension = pathinfo( $_FILES['image']['name'] , PATHINFO_EXTENSION);

            if ( in_array($my_file_extension , $allowed_extensions) ){

				//si un fichier est soumis lors de la mise à jour, je commence par supprimer l'ancien du serveur s'il existe
				if(isset($_POST['current-image'])){
					unlink('../img/article/' . $_POST['current-image']);
				}

                $new_file_name = md5(rand());
                $destination = '../img/article/' . $new_file_name . '.' . $my_file_extension;
                $result = move_uploaded_file( $_FILES['image']['tmp_name'], $destination);

                $query = $db->prepare('UPDATE article SET
					image = :image
					WHERE id = :id'
                );
                $resultUpdateImage = $query->execute([
					'image' => $new_file_name . '.' . $my_file_extension,
					'id' => $_POST['id']
				]);
            }
        }

        header('location:article-list.php');
        exit;
    }
	else{
		$message = 'Erreur.';
	}
}

function editArticle()
{
  $query = $db->prepare('SELECT * FROM article WHERE id = ?');
  $query->execute(array($_GET['article_id']));
  //$article contiendra les informations de l'article dont l'id a été envoyé en paramètre d'URL
  $article = $query->fetch();

  $query = $db->prepare('SELECT category_id FROM article_category WHERE article_id = ?');
  $query->execute(array($_GET['article_id']));

  $articleCategories = $query->fetchAll();
}

function addImage()
{
  $query = $db->prepare('INSERT INTO image (caption, article_id) VALUES (?, ?)');
	$newImage = $query->execute([
		$_POST['caption'],
		$_POST['article_id']
	]);

	//on récupère l'ID de l'image que l'on vient d'enregistrer en BDD
	$lastInsertedImageId = $db->lastInsertId();

	//si enregistrement ok
	if($newImage){
        if(isset($_FILES['image'])){

            $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $my_file_extension = pathinfo( $_FILES['image']['name'] , PATHINFO_EXTENSION);

            if ( in_array($my_file_extension , $allowed_extensions) ){

                $new_file_name = md5(rand());
                $destination = '../img/article/' . $new_file_name . '.' . $my_file_extension;
                $result = move_uploaded_file( $_FILES['image']['tmp_name'], $destination);

				//mise à jour de l'image enregistrée avec le nom de fichier généré
                $query = $db->prepare('UPDATE image SET
					name = :name
					WHERE id = :id'
                );
                $resultUpdateImage = $query->execute([
					'name' => $new_file_name . '.' . $my_file_extension,
					'id' => $lastInsertedImageId
				]);
            }
        }
    }
}

?>
