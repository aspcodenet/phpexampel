<?php
require_once("Models/Database.php");
require_once("Utils/Validator.php");
$dbContext = new DBContext();


$customer = new Customer();
$message = "";

$v = new Validator($_POST);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Man har tryckt på spara
    // all formulär data -> $customer
    $customer->GivenName = $_POST['GivenName'];    
    $customer->Surname = $_POST['Surname'];    
    $customer->Streetaddress = $_POST['Streetaddress'];    
    $customer->City = $_POST['City'];    
    $customer->Zipcode = $_POST['Zipcode'];    
    $customer->Country = $_POST['Country'];    
    $customer->CountryCode = $_POST['CountryCode'];    
    $customer->Birthday = $_POST['Birthday'];    
    $customer->NationalId = $_POST['NationalId'];    
    $customer->TelephoneCountryCode = $_POST['TelephoneCountryCode'];    
    $customer->Telephone = $_POST['Telephone'];    
    $customer->EmailAddress = $_POST['EmailAddress'];    
    $customer->OfficeId = $_POST['OfficeId'];        


    // VALIDERA
    $v->field('GivenName')->required()->alpha([' '])->min_len(1)->max_len(200);
    $v->field('Surname')->required()->alpha([' '])->min_len(2)->max_len(200);
    //$v->field('age') ->required()->numeric()->min_val(14)->max_val(100);
    $v->field('EmailAddress')->required()->email();
    // $v->field('password')->required()->min_len(8)->max_len(16)->must_contain('@#$&')->must_contain('a-z')->must_contain('A-Z')->must_contain('0-9');
    // $v->field('confirm_password')->required()->equals($data['password']);
    //    $v->field('sex')->enum(['male', 'female', 'others']);
    //  $v->field('phone')->numeric()->min_len(10)->max_len(10);    
    if($v->is_valid()){
        $dbContext->addCustomer($customer->GivenName,$customer->Surname,$customer->Streetaddress,$customer->City,$customer->Zipcode,
        $customer->Country,   $customer->CountryCode, $customer->Birthday,$customer->NationalId,$customer->TelephoneCountryCode,
        $customer->Telephone,$customer->EmailAddress,$customer->OfficeId
        );    
        $message = "SPARAD";
    }else{
        $message = "FIXA FEL";
    }
    // två sätt att validera
    // CLIENT - html5 required, min, max, type="number"
            // BARA User Experience Javascript, HTML5 
    // SERVERSIDE validering
    // MÅSTE VALIDERA såsom att INGEN validering skett på client
    // givennname required, minst 2 tecken, max 200
    

    // Om vi nu  kunde spara om customer i databasen

    //Spara i databasen

}

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
                        <h2>Ny kund - <?php echo $message; ?></h2>
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
                                <th><label for="name">NationalId</label></th>
                                <td>
                                <input class="form-control" type="text" name="NationalId" value="<?php echo $customer->NationalId ?>">
                                    
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">Förnamn</label></th>
                                <td>
                                    <input class="form-control" type="text" name="GivenName" value="<?php echo $customer->GivenName ?>">
                                    <span class="alert"><?php echo $v->get_error_message('GivenName');  ?></span>
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">Efternamn</label></th>
                                <td>
                                <input class="form-control" type="text" name="Surname" value="<?php echo $customer->Surname ?>">
                                <span class="alert"><?php echo $v->get_error_message('Surname');  ?></span>
                                    
                                </td>
                            </tr>


                            <tr>
                                <th><label for="name">Streetaddress</label></th>
                                <td>
                                <input class="form-control" type="text" name="Streetaddress" value="<?php echo $customer->Streetaddress ?>">
                                    
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">City</label></th>
                                <td>
                                <input class="form-control" type="text" name="City" value="<?php echo $customer->City ?>">
                                    
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">Zipcode</label></th>
                                <td>
                                <input class="form-control" type="text" name="Zipcode" value="<?php echo $customer->Zipcode ?>">
                                    
                                </td>
                            </tr>


                            <tr>
                                <th><label for="name">Country</label></th>
                                <td>
                                <input class="form-control" type="text" name="Country" value="<?php echo $customer->Country ?>">
                                    
                                </td>
                            </tr>

 
                            <tr>
                                <th><label for="name">CountryCode</label></th>
                                <td>
                                <input class="form-control" type="text" name="CountryCode" value="<?php echo $customer->CountryCode ?>">
                                    
                                </td>
                            </tr>
                            <tr>
                                <th><label for="name">Birthday</label></th>
                                <td>
                                <input class="form-control" type="datetime" name="Birthday" value="<?php echo $customer->Birthday ?>">
                                    
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">TelephoneCountryCode</label></th>
                                <td>
                                <input class="form-control" type="text" name="TelephoneCountryCode" value="<?php echo $customer->TelephoneCountryCode ?>">
                                    
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">Telephone</label></th>
                                <td>
                                <input class="form-control" type="text" name="Telephone" value="<?php echo $customer->Telephone ?>">
                                    
                                </td>
                            </tr>

 
                            <tr>
                                <th><label for="name">EmailAddress</label></th>
                                <td>
                                <input class="form-control" type="email" name="EmailAddress" value="<?php echo $customer->EmailAddress ?>">
                                    
                                </td>
                            </tr>


                            <tr>
                                <th><label for="name">OfficeId</label></th>
                                <td>
                                <input class="form-control" type="text" name="OfficeId" value="<?php echo $customer->OfficeId ?>">
                                    
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

