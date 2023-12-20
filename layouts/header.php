<?php $user = current_user(); ?>
<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "Inventory Management System";?>
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
  
 
  <?php  if ($session->isUserLoggedIn(true)): ?>

    <header id="header">
    
      <div class="logo pull-left" style="display: flex;"> 
          <div class="openbtn" onclick="toggleNav()" style="margin-right:15px;margin-left:15px;">
              <i class="fa fa-bars" ></i>
          </div>  
      Inventory 
        
      </div>
      <div class="header-content">
      <div class="header-date pull-left">
        
      </div>
      
      <div class="pull-right clearfix" >
        <ul class="info-menu list-inline list-unstyled">
          <li class="profile">
            <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
              <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
              <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                  <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                      <i class="glyphicon glyphicon-user"></i>
                      Profile
                  </a>
              </li>
             <li>
                 <a href="edit_account.php" title="edit account">
                     <i class="glyphicon glyphicon-cog"></i>
                     Settings
                 </a>
             </li>
             <li class="last">
                 <a href="logout.php">
                     <i class="glyphicon glyphicon-off"></i>
                     Logout
                 </a>
             </li>
           </ul>
          </li>
        </ul>
      </div>
     </div>


    </header>




    <div class="sidebar">
      <?php if($user['user_level'] === '1'): ?>
        <!-- admin menu -->
        <?php include_once('admin_menu.php');?>

      <?php elseif($user['user_level'] === '2'): ?>
        <!-- Special user -->
        <?php include_once('special_menu.php');?>

      <?php elseif($user['user_level'] === '3'): ?>
        <!-- User menu -->
        <?php include_once('user_menu.php');?>

      <?php endif;?>

   </div>


   <?php else: ?>
    <header id="header">
      <div class="logo pull-left"> Inventory System
        <!-- <a href="home.php" style="text-decoraction:none;color:white;">Inventory System</a> -->
      </div>
      <div class="header-content">
      <div class="header-date pull-left">
        
      </div>
      <div class="loginbutton pull-right" style="margin-right:20px;">
      <form action="index.php" class="clearfix" >
        
        <div class="form-group" >
            <button type="submit" class="btn btn-danger" style="border-radius:10%;">Login</button>
        </div>
        
    </form>
      </div>
     </div>
    </header>
    <div class="sidebar">
      <?php include_once('user_menu.php');?>
    </div>
    
<?php endif;?>

<script>
  /* Toggle the sidebar open or closed and update body class */
  function toggleNav() {
    var body = document.body;
    var sidepanel = document.getElementById("mySidepanel");
    
    if (body.classList.contains("sidepanel-closed")) {
      // If the sidebar is closed, open it
      body.classList.remove("sidepanel-closed");
      body.classList.add("sidepanel-open");
      sidepanel.style.width = "250px";
    } else {
      // If the sidebar is open, close it
      body.classList.remove("sidepanel-open");
      body.classList.add("sidepanel-closed");
      sidepanel.style.width = "0";
    }
  }
</script>

<div class="page">
  <div class="container-fluid">
