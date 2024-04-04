<?php
require 'vendor/autoload.php';
require_once('Models/Database.php');
require_once("Pages/layout/header.php");
require_once("Pages/layout/sidenav.php");
require_once("Pages/layout/footer.php");

$dbContext = new DbContext();
$message = "";
$username = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // TODO:Add validation - redan registrerad, password != passwordAgain
    $username = $_POST['username'];
    $password = $_POST['password']; // Hejsan123#
    try{
        $userId = $dbContext->getUsersDatabase()->getAuth()->register($username, $password, $username, function ($selector, $token) {
            echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
            echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
            echo '  For SMS, consider using a third-party service and a compatible SDK';
        });
        header('Location: /user/login');
        exit;
    }
    catch(Exception $e){
        $message = "Error";
    }

}

//$namn = $_POST['namn'];

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
                                <th><label for="name">Username</label></th>
                                <td>
                                <input class="form-control" type="text" name="username" value="<?php echo $username ?>">
                                    
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">Password</label></th>
                                <td>
                                <input class="form-control" type="password" name="password" >
                                    
                                </td>
                            </tr>

                            <tr>
                                <th><label for="name">Password again</label></th>
                                <td>
                                <input class="form-control" type="password" name="passwordAgain" >
                                    
                                </td>
                            </tr>




                            <tr>
                                <td></td>
                                <td>
                                    <input type="submit" class="listbutton" value="Register">
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

    

<?php
layout_footer();
?>

    </body>
</html>