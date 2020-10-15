    <h1>Thẻ</h1>
        <?php
            //create array store all tags
            $tagArray = [];

            // collect all the tags from the posts table but to remove 
            // any case-insensitive or like for like duplicated we use DISTINCT 
            // and LOWER to get only unique matches that are all in lower case 
            // this stops Demo and demo being two different tags. 
            $stmt = $db->query('SELECT distinct LOWER(postTags) as postTags FROM blog_posts WHERE postTags != "" GROUP BY postTags');


            while($row = $stmt->fetch()){
                $parts = explode(',',$row['postTags']);
                foreach ($parts as $tag){
                    $tagArray[] = $tag;
                    $tag = slug($tag);
                }
            }
            

            
            //delete all tags duplicated
            $finalTags = array_unique($tagArray);
            
            
                foreach($finalTags as $tag){
                    $size = random_int(10,25);
                    echo "<a class='tag-link p-1' style='font-size:".$size."px' href='t-".$tag."'>".ucwords($tag)."</a>";
                }
        ?>