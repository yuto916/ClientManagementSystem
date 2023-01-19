<!doctype html>
<html lang="en">

  <head>
    <!--
	Filename: <?php echo $file . "\n"; ?>
	Date: <?php echo $date . "\n"; ?>
	-->

    <?php 
        session_start();
        ob_start();

		require "./includes/constants.php"; 
        require "./includes/db.php";
        require "./includes/functions.php";

        $message = flashMessage();
	?>



<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/styles.css" rel="stylesheet">
  </head>



  <body>
    <!--     - - - - - - - - - - - Determine if displaying "Sign In" or "Sign Out" - - - - - - - - - - -     -->
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">CMS</a>
        <ul class="navbar-nav px-3">
            <?php if(!isLoggedIn('user')): ?>
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="../cms/sign-in.php">Sign In</a>
                </li>

            <?php else: ?>
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="../cms/sign-out.php">Sign Out</a>
                </li>

            <?php endif ?>
        </ul>
    </nav>
    <!--     - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -     -->
    



    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <!--     - - - - - - - - - - - - If a user is not logged in - - - - - - - - - - - -    -->
                    <?php if(!isLoggedIn('user')): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/index.php">Home Page</a>
                        </li>
                    <!--     - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -     -->



                    <!--     - - - - - - If a user is logged in and not Admin or Salesperson - - - - -     -->
                    <?php elseif(isLoggedIn('user') && isAdmin() == false && isSalesPerson() == false): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/index.php">Home Page</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/dashboard.php">Dashboard </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/change-password.php">Change Password</a>
                        </li>
                    <!--     - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -     -->
                    


                    <!--     - - - - - - - - - If a user is logged in and is Admin - - - - - - - - -     -->
                    <?php elseif(isLoggedIn('user') && isAdmin() == true): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/index.php">Home Page</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/dashboard.php">Dashboard</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/salespeople.php">Sales People</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/clients.php">Clients</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/change-password.php">Change Password</a>
                        </li>
                    <!--     - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -     -->
                    


                    <!--     - - - - - - - If a user is logged in and is a sales person - - - - - - -     -->
                    <?php elseif(isLoggedIn('user') && isSalesPerson() == true): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/index.php">Home Page</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/dashboard.php">Dashboard</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/clients.php">Clients</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/calls.php">Calls</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="../cms/change-password.php">Change Password</a>
                        </li>
                    <!--     - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -     -->

                    <?php endif ?>
                </ul>  
                </div>
            </nav>
        
    <main class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
