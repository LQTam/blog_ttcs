<?php require('includes/config.php'); ?>
<?php include('model1/header.php'); ?>

<main id='content' class="mt-5">
	<div class="row container-fluid">
		<div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
			<?php
			try {
				$stmt = $db->prepare('SELECT postID, postTitle, postSlug,image, postDesc, postDate, postTags FROM blog_posts WHERE postTags like :postTags ORDER BY postID DESC');
				$stmt->execute(array(':postTags' => '%' . $_GET['id'] . '%'));
				echo '<div class="row">';
				while ($row = $stmt->fetch()) {
					echo ("<div class='col-xl-6 col-lg-6 col-md-12 col-sm-12' style='max-width:370px'>");
					echo '<div>';
					echo '<div class="clearfix">
						<a style="float:right" href="' . $row['postSlug'] . '.html#disqus_thread"></a>
						</div>';
					echo "<a href='" . $row['postSlug'] . ".html'><div class='normalImage'><img src='images/" . $row['image'] . "' width='100%' height='100%' /></div></a>";
					echo '<h5><a href="' . $row['postSlug'] . '.html">' . htmlentities($row['postTitle']) . '</a></h5>';
					echo '<p>Posted on ' . date('jS M Y', strtotime($row['postDate'])) . ' in ';

					$stmt2 = $db->prepare('SELECT catTitle, catSlug FROM blog_cats, blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
					$stmt2->execute(array(':postID' => $row['postID']));

					$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

					$links = array();
					foreach ($catRow as $cat) {
						$links[] = "<a href='c-" . $cat['catSlug'] . "'>" . htmlentities($cat['catTitle']) . "</a>";
					}
					echo implode(", ", $links);

					echo '</p>';
					echo '<p>Tagged as: ';
					$links = array();
					$parts = explode(',', $row['postTags']);
					foreach ($parts as $tag) {
						$links[] = "<a href='t-" . $tag . "'>" . htmlentities($tag) . "</a>";
					}
					echo implode(", ", $links);
					echo '</p>';
					echo '<p>' . ($row['postDesc']) . '</p>';
					echo '<p><a href="' . $row['postSlug'] . '.html">Đọc Thêm</a></p>';
					echo '</div>';
					echo '</div>';
				}
				echo '</div>';
			} catch (PDOException $e) {
				echo $e->getMessage();
			}

			?>
		</div>
		<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 p-0">
			<?php include('model1/aside.php') ?>
		</div>
	</div>
</main>
<?php include('model1/footer.php') ?>