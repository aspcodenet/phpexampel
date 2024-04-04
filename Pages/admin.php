<?php
require_once("Models/Database.php");
require_once("Utils/UrlModifier.php");
require_once("Pages/layout/header.php");
require_once("Pages/layout/sidenav.php");
require_once("Pages/layout/footer.php");


$dbContext = new DBContext();

layout_header("Stefans Bank ADMIN");
?>


<body>


<!------------------sidenav-------------->
<?php
layout_sidenav($dbContext);
?>
<!------------------main-------------->
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
        <div>
    <input type="hidden" id="currentOrgId" value="1">

    <div class="row-box">
        <div class="col-boxes-1">
            <div class="col-table">
                <div class="table-section">
                    <div class="header-table">
                        <h2>SO SECRET!!!</h2>
                        <a id="clear-filter" href="javascript:void">see all</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>
</div>

</main>

<?php
layout_footer();
?>


</body>

</html>