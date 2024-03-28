<?php
require_once("Models/Database.php");
$dbContext = new DBContext();

$id = $_GET['id'];

$customer = $dbContext->getCustomer($id)

?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title >Stefans Webshop</title>

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

    <div class="row-box">
        <div class="col-boxes-1">
            <div class="col-table">
                <div class="table-section">
                    <div class="header-table">
                        <h2>Ändra kund</h2>
                    </div>
                    <form method="post" class="form">
                        <table width="100%">
                            <thead>
                            <tr>
                                <th><span class="las la-sort"></span> </th>
                                <th width="90%"><span class="las la-sort"></span> </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th><label for="name">Förnamn</label></th>
                                <td>
                                    <input class="form-control" type="text" id="givenName" name="givenName" value="<?php echo $customer->GivenName ?>">
                                    
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">Efternamn</label></th>
                                <td>
                                <input class="form-control" type="text" id="surname" name="surname" value="<?php echo $customer->Surname ?>">
                                    
                                </td>
                            </tr>


                            <tr>
                                <td></td>
                                <td>
                                    <input type="submit" class="listbutton" value="Save">
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="/" class="listbutton">Cancel</a>
                                </td>
                            </tr>
                            </tbody>


                        </table>
                    </form>



                </div>

            </div>

        </div>
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

