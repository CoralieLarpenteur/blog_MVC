<?php

require_once('common.php');
$db = dbConnect();
session_start();

if(isset($_GET['page'])){

  if($_GET['page'] == 'article_list'){
    require_once('controllers/article_list.php');
  }
  elseif($_GET['page'] == 'article'){
    require_once('controllers/article.php');
  }
  elseif($_GET['page'] == 'user'){
    require_once('controllers/user.php');
  }
  elseif (isset($_SESSION['user'])) {
    if ($_SESSION['is_admin'] == 1) {
      if ($_GET['page'] == 'user-list') {
        require_once('admin/controllers/user-list.php');
      }
      elseif ($_GET['page'] == 'user-form') {
        require_once('admin/controllers/user-form.php');
      }
      elseif ($_GET['page'] == 'article-list') {
        require_once('admin/controllers/article-list.php');
      }
      elseif ($_GET['page'] == 'article-form') {
        require_once('admin/controllers/article-form.php');
      }
      elseif ($_GET['page'] == 'category-list') {
        require_once('admin/controllers/category-list.php');
      }
      elseif ($_GET['page'] == 'category-form') {
        require_once('admin/controllers/category-form.php');
      }
      else {
        require_once('admin/controllers/index.php');
      }
    }
    else {
      require_once('controlers/index.php');
    }
  }
  else{
    require_once('controllers/index.php');
  }
}
else{
  require_once('controllers/index.php');
}



?>
