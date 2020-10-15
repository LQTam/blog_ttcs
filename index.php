<?php require('includes/config.php'); ?>
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/style.css?v=<?php echo time() ?>" />
    <style>
        label {
            display: block !important
        }
    </style>
</head>

<body>

    <div id='wrapper'>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
                <div id="logo">
                    <a class="navbar-brand" href=".">Logo</a>
                </div>
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-keyboard='false' data-backdrop='static' data-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation"></button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href=".">Trang chủ <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Bài tập</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownId">
                                <a class="dropdown-item" href="c-nguc">Bài tập ngực</a>
                                <a class="dropdown-item" href="c-lung">Bài tập lưng</a>
                                <a class="dropdown-item" href="c-chan">Bài tập chân</a>
                                <a class="dropdown-item" href="c-tay">Bài tập tay</a>
                                <a class="dropdown-item" href="c-bung">Bài tập bụng</a>
                                <a class="dropdown-item" href="c-vai">Bài tập vai</a>
                                <a class="dropdown-item" href="c-cardio">Bài tập cardio</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="nutrition" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Giáo án</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownId">
                                <a class="dropdown-item" href="c-tieu-hoc">Tiểu học</a>
                                <a class="dropdown-item" href="c-trung-hoc">Trung học</a>
                                <a class="dropdown-item" href="c-dai-hoc">Đại học</a>
                                <a class="dropdown-item" href="c-nang-cao">Nâng cao</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="plan" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dinh dưỡng</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownId">
                                <a class="dropdown-item" href="c-tang-can">Tăng cân</a>
                                <a class="dropdown-item" href="c-giam-can">Giảm cân</a>
                            </div>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="text" placeholder="Tìm kiếm">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </nav>
        </header>

        <main id='content' class="mt-5">
            <div class="row container-fluid">
                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                    <?php

                    try {
                        //first is then number of records to display next is the character to use in the 
                        // url the default is to use p so urls become ?page=1
                        $number_per_page = 6;

                        if (!isset($_GET['page'])) {
                            $pages = 1;
                        } else {
                            $pages = $_GET['page'];
                        }

                        //collect all records from the next function
                        $cmd = $db->query('SELECT postID FROM blog_posts');
                        //pass number of records to
                        $count = count($cmd->fetchAll());
                        // var_dump($pages);
                        $number_of_page = ceil($count / $number_per_page);
                        $start_limit_number = ($pages - 1) * $number_per_page;

                        $cmd = $db->query("SELECT postID,postTitle,postSlug,image,postDesc,postDate,postTags FROM blog_posts ORDER BY postID DESC LIMIT $start_limit_number,$number_per_page");
                        echo ("<div class='clearfix'>");
                        echo ("<div class='row'>");
                        while ($row = $cmd->fetch()) {
                            echo ("<div class='col-xl-6 col-lg-6 col-md-12 col-sm-12' style='max-width:370px'>");
                            echo '<div class="clearfix">
                                <a style="float:right;padding:2px" href="' . $row['postSlug'] . '.html#disqus_thread"></a>
                                </div>';
                            echo '<a href="' . $row['postSlug'] . '.html"><div class="normalImage" ><img src="images/' . $row['image'] . '"  width="100%" height="100%"></div></a>';
                            echo '<h5><a href="' . $row['postSlug'] . '.html">' . htmlentities($row['postTitle']) . '</a></h5>';
                            echo '<p>' . $row['postDesc'] . '</p>';
                            echo '<p class="">Posted on ' . date('Y-m-dS', strtotime($row['postDate'])) . ' in ';

                            $cmd1 = $db->prepare('SELECT catTitle,catSlug FROM blog_cats,blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                            $cmd1->execute(array(':postID' => $row['postID']));
                            $catRow = $cmd1->fetchAll(PDO::FETCH_ASSOC);
                            $links = array();
                            foreach ($catRow as $cat) {
                                $links[] = "<a href='c-" . $cat['catSlug'] . "' class='pill text-truncate'>" . htmlentities($cat['catTitle']) . "</a>";
                            }
                            echo implode(', ', $links);
                            echo "</p>";

                            echo '<p><a href="' . $row['postSlug'] . '.html">' . 'Đọc Thêm </a></p>';
                            echo ("</div>");
                        }
                        echo ("</div>");
                        echo ("</div>");
                    ?>
                        <div id='pagination' style='margin-bottom:10px;'>
                        <?php
                        echo "<a class='pages' href='index.php?page=" . (($pages - 1 < 1) ? ($pages = 1) : $pages - 1) . "'>Prev</a>";
                        for ($i = 1; $i <= $number_of_page; $i++) {
                            if ($i == $pages) {
                                echo "<a class='current pages' href='index.php?page=$i'>$i</a>";
                            } else {
                                echo "<a class='pages' href='index.php?page=$i'>" . $i . "</a>";
                            }
                        }
                        echo "<a class='pages' href='index.php?page=" . (($pages + 1 > $number_of_page) ? ($pages = $number_of_page) : $pages + 1) . "'>Next</a>";
                        echo "</div>";
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
        <footer>
            <div class="row container-fluid p-0 text-center">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                    <p id='copyleft' class='clearfix1'>&copy; 2018 <a href="index.php">Gym And Fitness</a></p>
                </div>
                <div class='col-xl-6 col-lg-6 col-md-6 col-sm-12'>
                    <div class="" style='margin-bottom:10px'>
                        <p>
                            <h3>Nói cho chúng tôi bạn nghĩ gì!</h3>
                        </p>
                        <?php
                        if (isset($_POST['submit'])) {
                            $fullName = $_POST['fullName'];
                            $email = $_POST['email'];
                            $content = $_POST['content'];
                            if (empty($fullName) || empty($email) || empty($content)) $message = "Không hợp lệ, xin kiểm tra tất cả các trường và thử lại!";
                            else {
                                $stmt = $db->prepare('INSERT INTO feedback (fullName,email,content) VALUES (:fullName,:email,:content)');
                                $stmt->execute(array(
                                    ":fullName" => $fullName,
                                    ':email' => $email,
                                    ':content' => $content
                                ));
                                echo "<script>alert('Thank you for your feedback!')</script>";
                            }
                        }
                        ?>

                        <form class="borderForm" style='padding-bottom:5px;' action='' method='post'>
                            <label for="" class='displayBlock'>Tên:</label>
                            <input type='text' name='fullName' />

                            <label for="" class='displayBlock'>Email: </label>
                            <input type='email' name='email' />

                            <label class='displayBlock'>Nội dung: </label>
                            <textarea name="content" cols="30" rows="5"></textarea>

                            <!-- <label>&nbsp;</label> -->
                            <br>
                            <input type='submit' value='Gửi' name='submit' />
                        </form>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                    <div id="contact">
                        <p>Liên hệ:</p>
                        <a href=""> <i class="fa fa-facebook" aria-hidden="true"></i> FOLLOW US</a><br>
                        <a href="mailto:laquyettam1995@gmail.com" aria-hidden="true"><i class="fa fa-envelope-o"></i>
                            MAIL
                            ME</a>

                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script id="dsq-count-scr" src="//blog-qx34bgck2z.disqus.com/count.js" async></script>
</body>

</html>