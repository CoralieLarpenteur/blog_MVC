

<!DOCTYPE html>
<html>
	<head>

		<title>Administration des articles - Mon premier blog !</title>

		<?php require 'partials/head_assets.php'; ?>

	</head>
	<body class="index-body">
		<div class="container-fluid">

			<?php require 'partials/header.php'; ?>

			<div class="row my-3 index-content">

				<?php require 'partials/nav.php'; ?>

				<section class="col-9">
					<header class="pb-3">
						<!-- Si $article existe, on affiche "Modifier" SINON on affiche "Ajouter" -->
						<h4><?php if(isset($article)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> un article</h4>
					</header>

					<ul class="nav nav-tabs justify-content-center nav-fill" role="tablist">
						<li class="nav-item">
							<a class="nav-link <?php if(isset($_POST['save']) || isset($_POST['update']) || !isset($_POST['add_image'])): ?>active<?php endif; ?>" data-toggle="tab" href="#infos" role="tab">Infos</a>
						</li>
						<?php if(isset($article)): ?>
						<li class="nav-item">
							<a class="nav-link <?php if(isset($_POST['add_image'])): ?>active<?php endif; ?>" data-toggle="tab" href="#images" role="tab">Images</a>
						</li>
						<?php endif; ?>
					</ul>

					<div class="tab-content">
						<div class="tab-pane container-fluid <?php if(isset($_POST['save']) || isset($_POST['update']) || !isset($_POST['add_image'])): ?>active<?php endif; ?>" id="infos" role="tabpanel">

							<?php if(isset($message)): //si un message a été généré plus haut, l'afficher ?>
							<div class="bg-danger text-white">
								<?php echo $message; ?>
							</div>
							<?php endif; ?>

							<!-- Si $article existe, chaque champ du formulaire sera pré-remplit avec les informations de l'article -->
							<form action="article-form.php" method="post" enctype="multipart/form-data">

								<div class="form-group">
									<label for="title">Titre :</label>
									<input class="form-control" <?php if(isset($article)): ?>value="<?php echo htmlentities($article['title']); ?>"<?php endif; ?> type="text" placeholder="Titre" name="title" id="title" />
								</div>
								<div class="form-group">
									<label for="content">Contenu :</label>
									<textarea class="form-control" name="content" id="content" placeholder="Contenu"><?php if(isset($article)): ?><?php echo htmlentities($article['content']); ?><?php endif; ?></textarea>
								</div>
								<div class="form-group">
									<label for="summary">Résumé :</label>
									<input class="form-control" <?php if(isset($article)): ?>value="<?php echo htmlentities($article['summary']); ?>"<?php endif; ?> type="text" placeholder="Résumé" name="summary" id="summary" />
								</div>

								<div class="form-group">
									<label for="image">Image :</label>
									<input class="form-control" type="file" name="image" id="image" />
									<?php if(isset($article) && $article['image']): ?>
									<img class="img-fluid py-4" src="../img/article/<?php echo $article['image']; ?>" alt="" />
									<input type="hidden" name="current-image" value="<?php echo $article['image']; ?>" />
									<?php endif; ?>
								</div>

								<div class="form-group">
									<label for="categories"> Catégorie </label>
									<select class="form-control" name="categories[]" id="categories" multiple="multiple">
										<?php
										$queryCategory= $db ->query('SELECT * FROM category');
										$categories = $queryCategory->fetchAll();
										?>
										<?php foreach($categories as $key => $category) : ?>

											<?php
											$selected = '';

											foreach ($articleCategories as $articleCategorie) {
												if($category['id'] == $articleCategorie['category_id']){
													$selected = 'selected="selected"';
												}
											}
											?>
											<option value="<?php echo $category['id']; ?>" <?php echo $selected; ?>> <?php echo $category['name']; ?> </option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group">
									<label for="is_published"> Publié ?</label>
									<select class="form-control" name="is_published" id="is_published">
										<option value="0" <?php if(isset($article) && $article['is_published'] == 0): ?>selected<?php endif; ?>>Non</option>
										<option value="1" <?php if(isset($article) && $article['is_published'] == 1): ?>selected<?php endif; ?>>Oui</option>
									</select>
								</div>


								<div class="text-right">
								<!-- Si $article existe, on affiche un lien de mise à jour -->
								<?php if(isset($article)): ?>
								<input class="btn btn-success" type="submit" name="update" value="Mettre à jour" />
								<!-- Sinon on afficher un lien d'enregistrement d'un nouvel article -->
								<?php else: ?>
								<input class="btn btn-success" type="submit" name="save" value="Enregistrer" />
								<?php endif; ?>
								</div>

								<!-- Si $article existe, on ajoute un champ caché contenant l'id de l'article à modifier pour la requête UPDATE -->
								<?php if(isset($article)): ?>
								<input type="hidden" name="id" value="<?php echo $article['id']; ?>" />
								<?php endif; ?>

							</form>
						</div>
						<?php if(isset($article)): ?>
						<div class="tab-pane container-fluid <?php if(isset($_POST['add_image'])): ?>active<?php endif; ?>" id="images" role="tabpanel">

								<h5 class="mt-4"><?php if(isset($image)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> une image :</h5>

								<form action="article-form.php?article_id=<?php echo $article['id']; ?>&action=edit" method="post" enctype="multipart/form-data">
									<div class="form-group">
										<label for="caption">Légende :</label>
										<input class="form-control" type="text" placeholder="Légende" name="caption" id="caption" />
									</div>
									<div class="form-group">
										<label for="image">Fichier :</label>
										<input class="form-control" type="file" name="image" id="image" />
									</div>

									<input type="hidden" name="article_id" value="<?php echo $article['id']; ?>" />

									<div class="text-right">
										<input class="btn btn-success" type="submit" name="add_image" value="Enregistrer" />
									</div>
								</form>

								<h5>Liste des images :</h5>

						</div>
						<?php endif; ?>
					</div>
				</section>
			</div>
		</div>
  </body>
</html>
