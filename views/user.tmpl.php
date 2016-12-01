
<?php include '_partials/header.php'; ?>

<ul class="user_info">
    <?php
    if($user_info) {
        foreach($user_info as $u) {
            $nick = htmlentities($u->nick);
            echo "<li>{$nick}</li>";
        }
    } else {
        echo "<li>No results available</li>";
    }

    ?>
</ul>

<p><a href="index.php">Back</a></p>

<?php include '_partials/footer.php'; ?>
