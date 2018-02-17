<?php

if (isset($_GET['CatID'])) {
    include("db.php");
    $recCatID = $_GET['CatID'];
    $selectCat = $db ->query("SELECT * FROM levels WHERE id = '$recCatID'");
    $cat = mysqli_fetch_array($selectCat);
    $catName = $cat['name'];
    $catParent = $cat['parentId'];
    ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" type="text/css" href="assets/lib/bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/font-awesome/css/font-awesome.min.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/Linearicons/css/demo.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/select2/css/select2.min.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/jquery.bxslider/jquery.bxslider.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/owl.carousel/owl.carousel.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/fancyBox/jquery.fancybox.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/animate.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/index9.css" />
            <link rel="stylesheet" type="text/css" href="css/stepwizard.css" />
            <!--[if IE]>
            <style>.form-category .icon {display: none;}</style>
            <![endif]--> 
            <link rel="stylesheet" type="text/css" href="assets/css/quick-view.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/responsive9.css" />
            <title>SAWASAWA</title>
            <style type="text/css">
                .itemheight {
                    max-height:370px;
                    min-height:370px;
                }
                .trackingCode {    
                    padding: 13px;
                    width: 300px;
                    text-align: left;
                    margin: auto;
                    font-weight: bold;
                }
                .trackingcode-form-search {
                    margin: 45px auto;
                    padding: 13px;
                    width: 300px;
                }
            </style>
        </head>
        <body class="home market-home">
        <!-- HEADER -->
        <?php include("header.php");?>
        <!-- end header -->
        <div class="content-page" style="margin-top: 20px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-lg-3"></div>
                    <div class="product-single main-product col-lg-9 col-md-9">
                        <div class="tab-container row">
                            <div class="navbar nav-menu" style="margin-top:unset;">
                                <div class="navbar-label"><h3 class="title"><span class="icon fa fa-star"></span><span class="label-prod"><?php echo $catName; ?></span></h3></div>
                            </div>
                            <div id="tab-1" class="tab-panel active">
                                <ul class="product-list" data-dots="false" data-loop="true" data-nav = "true" data-margin = "0" data-autoplayTimeout="1000" data-autoplayHoverPause = "true" data-responsive='{"0":{"items":1},"480":{"items":2}, "991":{"items":3},"1200":{"items":4}}'>
                                <?php
                                    include ("db.php");
                                    $sql2 = $db->query("SELECT itemId, itemName, productCode, quantity, unit,unityPrice,inDate, postedBy, itemCompanyCode, description, postDeadline, ncpp, id, name, parentId FROM `items1` JOIN `levels` WHERE `items1`.productCode = `levels`.id AND (id = '$recCatID' OR parentId = '$recCatID') AND quantity > 0 ORDER BY rand()");
                                    $countResult = mysqli_num_rows($sql2);
                                    if ($countResult > 0) {
                                        while($row = mysqli_fetch_array($sql2))
                                        {
                                            $postTitle = $row['itemName'];
                                            $priceStatus = $row['unit'];
                                            $price = $row['unityPrice'];
                                        ?>
                                        <li class="item col-md-4 itemheight">
                                            <div class="left-block">
                                                <a href="post.php?postId=<?php echo $row['itemId'];?>">
                                                    <img class="img-responsive" alt="<?php echo $postTitle;?>" src="products/<?php echo $row['itemId'];?>.jpg"/>
                                                </a>
                                                <div class="add-to-cart">
                                                    <a title="Add to Cart" href="post.php?postId=<?php echo"".$row['itemId']."";?>">View Product</a>
                                                </div>
                                            </div>
                                            <div class="right-block">
                                                <div class="left-p-info">
                                                    <h5 class="product-name"><a href="post.php?postId=<?php echo"".$row['itemId']."";?>"><?php echo $postTitle;?></a></h5>
                                                </div>
                                                <div class="content_price">
                                                    <span class="price product-price"><?php echo number_format($price);?> Rwf</span>
                                                </div>
                                            </div>
                                        </li>
                                        <?php
                                        }
                                    }
                                    else {
                                        echo '<center><b style="padding: 100px">No product yet<b></center>';
                                    }

                                    ?>
                                 </ul>
                            
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <div class="row">
                        <!-- Column left -->
                        <div class="stepwizardfooter">
                            <div class="row setup-content" id="step-2">
                                <div class="col-md-3"></div>
                                <div class="col-xs-6">
                                    <form class="trackingcode-form-search toggle-mobile" name="trackingCodeform">
                                        <div class="input-search">
                                            <input onkeyup="trackingCode()" type="text" name="trackingcode"  placeholder="Enter tracking code to see your order" class="form-control">
                                            <div class="trackingCode" id="trackingCodeSpace"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end part -->
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="scroll_top" title="Scroll to Top" style="display: inline;">Scroll</a>
        <!-- Script-->
        <script type="text/javascript" src="assets/lib/jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/lib/select2/js/select2.min.js"></script>
        <script type="text/javascript" src="assets/lib/jquery.bxslider/jquery.bxslider.min.js"></script>
        <script type="text/javascript" src="assets/lib/owl.carousel/owl.carousel.min.js"></script>
        <script type="text/javascript" src="assets/lib/jquery.countdown/jquery.countdown.min.js"></script>
        <script type="text/javascript" src="assets/lib/fancyBox/jquery.fancybox.js"></script>
        <script type="text/javascript" src="assets/lib/jquery.elevatezoom.js"></script>
        <script type="text/javascript" src="assets/js/theme-script.js"></script>
        <script type="text/javascript" src="assets/js/equalheight.js"></script>
        <script src="js/jquery.js"></script>    
        <script src="http://maps.google.com/maps/api/js?key=AIzaSyAlKttaE2WuI1xKpvt-f7dBOzcBEHRaUBA&libraries=places"></script>
        <script type="text/javascript">
            function trackingCode() {
                xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET","trackingcode.php?trackingCode="+document.trackingCodeform.trackingcode.value,false);
                xmlhttp.send(null);
                document.getElementById('trackingCodeSpace').innerHTML=xmlhttp.responseText;
            }
        </script>
        </body>
        </html>
        <?php
    }

?>
