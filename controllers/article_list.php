<?php

require_once ('models/article.php');
require_once ('models/category.php');

$categories = getCategories();

if(isset($_GET['category_id'])){

  $currentCategory = getCategory($_GET['category_id']);
  if($currentCategory) {
    $articles = getArticles(false, $_GET['category_id']);
  }
}
else{
    $articles = getArticles(false, false);
}

require_once ('views/article_list.php');

?>
