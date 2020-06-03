<?php
include "includes/header.php"
?>
    <!-- Navigation -->
  <?php
include "includes/navigation.php"
?>


<?php

if(userLiked(41)){

    echo 'like';
}else{
echo ' d"ont like';
}

