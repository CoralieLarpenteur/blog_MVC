<?php

function getCategory($categoryId){

	$db = dbConnect();
  $query = $db->prepare('SELECT * FROM category WHERE id = ?');

  $query->execute(array($categoryId));

  return $query -> fetch();

}

function getCategories(){

	$db = dbConnect();

	$query = $db->query('SELECT * FROM category');

	return $query->fetchAll();


}

?>
