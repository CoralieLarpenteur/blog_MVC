<!DOCTYPE html>
<html>
 <head>

	<title><?php echo $article['title']; ?> - Mon premier blog !</title>

  <?php require 'partials/head_assets.php'; ?>


 </head>
 <body class="article-body">
	<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

		<div class="row my-3 article-content">

      <?php require 'partials/nav.php'; ?>

			<main class="col-9">
				<article>
					<?php if(!empty($article['image'])): ?>
					<img class="pb-4 img-fluid" src="img/article/<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>" />
					<?php endif; ?>
					<h1 class="h3"><?php echo $article['title']; ?></h1>
					<b class="article-category">[<?php echo $article['categories']; ?>]</b>
					<span class="article-date">Créé le <?php echo $article['created_at']; ?></span>
					<div class="article-content">
						<?php echo $article['content']; ?>
					</div>
				</article>
			</main>

		</div>

    <?php require 'partials/footer.php'; ?>


	</div>
 </body>
</html>
