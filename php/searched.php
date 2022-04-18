<?php
if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET) && !empty($_GET)){
    if(!empty($_GET['search'])){

        require_once('conn.php');
        require_once('lib.php');

        echo "<h1>Search results:</h1><hr>";
        
        if(tableExists($pdo,'threads')){
            $stmt = $pdo->prepare("SELECT * FROM threads WHERE msg LIKE CONCAT('%', :search, '%') OR title LIKE CONCAT('%', :search, '%');");
            $stmt->bindValue(':search', $_GET['search']);
            $stmt->execute();
        
            if($stmt->rowCount() > 0){
                echo '<table><caption>Threads search: '.htmlspecialchars($_GET['search'])."</caption><tr><th>Author</th><th>Date</th><th>Title</th><th>Message</th></tr>";
        
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo '<tr class="link" style="text-align:center;" onclick="window.location.href=`';
                    echo 'index.php?thread='.$row['threadid'];

                        //get username
                        $stmt = $pdo->prepare("SELECT username FROM users WHERE userid = :userid;");
                        $stmt->bindValue(":userid", $row['authorid']);
                        $stmt->execute();
                        $author = $stmt->fetchColumn();

                    echo '`"><td><a>'.limitstr($author, 20)
                    .'</a></td><td><a>'.limitstr($row['date'], 20)
                    .'</a></td><td><a>'.htmlspecialchars(limitstr($row['title'], 20))
                    .'</a></td><td><a href="index.php?thread='.$row['threadid'].'">'.htmlchars_minus(limitstr($row['msg'], 200), ...$htmltags)
                    .'</a></td></tr>';
                }
                echo "</table>";
            } else echo "<p>".htmlspecialchars($_GET['search']).": No results found in threads."."</p>";
        } else echo "<p>Search failed. Table: threads does not exist.</p>";

        if(tableExists($pdo, 'posts')){
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE msg LIKE CONCAT('%', :search, '%') OR title LIKE CONCAT('%', :search, '%');");
            $stmt->bindValue(':search', $_GET['search']);
            $stmt->execute();
            echo "<hr>";
        
            if($stmt->rowCount() > 0){
                echo '<table><caption>Posts search: '.htmlspecialchars($_GET['search'])."</caption><tr><th>Author</th><th>Date</th><th>Title</th><th>Message</th></tr>";
        
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo '<tr class="link" style="text-align:center;" onclick="window.location.href=`';
        
                    echo 'index.php?thread='.$row['threadid'];

                        //get username
                        $stmt = $pdo->prepare("SELECT username FROM users WHERE userid = :userid;");
                        $stmt->bindValue(":userid", $row['authorid']);
                        $stmt->execute();
                        $author = $stmt->fetchColumn();

                    echo '`"><td><a>'.limitstr($author, 20)
                    .'</a></td><td><a>'.limitstr($row['date'], 20)
                    .'</a></td><td><a>'.htmlspecialchars(limitstr($row['title'], 20))
                    .'</a></td><td><a href="index.php?thread='.$row['threadid'].'">'.htmlchars_minus(limitstr($row['msg'], 200), ...$htmltags)
                    .'</a></td></tr>';
                }
                echo "</table>";
            } else echo "<p>".htmlspecialchars($_GET['search']).": No results found in posts."."</p>";
        } else echo "<p>Search failed. Table: posts does not exist.</p>";

        $pdo = null;
        $stmt = null;
    } else echo "<p>No results: empty search.</p>";
} else header("Location: ../index.php");
?>