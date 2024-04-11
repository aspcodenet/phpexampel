<?php
require 'vendor/autoload.php';
require_once('Models/Database.php');
require_once("Pages/layout/header.php");
require_once("Pages/layout/sidenav.php");
require_once("Pages/layout/footer.php");

$dbContext = new DbContext();
layout_header("Registrera");
?>
    <body>
    <main>
    <div class="top-header">
        <div class="logo">
            <a href="index.html"> <img src="/images/rocket.png"></a>
        </div>
        <div>
            <label for="active" class="menu-btn">
                <i class="fas fa-bars" id="menu"></i>
            </label>
        </div>
    </div>
    
    <div class="content">

    
        Tack för din registering. Kolla din mail och klicka på länken
    </div>

    </div>


</main>

    

<?php
layout_footer();
?>

    </body>
</html>