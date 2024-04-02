<?php
require_once("Models/Database.php");
require_once("Utils/UrlModifier.php");
require_once("Pages/layout/header.php");
require_once("Pages/layout/sidenav.php");
require_once("Pages/layout/footer.php");


$sortOrder = $_GET['sortOrder'] ?? "";
$sortCol = $_GET['sortCol'] ?? "";
$q = $_GET['q'] ?? "";
$categoryId =  $_GET['id'] ?? "";


$dbContext = new DBContext();
$urlModifier = new UrlModifier();


$theOffice = $dbContext->getOffice($categoryId);

layout_header("Stefans Bank - Office " . $theOffice->Name);
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
                        <h2>Kunder i <?php echo $theOffice->Name ?></h2>
                        <a id="clear-filter" href="javascript:void">see all</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <a class="listbutton" href="/admin/1/locations/new">
        <span class="fas fa-plus mr-2"></span>
        Ny kund
    </a>
    <br/><br/>
    <form method="GET">
        Search i <?php echo $theOffice->Name ?>:
        <input type="text" name="q" class="form-control" value="<?php echo $q ?>"  />
        <input type="hidden" name="id" value="<?php echo $categoryId ?>" />

    </form>
    <table class="tabulator-table">
        <thead>
            <tr class="tabulator-row">
                <th class="tabulator-cell">
                    National ID
                    <a href="?sortCol=NationalId&sortOrder=asc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=NationalId&sortOrder=desc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>

                <th >
                    Förnamn
                    <a href="?sortCol=GivenName&sortOrder=asc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=GivenName&sortOrder=desc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>Efternamn
                    <a href="?sortCol=Surname&sortOrder=asc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=Surname&sortOrder=desc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>City                
                    <a href="?sortCol=City&sortOrder=asc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=City&sortOrder=desc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>Country                
                    <a href="?sortCol=Country&sortOrder=asc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=Country&sortOrder=desc&q=<?php echo $q ?>&id=<?php echo $categoryId ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th></th>
            </tr>

        </thead>
        <tbody>
            <?php
            foreach($dbContext->searchCustomers($sortCol,$sortOrder,$q,$categoryId) as $customer) {
            ?>
                <tr class="tabulator-row">
                    <td ><?php echo $customer->NationalId ?></td>
                    <td ><?php echo $customer->GivenName ?></td>
                    <td ><?php echo $customer->Surname ?></td>
                    <td ><?php echo $customer->City ?></td>
                    <td><?php echo $customer->Country ?></td>
                    <td>
                        <a class="listbutton" href="/customer?id=<?php echo $customer->Id ?>">Edit</a>
                    </td>
                </tr>
            <?php
            }
            ?>
    </tbody>
    </table>

    <link rel="stylesheet" href="/css/tabulator/tabulator_modified.css">


</div>
    </div>


</main>


<?php
layout_footer();
?>

</body>

</html>

