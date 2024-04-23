<?php
require 'vendor/autoload.php';
require_once("Models/Database.php");
require_once("Utils/UrlModifier.php");
require_once("Pages/layout/header.php");
require_once("Pages/layout/sidenav.php");
require_once("Pages/layout/footer.php");

$sortOrder = $_GET['sortOrder'] ?? "";
$sortCol = $_GET['sortCol'] ?? "";
$q = $_GET['q'] ?? "";
$pageNo = $_GET['pageNo'] ?? "1";
$pageSize = $_GET['pageSize'] ?? "20";


$dbContext = new DBContext();
$urlModifier = new UrlModifier();



// $mail = new PHPMailer\PHPMailer\PHPMailer(true);
// $mail->isSMTP();
// $mail->Host = 'smtp.ethereal.email';
// $mail->SMTPAuth = true;
// $mail->Username = 'raheem2@ethereal.email';
// $mail->Password = 'PdZkY2RvfRyZGrgNAT';
// $mail->SMTPSecure = 'tls';
// $mail->Port = 587;

// $mail->From = "stefans@superdupershop.com"; 
// $mail->FromName = "Stefans SuperShop"; //To address and name 
// $mail->addAddress("bill.gates@microsoft.com"); //Address to which recipient will reply 
// $mail->addReplyTo("noreply@ysuperdupershop.com", "No-Reply"); //CC and BCC 
// $mail->isHTML(true); 
// $mail->Subject = "Bara liten test"; 
// $mail->Body = "<h2>Hej</h2>, Vilket kul nyhetsbrev <b>fdsfds</b>";
// $mail->send();


// $mail->From = "stefans@superdupershop.com"; 
// $mail->FromName = "Stefans SuperShop"; //To address and name 
// $mail->addAddress("kalle@hfdsdg7ewqygwqu.com"); //Address to which recipient will reply 
// $mail->addReplyTo("noreply@ysuperdupershop.com", "No-Reply"); //CC and BCC 
// $mail->isHTML(true); 
// $mail->Subject = "Bara liten test2"; 
// $mail->Body = "<h2>Hej2</h2>, Vilket kul nyhetsbr2ev <b>fdsfds</b>";
// $mail->send();



layout_header("Stefans Bank");
?>


<body>
<script>
async function addToCart(id){
    const antal = await((await fetch(`/addtocart?id=${id}`)).text())
    console.log(antal)
    document.getElementById("antal").innerText = antal
}


</script>

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
                        <h2>Kunder</h2>
                        <a id="antal" href="javascript:void">Cart</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <a class="listbutton" href="/newcustomer">
        <span class="fas fa-plus mr-2"></span>
        Ny kund
    </a>
    <br/><br/>
    <form method="GET">
        Search:
        <input type="text" name="q" value="<?php echo $q; ?>" />
         <!-- <input type="hidden" name="sortCol"  value="<?php echo $sortCol; ?>" />       -->
    </form>
    <table class="tabulator-table">
        <thead>
            <tr class="tabulator-row">
                <th class="tabulator-cell">
                    National ID
                    <a href="?sortCol=NationalId&sortOrder=asc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=NationalId&sortOrder=desc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>

                <th >
                    Förnamn
                    <a href="?sortCol=GivenName&sortOrder=asc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=GivenName&sortOrder=desc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>Efternamn
                    <a href="?sortCol=Surname&sortOrder=asc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=Surname&sortOrder=desc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>City                
                    <a href="?sortCol=City&sortOrder=asc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=City&sortOrder=desc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th>Country                
                    <a href="?sortCol=Country&sortOrder=asc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-up-a-z"></i></a>
                    
                    <a href="?sortCol=Country&sortOrder=desc&q=<?php echo $q ?>"><i class="fa-solid fa-arrow-down-z-a"></i></a>
                </th>
                <th></th>
            </tr>

        </thead>
        <tbody>
            <?php
            $result = $dbContext->searchCustomers($sortCol,$sortOrder,$q,null,$pageNo,$pageSize);
            foreach($result["data"] as $customer) {
            ?>
                <tr class="tabulator-row">
                    <td >
                        <a href="viewcustomer?id=<?php echo $customer->Id ?>">
                            <?php echo $customer->NationalId ?>
                            </a>
                    </td>
                    <td ><?php echo $customer->GivenName ?></td>
                    <td ><?php echo $customer->Surname ?></td>
                    <td ><?php echo $customer->City ?></td>
                    <td><?php echo $customer->Country ?></td>
                    <td>
                        <button class="listbutton" onclick="javascript:addToCart(<?php echo $customer->Id ?>)" >Add to cart</button>
                    </td>
                </tr>
            <?php
            }
            ?>
    </tbody>
    </table>

    <?php 
        for($i = 1; $i <= $result["num_pages"] ;$i++){
            if($pageNo == $i){
                echo "$i&nbsp;";    // &nbsp; = fusk space så Stefan slapp göra margin i CSS
            }else{
                echo "<a class='listbutton' href='?sortCol=$sortCol&sortOrder=$sortOrder&q=$q&pageNo=$i'>$i</a>&nbsp;"; 
            }
        }
         

    ?>




    <link rel="stylesheet" href="/css/tabulator/tabulator_modified.css">


</div>
    </div>


</main>

<?php
layout_footer();
?>
<!-- 
<script type="module" src="scripts/cart.js"></script> -->
</body>

</html>