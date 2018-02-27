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
    <link rel="stylesheet" type="text/css" href="assets/css/quick-view.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/responsive9.css" />
    <title>PANDAGALI</title>
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
<!-- Home slideder-->
<div id="home-slider">
    <div class="container" style="padding: 0">
        <div class="row">
            <div class="col-lg-9 col-lg-push-3 col-md-9 col-md-push-3">
                <div class="homeslider">
                    <div class="content-slide">
                        <ul id="contenhomeslider">
                          <li><a href="#"><img alt="Cavada img" src="assets/data/market/slide/slide1.jpg" title="" /></a></li>
                          <li><a href="#"><img alt="Cavada img" src="assets/data/market/slide/slide2.jpg" title="" /></a></li>
                          <li><a href="#"><img  alt="Cavada img" src="assets/data/market/slide/slide3.jpg" title="" /></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Home slideder-->
<!-- servives -->
<!-- <div class="service">
    <div class="container ">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 service-item equalheight">
            <div class="content">
                <div class="icon">
                    <span><img alt="services" src="assets/data/market/struct.png" /></span>
                </div>
                <div class="info">
                    <h3>Shipping Service</h3>
                    <span>We facilitate by shiping to your current location</span>
                </div>
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 service-item equalheight">
            <div class="content">
                <div class="icon">
                    <span class="lnr lnr-sync"></span>
                </div>
                <div class="info">
                    <h3>Return </h3>
                    <span>Cansel your order within 24 hours</span>
                </div>
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 service-item equalheight">
            <div class="content">
                <div class="icon">
                    <span class="lnr lnr-phone-handset"></span>
                </div>
                <div class="info" >
                    <h3>24/7 support</h3>
                    <span>call +250784848236 fur support</span>
                </div>
            </div>
        </div>
    </div>
</div>
 --><!-- end services -->

<!---->
<div class="content-page" style="margin-top: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-lg-3"></div>
            <div class="product-single main-product col-lg-9 col-md-9">
                <div class="tab-container row">
                    <div class="navbar nav-menu" style="margin-top:unset;">
                        <div class="navbar-label"><h3 class="title"><span class="icon fa fa-star"></span><span class="label-prod">Available Goods</span></h3></div>
                    </div>
                    <div id="tab-1" class="tab-panel active">
                        <ul class="product-list" data-dots="false" data-loop="true" data-nav = "true" data-margin = "0" data-autoplayTimeout="1000" data-autoplayHoverPause = "true" data-responsive='{"0":{"items":1},"480":{"items":2}, "991":{"items":3},"1200":{"items":4}}'>
                        <?php
    						include ("db.php");
    						$sql2 = $db->query("SELECT * FROM `items1` WHERE quantity > 0 ORDER BY itemId DESC");
    						while($row = mysqli_fetch_array($sql2))
    						{
                                $selectPercentage = $db->query("SELECT * FROM `charges` WHERE chargedFrom = 'saler'");
                                $rowpercentage = mysqli_fetch_array($selectPercentage);
                                $percentage = $rowpercentage['percentage'];
                                $postTitle = $row['itemName'];
                                $priceStatus = $row['unit'];
                                $price = ($row['unityPrice'] + (($percentage/100)*$row['unityPrice']));
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
                            <?php ;} ?>
                         </ul>
                    
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="row">
            <!-- Column left -->
            <div class="stepwizardfooter" style="background-color: #1976d2;">
                <div class="row setup-content" id="step-2">
                    <div class="col-md-3"></div>
                    <div class="col-xs-6">
                        <form class="trackingcode-form-search toggle-mobile" name="trackingCodeform">
                            <div class="input-search">
                                <input onkeyup="trackingCode()" type="text" name="trackingcode"  placeholder="Track Your Order" class="form-control">
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
<script>
(function cart(){ 
var cart = '1';
    $.ajax({
        type : "GET",
        url : "cartBack.php",
        dataType : "html",
        cache : "false",
        data : {                
            cart : cart,
        },
        success : function(html, textStatus){
            $("#cartDiv").html(html);
        },
        error : function(xht, textStatus, errorThrown){
            alert("Error : " + errorThrown);
        }
    });
})();
</script>
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