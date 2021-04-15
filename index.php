<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: welcome.php");
  exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Sharee Online</title>
    <link rel="shortcut icon" href="img/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/slick-theme.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    
</head>

<body>
    <!-- push menu-->
    <div class="pushmenu menu-home5">
        <div class="menu-push">
            <span class="close-left js-close"><i class="ion-ios-close-empty f-40"></i></span>
            <div class="clearfix"></div>
            <form role="search" method="get" id="searchform" class="searchform" action="/search">
                <div>
                    <label class="screen-reader-text" for="q"></label>
                    <input type="text" placeholder="Search for products" value="" name="q" id="q" autocomplete="off">
                    <input type="hidden" name="type" value="product">
                    <button type="submit" id="searchsubmit"><i class="ion-ios-search-strong"></i></button>
                </div>
            </form>
            <ul class="nav-home5 js-menubar">
                <li class="level1 active dropdown">
                    <a href="#">Home</a>
                    <span class="icon-sub-menu"></span>
                </li>
                <li class="level1 active dropdown">
                    <a href="About.html" title="Shop">About</a>
                    <span class="icon-sub-menu"></span>
                    <div class="menu-level1 js-open-menu">
                        
                        <div class="clearfix"></div>
                    </div>
                </li>
                <li class="level1 active dropdown"><a href="#">Mega Menu</a></li>
                
                <li class="level1">
                    <a href="Blog.html">Blog</a>
                    <span class="icon-sub-menu"></span>
                </li>
            </ul>
            <ul class="mobile-account">
                <li><a href=""><i class="fa fa-unlock-alt"></i>Login</a></li>
                <li><a href=""><i class="fa fa-user-plus"></i>Register</a></li>
                <li><a href=""><i class="fa fa-heart"></i>Wishlist</a></li>
            </ul>
            <h4 class="mb-title">connect and follow</h4>
            <div class="mobile-social mg-bottom-30">
                <a href=""><i class="fa fa-facebook"></i></a>
                <a href=""><i class="fa fa-twitter"></i></a>
                <a href=""><i class="fa fa-google-plus"></i></a>
            </div>
        </div>
    </div>
    <!-- end push menu-->
    <!-- Push cart -->
    <div class="pushmenu pushmenu-left cart-box-container">
        <div class="cart-list">
            <div class="cart-list-heading">
                <h3 class="cart-title">My cart</h3>
                <span class="close-left js-close"><i class="ion-ios-close-empty"></i></span>
            </div>
            <div class="cart-inside">
                <ul class="list">
                    <li class="item-cart">
                        <div class="product-img-wrap">
                            <a href="#" title="Product"><img src="img/product/cart_product_1.jpg" alt="Product" class="img-responsive"></a>
                        </div>
                        <div class="product-details">
                            <div class="inner-left">
                                <div class="product-name"><a href="#">cotton sharee</a></div>
                                <div class="product-price"><span>$100.9</span></div>
                                <div class="cart-qtt">
                                    <button type="button" class="quantity-left-minus btn btn-number js-minus" data-type="minus" data-field="">
                                        <span class="minus-icon"><i class="ion-ios-minus-empty"></i></span>
                                    </button>
                                    <input type="text" name="number" value="1" class="product_quantity_number js-number">
                                    <button type="button" class="quantity-right-plus btn btn-number js-plus" data-type="plus" data-field="">
                                        <span class="plus-icon"><i class="ion-ios-plus-empty"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="item-cart">
                        <div class="product-img-wrap">
                            <a href="#" title="Product"><img src="img/product/cart_product_2.jpg" alt="Product" class="img-responsive"></a>
                        </div>
                        <div class="product-details">
                            <div class="inner-left">
                                <div class="product-name"><a href="#">cotton sharee</a></div>
                                <div class="product-price"><span>$200.9</span></div>
                                <div class="cart-qtt">
                                    <button type="button" class="quantity-left-minus btn btn-number js-minus" data-type="minus" data-field="">
                                        <span class="minus-icon"><i class="ion-ios-minus-empty"></i></span>
                                    </button>
                                    <input type="text" name="number" value="1" class="product_quantity_number js-number">
                                    <button type="button" class="quantity-right-plus btn btn-number js-plus" data-type="plus" data-field="">
                                        <span class="plus-icon"><i class="ion-ios-plus-empty"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="cart-bottom">
                    <div class="cart-form">
                        <div class="cart-note-form">
                            <label for="CartSpecialInstructions" class="cart-note cart-note_text_label small--text-center">Special Offer:</label>
                            <textarea rows="6" name="note" id="CartSpecialInstructions" class="cart-note__input form-control note--input"></textarea>
                        </div>
                    </div>
                    <div class="cart-button mg-top-30">
                        <a class="zoa-btn checkout" href="#" title="">Check out</a>
                    </div>
                </div>
            </div>
            <!-- End cart bottom -->
        </div>
    </div>
    <!-- End pushcart -->
    <!-- Search form -->
    <div class="search-form-wrapper header-search-form">
        <div class="container">
            <div class="search-results-wrapper">
                <div class="btn-search-close">
                    <i class="ion-ios-close-empty"></i>
                </div>
            </div>
            <ul class="zoa-category text-center">
                <li><a href="">All Categories</a></li>
                <li><a href="">Woman</a></li>
                <li><a href="">Man</a></li>
                <li><a href="">Accessories</a></li>
                <li><a href="">Kid</a></li>
                <li><a href="">Others</a></li>
            </ul>
            <form method="get" action="/search" role="search" class="search-form  has-categories-select">
                <input name="q" class="search-input" type="text" value="" placeholder="Enter your keywords..." autocomplete="off">
                <input type="hidden" name="post_type" value="product">
                <button type="submit" id="search-btn"><i class="ion-ios-search-strong"></i></button>
            </form>
        </div>
    </div>
    <!-- End search form -->
    <!-- Account -->
    <div class="account-form-wrapper">
        <div class="container">
            <div class="search-results-wrapper">
                <div class="btn-search-close">
                    <i class="ion-ios-close-empty black"></i>
                </div>
            </div>
            <div class="account-wrapper">
                <ul class="account-tab text-center">
                    <li class="active"><a data-toggle="tab" href="#login">Login</a></li>
                    <li><a data-toggle="tab" href="#register">Register</a></li>
                </ul>
                <div class="tab-content">
                    <div id="login" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-md-4">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            <a href="reset-password.php"><img src="img/Icon_Lock.jpg" alt="Icon_User.jpg"> reset password</a>
                            </form>
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- End Account -->
    </div>
    <div class="wrappage">
        <header id="header" class="header-v2">
            <div class="topbar hidden-xs hidden-sm">
                <button type="button" class="js-promo close"><i class="ion-ios-close-empty black" aria-hidden="true"></i></button>
                <div class="container container-content">
                    <div class="row flex">
                        <div class="col col-sm-4">
                            <div class="topbar-left">
                                <span>Hotline: +880 13036 83106</span>
                                <div class="topbar-social">
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col col-sm-4 justify-content-center">
                            <p>Winter sale discount off 50%! <a href="">Shop Now</a></p>
                        </div>
                        <div class="col col-sm-4 justify-content-end">
                            <div class="topbar-right">
                                <div class="element element-currency">
                                    <a id="label3" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    
                                      <span>৳ BDT</span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="label3">
                                        <li><a href="#">USD</a></li>
                                        <li><a href="#">BDT</a></li>
                                    
                                    </ul>
                                </div>
                                <div class="element element-leaguage">
                                    <a id="label2" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <img src="img/icon-l.png" alt="">
                                      <span>BANGLA</span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="label2">
                                        <li><a href="#">EN</a></li>
                                        <li><a href="#">BD</a></li>
                    
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-center">
                <div class="container container-content">
                    <div class="row flex align-items-center justify-content-between">
                        <div class="col-md-4 col-xs-4 col-sm-4 col2 hidden-lg hidden-md">
                            <div class="topbar-right">
                                <div class="element">
                                    <a href="#" class="icon-pushmenu js-push-menu">
                                        <svg width="26" height="16" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 66 41" style="enable-background:new 0 0 66 41;" xml:space="preserve">
                                            <style type="text/css">
                                            .st0 {
                                                fill: none;
                                                stroke: #000000;
                                                stroke-width: 3;
                                                stroke-linecap: round;
                                                stroke-miterlimit: 10;
                                            }
                                            </style>
                                            <g>
                                                <line class="st0" x1="1.5" y1="1.5" x2="64.5" y2="1.5" />
                                                <line class="st0" x1="1.5" y1="20.5" x2="64.5" y2="20.5" />
                                                <line class="st0" x1="1.5" y1="39.5" x2="64.5" y2="39.5" />
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-4 col-sm-4 col2 justify-content-center">
                            <a href="#"><img src="img/logo2.png" alt="" class="img-reponsive"></a>
                        </div>
                        <div class="col-md-9 col-xs-4 col-sm-4 col2 flex justify-content-end">
                            <ul class="nav navbar-nav js-menubar hidden-xs hidden-sm">
                                <li class="level1 active dropdown"><a href="#" title="Home">Home</a>
                                    <span class="plus js-plus-icon"></span>
                
                                </li>
                                <li class="level1 dropdown hassub"><a href="About.html" title="Shop">About</a>
                                    <span class="plus js-plus-icon"></span>
                                    
                                </li>
                                
                               
                                <li class="level1 active dropdown">
                                    <a href="Blog.html" title="Blog">Blog</a>
                                </li>
                            </ul>
                            <div class="topbar-left">
                                <div class="element element-search hidden-xs hidden-sm">
                                    <a href="#" class="zoa-icon search-toggle">
                                        <svg width="20" height="20" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 90 90" style="enable-background:new 0 0 90 90;" xml:space="preserve">
                                            <g>
                                                <path d="M0,39.4C0,50,4.1,59.9,11.6,67.3c7.4,7.5,17.3,11.6,27.8,11.6c9.5,0,18.5-3.4,25.7-9.5l19.8,19.7c1.2,1.2,3.1,1.2,4.2,0
        c0.6-0.6,0.9-1.3,0.9-2.1s-0.3-1.5-0.9-2.1L69.3,65.1c6.2-7.1,9.5-16.2,9.5-25.7c0-10.5-4.1-20.4-11.6-27.9C59.9,4.1,50,0,39.4,0
        C28.8,0,19,4.1,11.6,11.6S0,28.9,0,39.4z M63.1,15.8c6.3,6.3,9.8,14.7,9.8,23.6S69.4,56.7,63.1,63s-14.7,9.8-23.6,9.8
        S22.2,69.3,15.9,63C9.5,56.8,6,48.4,6,39.4s3.5-17.3,9.8-23.6S30.5,6,39.4,6S56.8,9.5,63.1,15.8z" />
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="element element-user hidden-xs hidden-sm">
                                    <a href="#" class="zoa-icon js-user">
                                        <svg width="19" height="20" version="1.1" id="Layer_3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 102.8" style="enable-background:new 0 0 100 102.8;" xml:space="preserve">
                                            <g>
                                                <path d="M75.7,52.4c-2.1,2.3-4.4,4.3-7,6C82.2,58.8,93,69.9,93,83.5v12.3H7V83.5c0-13.6,10.8-24.7,24.3-25.1c-2.6-1.7-5-3.7-7-6
        C10.3,55.9,0,68.5,0,83.5v15.8c0,1.9,1.6,3.5,3.5,3.5h93c1.9,0,3.5-1.6,3.5-3.5V83.5C100,68.5,89.7,55.9,75.7,52.4z" />
                                                <g>
                                                    <path d="M50,58.9c-16.2,0-29.5-13.2-29.5-29.5S33.8,0,50,0s29.5,13.2,29.5,29.5S66.2,58.9,50,58.9z M50,7
            C37.6,7,27.5,17.1,27.5,29.5S37.6,51.9,50,51.9s22.5-10.1,22.5-22.5S62.4,7,50,7z" />
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="element element-cart">
                                    <a href="#" class="zoa-icon icon-cart">
                                        <svg width="20" height="20" version="1.1" id="Layer_4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 55.4 55.4" style="enable-background:new 0 0 55.4 55.4;" xml:space="preserve">
                                            <g>
                                                <rect x="0.2" y="17.4" width="55" height="3.4" />
                                            </g>
                                            <g>
                                                <polygon points="7.1,55.4 3.4,27.8 3.4,24.1 7.3,24.1 7.3,27.6 10.5,51.6 44.9,51.6 48.1,27.6 48.1,24.1 52,24.1 52,27.9 
        48.3,55.4   " />
                                            </g>
                                            <g>
                                                <path d="M14,31.4c-0.1,0-0.3,0-0.5-0.1c-1-0.2-1.6-1.3-1.4-2.3L19,1.5C19.2,0.6,20,0,20.9,0c0.1,0,0.3,0,0.4,0
        c0.5,0.1,0.9,0.4,1.2,0.9c0.3,0.4,0.4,1,0.3,1.5l-6.9,27.5C15.6,30.8,14.8,31.4,14,31.4z" />
                                            </g>
                                            <g>
                                                <path d="M41.5,31.4c-0.9,0-1.7-0.6-1.9-1.5L32.7,2.4c-0.1-0.5,0-1.1,0.3-1.5s0.7-0.7,1.2-0.8c0.1,0,0.3,0,0.4,0
        c0.9,0,1.7,0.6,1.9,1.5L43.4,29c0.1,0.5,0,1-0.2,1.5c-0.3,0.5-0.7,0.8-1.1,0.9c-0.2,0-0.3,0-0.4,0.1C41.6,31.4,41.6,31.4,41.5,31.4
        z" />
                                            </g>
                                        </svg>
                                        <span class="count cart-count">0</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- /header -->
        <!-- content -->
         <div class="slide v3">
            <div class="js-slider-v4">
                <div class="slide-img">
                    <img src="img/slide/slider-1-home-1.png" alt="" class="img-responsive">
                    <div class="box-center content2">
                        <h3>শাড়ির সংগ্রহ</h3>
                        <a href="" class="slide-btn">Shop Now</a>
                    </div>
                </div>
                <div class="slide-img">
                    <img src="img/slide/slider-2-home-1.png" alt="" class="img-responsive">
                    <div class="box-center content1">
                        
                    </div>
                </div>
                
            </div>
           <div class="custom">
                <div class="pagingInfo"></div>
            </div>
        </div>
        <!-- End content -->
        <div class="trend-product pad">
            <div class="container container-content">
                <div class="row first">
                    <div class="col-md-5 col-sm-6 col-xs-12">
                        <div class="trend-img hover-images">
                            
                                <img class="img-responsive" src="img/home1/trend.png" alt="">
                            
                            <div class="box-center align-items-end">
                                <h3 class="zoa-category-box-title">
                                    <a href="#">#trend</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-6 col-xs-12">
                        <div class="row engoc-row-equal">
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 product-item">
                                <div class="product-img">
                                    <a href=""><img src="img/home1/product_1b.jpg" alt="" class="img-responsive"></a>
                                    <div class="ribbon zoa-sale"><span>-15%</span></div>
                                    <div class="product-button-group">
                                        <a href="#" class="zoa-btn zoa-quickview">
                                    <span class="zoa-icon-quick-view"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-wishlist">
                                    <span class="zoa-icon-heart"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-addcart">
                                    <span class="zoa-icon-cart"></span>
                                </a>
                                    </div>
                                </div>
                                <div class="product-info text-center">
                                    <h3 class="product-title">
                                        <a href="">টাঙ্গাইল সুতি শাড়ি</a>
                                    </h3>
                                    <div class="product-price">
                                        <span class="old">৳ ১,৫৫০/-</span>
                                        <span>৳ ১,৫৫০/-</span>
                                    </div>
                                    <div class="color-group">
                                        <a href="#" class="circle gray"></a>
                                        <a href="#" class="circle yellow active"></a>
                                        <a href="#" class="circle white"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 product-item">
                                <div class="product-img">
                                    <a href=""><img src="img/home1/product_2.jpg" alt="" class="img-responsive"></a>
                                    <div class="ribbon zoa-hot"><span>Hot</span></div>
                                    <div class="product-button-group">
                                        <a href="#" class="zoa-btn zoa-quickview">
                                    <span class="zoa-icon-quick-view"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-wishlist">
                                    <span class="zoa-icon-heart"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-addcart">
                                    <span class="zoa-icon-cart"></span>
                                </a>
                                    </div>
                                </div>
                                <div class="product-info text-center">
                                    <h3 class="product-title">
                                        <a href="">হাফ সিল্ক শাড়ি</a>
                                    </h3>
                                    <div class="product-price">
                                        <span>৳ ১,৫৫০/-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 product-item">
                                <div class="product-img">
                                    <a href=""><img src="img/home1/product_3.jpg" alt="" class="img-responsive"></a>
                                    <div class="ribbon zoa-new"><span>New</span></div>
                                    <div class="product-button-group">
                                        <a href="#" class="zoa-btn zoa-quickview">
                                    <span class="zoa-icon-quick-view"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-wishlist">
                                    <span class="zoa-icon-heart"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-addcart">
                                    <span class="zoa-icon-cart"></span>
                                </a>
                                    </div>
                                </div>
                                <div class="product-info text-center">
                                    <h3 class="product-title">
                                        <a href="">স্ক্রিন প্রিন্টেড লাল কটন শাড়</a>
                                    </h3>
                                    <div class="product-price">
                                        <span>৳ ১,৫৫০/-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 product-item">
                                <div class="product-img">
                                    <a href=""><img src="img/home1/product_4.jpg" alt="" class="img-responsive"></a>
                                    <div class="product-button-group">
                                        <a href="#" class="zoa-btn zoa-quickview">
                                    <span class="zoa-icon-quick-view"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-wishlist">
                                    <span class="zoa-icon-heart"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-addcart">
                                    <span class="zoa-icon-cart"></span>
                                </a>
                                    </div>
                                </div>
                                <div class="product-info text-center">
                                    <h3 class="product-title">
                                        <a href="">সিল্ক বুটিকস্ শাড়</a>
                                    </h3>
                                    <div class="product-price">
                                        <span>৳ ১,৫৫০/-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 product-item">
                                <div class="product-img">
                                    <a href=""><img src="img/home1/product_5.jpg" alt="" class="img-responsive"></a>
                                    <div class="product-button-group">
                                        <a href="#" class="zoa-btn zoa-quickview">
                                    <span class="zoa-icon-quick-view"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-wishlist">
                                    <span class="zoa-icon-heart"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-addcart">
                                    <span class="zoa-icon-cart"></span>
                                </a>
                                    </div>
                                </div>
                                <div class="product-info text-center">
                                    <h3 class="product-title">
                                        <a href="">টাঙ্গাইল কটন শাড়ি</a>
                                    </h3>
                                    <div class="product-price">
                                        <span>৳ ১,৫৫০/-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 product-item">
                                <div class="product-img">
                                    <a href=""><img src="img/home1/product_6.jpg" alt="" class="img-responsive"></a>
                                    <div class="ribbon zoa-new"><span>trend</span></div>
                                    <div class="product-button-group">
                                        <a href="#" class="zoa-btn zoa-quickview">
                                    <span class="zoa-icon-quick-view"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-wishlist">
                                    <span class="zoa-icon-heart"></span>
                                </a>
                                        <a href="#" class="zoa-btn zoa-addcart">
                                    <span class="zoa-icon-cart"></span>
                                </a>
                                    </div>
                                </div>
                                <div class="product-info text-center">
                                    <h3 class="product-title">
                                        <a href="">পিউর কটন শাড়</a>
                                    </h3>
                                    <div class="product-price">
                                        <span>৳ ১,৫৫০/-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="single-banner">
            <div class="container container-content">
                <div class="banner-img hover-images">
                    <img src="img/home1/home-1-bg.png" alt="" class="img-responsive">
                
                    <div class="box-center">
                        <div class="content">
                            <a class="text" href="">#Winter collect</a>
                            <h2>-50%</h2>
                            <a href="#" class="zoa-btn btn-shopnow">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="newsletter">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 col-sm-6 col-xs-12">
                        <div class="newsletter-heading">
                            <h3>যোগাযোগ </h3>
                            <p>Subscribe for latest stories and promotions (35% sale)</p>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-6 col-xs-12 flex-end">
                        <form class="form_newsletter" action="#" method="post">
                            <input type="email" value="" placeholder="Enter your emaill" name="EMAIL" id="mail" class="newsletter-input form-control">
                            <button id="subscribe" class="button_mini zoa-btn" type="submit">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="policy pad bd-top">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-3 policy-item">
                        <div class="policy-icon"><img src="img/policy/icon_1.png" alt=""></div>
                        <div class="policy-content">
                            <h3>Free shipping</h3>
                            <p>on all orders over $49.00</p>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-3 policy-item">
                        <div class="policy-icon"><img src="img/policy/icon_2.png" alt=""></div>
                        <div class="policy-content">
                            <h3>15 days returns</h3>
                            <p>moneyback guarantee</p>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-3 policy-item">
                        <div class="policy-icon"><img src="img/policy/icon_3.png" alt=""></div>
                        <div class="policy-content">
                            <h3>Secure checkout</h3>
                            <p>100% protected by Paypal</p>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-3 policy-item">
                        <div class="policy-icon"><img src="img/policy/icon_4.png" alt=""></div>
                        <div class="policy-content">
                            <h3>100% free warranty</h3>
                            <p>moneyback guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="footer v1 bd-top">
            <div class="container">
                <div class="f-content">
                    <div class="f-col">
                        <div class="social">
                            <a href=""><i class="fa fa-rss"></i></a>
                            <a href=""><i class="fa fa-facebook"></i></a>
                            <a href=""><i class="fa fa-twitter"></i></a>
                            <a href=""><i class="fa fa-linkedin"></i></a>
                            <a href=""><i class="fa fa-rss"></i></a>
                        </div>
                    </div>
                    <div class="f-col align-items-center">
                        <p>© 2021 <a href="">Saree Online</a></p>
                        <ul>
                            <li><a href="">Privacy Policy</a></li>
                            <li><a href="">Terms of Use</a></li>
                        </ul>
                    </div>
                    <div class="f-col">
                        <a href=""><img src="img/credit-card-icons.png" alt="" class="img-responsive"></a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->
    </div>
    <a href="#" class="zoa-btn scroll_top"><i class="ion-ios-arrow-up"></i></a>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/countdown.js"></script>
    <script src="js/main.js"></script>
</body>

</html>