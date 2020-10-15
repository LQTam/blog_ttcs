<h1>Hoàn thành</h1>
<ul>
    <?php
    $stmt = $db->query("SELECT Month(postDate) as Month, Year(postDate) as Year FROM blog_posts GROUP BY Month(postDate), Year(postDate) ORDER BY month DESC");

    while ($row = $stmt->fetch()) {
        $monthName = date("F", mktime(0, 0, 0, $row['Month'], 10));
        $slug = 'a-' . $row['Month'] . '-' . $row['Year'];
        echo "<li><a href='$slug'>$monthName</a></li>";
    }
    ?>
</ul>
<hr>