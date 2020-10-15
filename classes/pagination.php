<?php
    $result_per_page = 1;
    if(!isset($_GET['page'])){
        $page = 1;
    }
    else{
        $page = $_GET['page'];
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php 
        $db = new PDO('mysql:host=localhost;dbname=laquyettam','laquyettam','laquyettam1995@gmail.com');
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        $start_limit_number = ($page-1)*$result_per_page;
        $sql = "SELECT * FROM blog_posts LIMIT $start_limit_number,$result_per_page";
        $query = $db->query($sql);
        while($row = $query->fetch()){
            echo $row['postTitle'] .'   | Date:' . $row['postDate']."<br>";
        }

        $temp = $db->query('SELECT postID FROM blog_posts');
        $number_of_result = count($temp->fetchAll());
        
        $number_of_pages = ceil($number_of_result / $result_per_page);
        
        #define current page of visitor
        if(!isset($_GET['page'])){
            $page = 1;
        }
        else{
            $page = $_GET['page'];
        }

        
        
        for($page = 1; $page <= $number_of_pages; $page++){
            // echo "<a href='pagination.php?p=$page'>$page</a>";
            echo '<a href="pagination.php?p='.$page.'">'.$page.'</a> ';
        }
    ?>
</body>
</html>