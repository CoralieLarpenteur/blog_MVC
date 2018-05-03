<?php

require_once('../models/article.php');
require_once('../models/category.php');

$categories = getCategories();
$articles = getArticles(3);



require_once('..views/article-list.php');


 ?>
