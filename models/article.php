<?php


function getArticle( $articleId ){

	$db = dbConnect();

	$query = $db->prepare('
		SELECT article.*, GROUP_CONCAT(category.name SEPARATOR " / ") AS categories
		FROM article
		JOIN article_category ON article.id = article_category.article_id
		JOIN category ON article_category.category_id = category.id
		WHERE article.id = ? AND article.is_published = 1
	');

	$query->execute( array( $articleId ) );

	return $query->fetch();

}

function getArticles($limit = false, $categoryId = false, $orderBy = false){

	$db = dbConnect();

	$queryString = 'SELECT article.*, GROUP_CONCAT(category.name SEPARATOR " / ") AS categories FROM article JOIN article_category ON article.id = article_category.article_id JOIN category ON category.id = article_category.category_id WHERE article.is_published = 1';

  if($categoryId){
    $queryString .= ' AND article_category.category_id = :category_id';
	}

	$queryString .= ' GROUP BY article.id ORDER BY created_at DESC';

	if($limit){
		$queryString .= ' LIMIT 0, :limit';
	}

  $query = $db->prepare($queryString);

	if($limit) {
    $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
	}
	if($categoryId){
		$query->bindValue(':category_id', (int)$categoryId, PDO::PARAM_INT);
	}

	$query -> execute();

	return $query->fetchAll();
}



?>
