<?php
session_start();

define("UMAX", 20);

$pages = str_replace("page=cp_users", "", str_replace("&pages=", "", $_SERVER['QUERY_STRING']));

if(empty($pages)) $pages = 1;

require_once('conn.php');

//check if user is banned
$stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
$stmt->bindValue(1, $_SESSION['userid']);
$stmt->execute();

if(/*(time() > strtotime($stmt->fetchColumn().' + 4 hours')) &&*/ $_SESSION['priviledge'] >= 2){
    $stmt = $pdo->prepare("SELECT userid, username, priviledge, email FROM users limit ?, ".UMAX.";");
    $stmt->bindValue(1, (int)($pages-1)*UMAX, PDO::PARAM_INT);
    if($stmt->execute()){
        echo '<form method="get" action="php/cp_update.php"><table><tr>
        <td><label for="userid">Id: </label><input type="number" id="userid" name="userid" min="0"/></td>
        <td><label for="priviledge">Priviledge: </label><b id="priviledgeValue">0</b>
        <input type="range" id="priviledge" name="priviledge" min="0" max="3" step="1" value="0" oninput="document.getElementById(`priviledgeValue`).innerHTML = this.value;"/></td>
        <td><label for="ban">Ban (minutes): </label><input type="number" id="ban" name="ban"/></td>
        <td><input type="submit" id="update" name="update" value="Update"/></td>
        </tr></table></form>';

        //get count number of users
        $stmt2 = $pdo->prepare("SELECT COUNT(userid) FROM users;");
        $stmt2->execute();

        echo '<table style="text-align:center"><caption>Number of users: '.$stmt2->fetchColumn().'</caption>';
        echo '<tr><th>Id</th><th>Username</th><th>Priviledge</th><th>Email</th><th>Unban@:</th></tr>';

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<tr><td>".$row['userid']."</td><td>".$row['username']."</td><td>".$row['priviledge']."</td><td>".$row['email']."</td>";
            $stmt2 = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
            $stmt2->bindValue(1, $row['userid']);
            $stmt2->execute();
            echo "<td>";
            if($stmt2->rowCount() > 0){
                echo $stmt2->fetchColumn();
            }else echo "0";
            echo "</td></tr>";
        }
        echo "</table>";

        // BUTTON: PREV
        if($pages>1){
            echo '<a class="nsyn" href="?page=cp_users&pages='.($pages-1).'"><button>Prev</button></a>';
        }

        // BUTTON: NEXT
        if($stmt->rowCount() == UMAX){
            echo '<a class="nsyn" href="?page=cp_users&pages='.($pages+1).'"><button>Next</button></a>';
        }
    }
    else{
        echo "<p>Error: query failed.</p>";
    }
}
else{
    echo "<p>You don't have the priviledges to use the control panel.</p>";
}

$pdo = null;
$stmt = null;
$stmt2 = null;
?>