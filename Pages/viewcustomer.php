<?php
require_once("Models/Database.php");
require_once("Utils/UrlModifier.php");
require_once("Pages/layout/header.php");
require_once("Pages/layout/sidenav.php");
require_once("Pages/layout/footer.php");

$id = $_GET['id'] ?? "";


$dbContext = new DBContext();
$urlModifier = new UrlModifier();

$customer = $dbContext->getCustomer($id);

layout_header("Stefans Bank - " . $customer->GivenName . ' ' . $customer->Surname);
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
    
    <div class="row-box">
        <div class="col-boxes-1">
            <div class="col-table">
                <div class="table-section">
                    <div class="header-table">
                        <h2><?php echo $customer->Surname; ?>, <?php echo $customer->GivenName; ?></h2>
                        
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