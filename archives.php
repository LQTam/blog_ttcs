<?php require('includes/config.php');
?>
<?php include('model1/header.php'); ?>
<main id='content' class="mt-5">
    <div class="row container-fluid">
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
            <?php

            try {
                $month = $_GET['month'];
                $year = $_GET['year'];
                //set from and to dates
                $from = date('Y-m-01 00:00:00', strtotime("$year-$month"));
                $to = date('Y-m-31 23:59:59', strtotime("$year-$month"));
                $stmt = $db->prepare('SELECT postID, postTitle,image, postSlug, postDesc, postDate,postTags FROM blog_posts WHERE postDate >= :from AND postDate <= :to ORDER BY postID DESC');
                $stmt->execute(array(
                    ':from' => $from,
                    ':to' => $to
                ));
                echo ("<div class='clearfix'>");
                echo ("<div class='row'>");
                while ($row = $stmt->fetch()) {
                    echo ("<div class='col-xl-6 col-lg-6 col-md-12 col-sm-12' style='max-width:370px'>");
                    echo '<div class="clearfix">
                                <a style="float:right" href="' . $row['postSlug'] . '.html#disqus_thread"></a>
                                </div>';
                    // echo '<a style="float:right" href="'.$row['postSlug'].'.html#disqus_thread"></a>';
                    echo '<a href="' . $row['postSlug'] . '.html"><div style="max-width:370px;height:210px;"><img src="images/' . $row['image'] . '"  width="100%" height="100%"></div></a>';
                    echo '<h5><a href="' . $row['postSlug'] . '.html">' . htmlentities($row['postTitle']) . '</a></h5>';
                    echo '<p>' . $row['postDesc'] . '</p>';
                    echo '<p>Posted on ' . date('jS - M - Y', strtotime($row['postDate'])) . ' in ';

                    $cmd1 = $db->prepare('SELECT catTitle,catSlug FROM blog_cats,blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                    $cmd1->execute(array(':postID' => $row['postID']));
                    $catRow = $cmd1->fetchAll(PDO::FETCH_ASSOC);
                    $link = array();
                    foreach ($catRow as $cat) {
                        $link[] = "<a href='c-" . $cat['catSlug'] . "'>" . htmlentities($cat['catTitle']) . "</a>";
                    }
                    echo implode(', ', $link);
                    echo "</p>";

                    echo '<p><a href="' . $row['postSlug'] . '.html">Đọc Thêm</a></p>';
                    echo ("</div>");
                }
                echo ("</div>");
                echo ("</div>");
            } catch (PDOException $e) {
                echo '<h3>Doesn\'t have any products in this date.</h3>';
            }
            ?>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 p-0">
            <?php include('model1/aside.php') ?>
        </div>
    </div>
</main>
<?php include('model1/footer.php'); ?>

</body>

</html>