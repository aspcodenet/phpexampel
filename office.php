<?php
require_once("Models/Database.php");
require_once("Utils/UrlModifier.php");

$sortOrder = $_GET['sortOrder'] ?? "";
$sortCol = $_GET['sortCol'] ?? "";
$q = $_GET['q'] ?? "";
$categoryId =  $_GET['id'] ?? "";


$dbContext = new DBContext();
$urlModifier = new UrlModifier();


$theOffice = $dbContext->getOffice($categoryId);
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Stefans Webshop</title>

    <link rel="icon" type="image/*" size="32*32" href="/images/rocket.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
          integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/main.js"></script>


</head>

<body>


<!------------------sidenav-------------->
<input type="checkbox" id="active">
<nav>
    <div class="content">
        <div class="info-admin">
            <div class="user-card">
                <div  class="user-image">
                    <img src="/images/blank-profile-picture.png" alt="Not logged in">
                </div>
                <div class="user-info">
                    <h2>Account</h2>
                    <a href="/admin">
                        <i class="fas fa-sign-out-alt"></i>
                        Logga in
                    </a>
                    <!-- <a href="/admin">
                        <i class="fas fa-sign-in-alt"></i>
                        Register
                    </a>-->
                </div> 

            </div>
        </div>
        <ul class="nav-item">
            <li>
                <a href="/" >
                    <span class="sidebar-icon"><i class="fas fa-house"></i></span>
                    <span class="sidebar-text"> Start</span>
                </a>
            </li>
            <li>
                <a href="/public"  id="active">
                    <span class="sidebar-icon"><i class="fas fa-calendar"></i></span>
                    <span class="sidebar-text"> Customers</span>
                </a>
            </li>
            <li class="line-split"></li>
            <li>
                <a href="/public"  >
                    <span class="sidebar-icon"><i class="fas fa-calendar"></i></span>
                    <span class="sidebar-text"> Offices</span>
                </a>
            </li>
            <?php foreach($dbContext->getAllOffices() as $office){ ?>

                <li>
                    <a href="/office.php?id=<?php echo $office->Id ?>"  >
                        <span class="sidebar-icon"><i class="fa-solid fa-building"></i></span>
                        <span class="sidebar-text"><?php echo $office->Name ?></span>
                    </a>
                </li>
            <?php } ?>

        </ul>
    </div>

</nav>
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
        Search:
        <input type="text" name="q" class="form-control" value="<?php echo $q ?>"  />
        <input type="hidden" name="id" value="<?php echo $categoryId ?>" />
        <!-- <input type="hidden" name="sortCol" value="<?php echo $sortCol ?>" />
        <input type="hidden" name="sortOrder" value="<?php echo $sortOrder ?>" /> -->
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
                        <a class="listbutton" href="/customer.php?id=<?php echo $customer->Id ?>">Edit</a>
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


<footer>
    <div class="content">
        <div class="footer-copyright">
            <p>
                Copyright © 2024 <span>Systementor AB</span> Version: <span ></span>
            </p>
        </div>
        <div class="footer-menu">
<!--            <ul>-->
<!--                <li><a href="#">About</a></li>-->

<!--                <li><a href="#">Themes</a></li>-->

<!--                <li><a href="#">Blog</a></li>-->

<!--                <li><a href="#">Contact</a></li>-->
<!--            </ul>-->
        </div>
    </div>

</footer>

</body>

</html>

