<?php

require_once('models/article.php');
require_once('models/category.php');

$categories = getCategories();
$articles = getArticles(3);




require_once('views/article-form.php');

if(!isset($_SESSION['is_admin']) OR $_SESSION['is_admin'] == 0){
	header('location:../index.php');
	exit;
}








 ?>
