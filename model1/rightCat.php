    <h1>Thể loại</h1>
    <ul>
        <?php
        $stmt = $db->query('SELECT catTitle,catSlug FROM blog_cats ORDER BY catID DESC');
        while ($row = $stmt->fetch()) {
            echo '<li><a href="c-' . $row['catSlug'] . '">' . htmlentities($row['catTitle']) . '</a></li>';
        }
        ?>
    </ul>
    <hr>