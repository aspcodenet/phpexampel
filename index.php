<?php
require_once("Models/Database.php");
require_once("Utils/UrlModifier.php");
$dbContext = new DBContext();
$urlModifier = new UrlModifier();
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title th:text="${pageTitle}">Stefans Webshop</title>

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
            <li>
                <a href="/public"  >
                    <span class="sidebar-icon"><i class="fas fa-calendar"></i></span>
                    <span class="sidebar-text"> Accounts</span>
                </a>
            </li>


                <li class="line-split"></li>
                <li>
                    <a href="/admin/systemadmin/organisations"  >
                        <span class="sidebar-icon"><i class="fa-solid fa-building"></i></span>
                        <span class="sidebar-text">   Withdraw</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/systemadmin/users"  th:attrappend="id=${activeFunction=='users'? 'active' : null}">
                        <span class="sidebar-icon"><i class="fa-solid fa-user-group"></i></span>
                        <span class="sidebar-text">   Deposit</span>
                    </a>
                </li>

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
                        <h2>Kunder</h2>
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
    <table class="tabulator-table">
        <thead>
            <tr class="tabulator-row">
                <th class="tabulator-cell">
                    National ID
                    <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"asc", "sortCol"=>"NationalId"]) ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"desc", "sortCol"=>"NationalId"]) ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                    </th>

                <th >
                    Förnamn
                    <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"asc", "sortCol"=>"GivenName"]) ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"desc", "sortCol"=>"GivenName"]) ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>Efternamn
                <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"asc", "sortCol"=>"Surname"]) ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"desc", "sortCol"=>"Surname"]) ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>City                
                <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"asc", "sortCol"=>"City"]) ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"desc", "sortCol"=>"City"]) ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>Country                
                <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"asc", "sortCol"=>"Country"]) ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    <a href="<?php echo UrlModifier::changeParameters($_SERVER['REQUEST_URI'],["sortOrder"=>"desc", "sortCol"=>"Country"]) ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th></th>
            </tr>

        </thead>
        <tbody>
            <?php
            foreach($dbContext->getAllCustomers($_GET["sortCol"] ?? null, $_GET["sortOrder"] ?? null) as $customer) {
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
                Copyright © 2024 <span>Systementor AB</span> Version: <span th:text="${VersionNumber}"></span>
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

