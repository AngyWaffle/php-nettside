<?php
echo '<div class="topnav">
<a href="index.php" class="links">Home</a>
<div id="myLinks">
    <a href="product_page.php" class="links">Products</a>
    <a href="about_us.php" class="links">About Us</a>
    <a href="cart.php" class="links">Cart</a>
    ';
    if (isset($_SESSION['user_id'])) {
        echo '<a href="profile.php" class="links">Profile</a>';
    } else {
        echo '<a href="login.php" class="links">Login</a>';
    }
echo '
</div>
<a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
</a>
</div>';
?>