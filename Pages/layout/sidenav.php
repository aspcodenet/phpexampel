<?php 
function layout_sidenav($dbContext)  {
?>  
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
                    <?php 
                    if(!$dbContext->getUsersDatabase()->getAuth()->isLoggedIn()){
                    ?>
                        <a href="/user/login">
                        <i class="fas fa-sign-out-alt"></i>
                        Logga in
                        </a>
                    <?php
                    } else {
                        ?>
                        <a href="/user/logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Logga ut
                        </a>
                    <?php
    
                    }

                    ?>
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
                    <a href="/office?id=<?php echo $office->Id ?>"  >
                        <span class="sidebar-icon"><i class="fa-solid fa-building"></i></span>
                        <span class="sidebar-text"><?php echo $office->Name ?></span>
                    </a>
                </li>
            <?php } ?>

        </ul>
    </div>

</nav>

<?php 
}
?>