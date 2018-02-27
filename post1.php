<?php
    session_start();
    if (isset($_SESSION['Longitude'])) {
        $cityLat = $_SESSION['Latitude'];
        $cityLng = $_SESSION['Longitude'];
    }
    else {
        if(isset($_GET['postId'])) {
            $postId = $_GET['postId'];
            header("location: clientlocation.php?postId=$postId");
        }
        else {
            header("location: index.php");
        }
    }

    if(isset($_GET['postId']))
    {
        include("db.php");
        $selectPercentage = $db->query("SELECT * FROM `charges` WHERE chargedFrom = 'saler'");
        $rowpercentage = mysqli_fetch_array($selectPercentage);
        $percentage = $rowpercentage['percentage'];
        $postId = $_GET['postId'];
        $sql = $db->query("SELECT * FROM `items1` WHERE itemId = '$postId'");
        while($row = mysqli_fetch_array($sql))
        {
            $postTitle = $row['itemName'];
            $quantity = $row['quantity'];
            $price = ($row['unityPrice'] + (($percentage/100)*$row['unityPrice']));
            $priceStatus = $row['unit'];
            $postDesc = $row['description'];
            $postedDate = $row['inDate'];
            $postedBy = $row['itemCompanyCode'];
            $productCode = $row['productCode'];
            $ncpp = $row['ncpp'];
            $newNncpp = $ncpp+1;
        }
        $sqlncpp = $db->query("UPDATE `items1` SET ncpp=$newNncpp WHERE `itemId` = '$postId'");

        // Related ITEMS
        $relatedItems="";
        $getrelatedItems = $db->query("
            SELECT I.`itemId`, I.`itemName`, `I.description`, I.`inDate`, I.`itemCompanyCode`, I.`productCode`,quantity,I.`unit`, I.`unityPrice`    FROM `items1` I  WHERE `productCode` = '$productCode' AND itemId != '$postId' LIMIT 3");
        $countRelated = mysqli_num_rows($getrelatedItems);
        if($countRelated > 0)
        {
            while($row = mysqli_fetch_array($getrelatedItems))
            {
                $selectPercentage = $db->query("SELECT * FROM `charges` WHERE chargedFrom = 'saler'");
                $rowpercentage = mysqli_fetch_array($selectPercentage);
                $percentage = $rowpercentage['percentage'];
                $relatedItems.='
                <li class="item">
                    <div class="left-block">
                        <a href="post.php?postId='.$row['itemId'].'">
                            <img class="img-responsive" alt="'.$row['itemName'].'" src="products/'.$row['itemId'].'.jpg" />
                        </a>
                    </div>
                    <div class="right-block">
                        <div class="left-p-info">
                            <h5 class="product-name"><a href="post.php?postId='.$row['itemId'].'">'.$row['itemName'].'</a></h5>
                            <div class="product-star">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                        </div>
                        <div class="content_price">
                            <span class="price product-price">'.number_format(($row['unityPrice'] + (($percentage/100)*$row['unityPrice']))).'Rwf/ '.$row['unit'].'</span>
                        </div>
                    </div>
                </li>
               ';
            }   
        }else
        {
            $relatedItems.='<li class="item">
                    No related Items!
                </li>';
        }
        
        // Company INFO
        $getcompany=$db->query("SELECT * FROM `company1` WHERE `companyId` = '$postedBy'");
        while($row = mysqli_fetch_array($getcompany))
        {
            $companyName = $row['companyName'];
            $companyDescription = $row['companyDescription'];
            $location = $row['adress'];
            $companylocation = $row['location'];
            $companylatitude = $row['latitude'];
            $companylongitude = $row['longitude'];
            $businessType = $row['businessType'];
            $dateIn = $row['dateIn'];
        }
        
        // ITEMS IN THE SAME COMPANY
        $samecompany="";
        $getsamecompant = $db->query("
            SELECT I.`itemId`, I.`itemName`, I.description, I.inDate, I.itemCompanyCode, I.productCode,I.unityPrice,
            IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='In' AND `itemCompanyCode` = '$postedBy' AND T.`itemCode` = I.`itemId`),0) -
            IFNULL((SELECT SUM(T.`qty`) FROM `transactions` T WHERE `operation`='Out' AND `itemCompanyCode` = '$postedBy' AND T.`itemCode` = I.`itemId`),0)  Balance
            ,I.`unit`, I.`unityPrice`   
            FROM `items1` I  WHERE `itemCompanyCode` = '$postedBy' AND itemId != '$postId' ORDER BY rand()");
        $countsame = mysqli_num_rows($getsamecompant);
        if($countsame > 0)
        {
            while($row = mysqli_fetch_array($getsamecompant))
            {
                $selectPercentage = $db->query("SELECT * FROM `charges` WHERE chargedFrom = 'saler'");
                $rowpercentage = mysqli_fetch_array($selectPercentage);
                $percentage = $rowpercentage['percentage'];
                $samecompany.=' 
                <li>
                    <div class="left-block">
                        <a href="post.php?postId='.$row['itemId'].'">
                            <img class="img-responsive" alt="'.$row['itemName'].'" src="products/'.$row['itemId'].'.jpg" />
                        </a>
                        <div class="add-to-cart">
                            <a title="Add to Cart" class="" href="post.php?postId='.$row['itemId'].'">Add to Cart</a>
                        </div>
                    </div>
                    <div class="right-block">
                        <div class="left-p-info">
                            <h5 class="product-name"><a href="post.php?postId='.$row['itemId'].'">'.$row['itemName'].'</a></h5>
                            <div class="product-star">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                        </div>
                        <div class="content_price">
                            <span class="price product-price">'.number_format(($row['unityPrice'] + (($percentage/100)*$row['unityPrice']))).'Rwf</span>
                        </div>
                    </div>
                </li>
                                        
                ';
            }   
        }else
        {
            $relatedItems.='<li class="item">
                    No related Items!
                </li>';
        }
        
        // subSubCategory
        $subSubCategory=$db->query("SELECT * FROM `levels` WHERE `id` = '$productCode' LIMIT 1");
        while($row = mysqli_fetch_array($subSubCategory))
        {
            $productName = $row['name'];
            $subCatCode = $row['id'];
            $subCatParent = $row['parentId'];
            // subCategory
            $subCategory=$db->query("SELECT * FROM levels WHERE `id` = '$subCatParent' LIMIT 1");
            while($row = mysqli_fetch_array($subCategory))
            {
                $subCatName = $row['name'];
                $CatCode = $row['id'];
                $CatParent = $row['parentId'];
            
                // category
                $category=$db->query("SELECT * FROM levels WHERE `id` = '$CatParent' LIMIT 1");
                while($row = mysqli_fetch_array($category))
                {
                    $catNane = $row['name'];
                }
            }
        }
        
    }

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
    <link rel="stylesheet" type="text/css" href="assets/css/index2.css" />
    <link rel="stylesheet" type="text/css" href="css/stepwizard.css" />
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/quick-view.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/responsive9.css" />
    <title><?php echo $postTitle;?></title>
      <script src="js/jquery.js"></script>
      <style type="text/css">
        .shipp-card {
            background: #fff;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 1px 2px rgba(0,0,0,.24);
            border: none;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .modal-body {
            padding: unset;
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
        .addTocart {
            line-height: 38px;
            padding: 0 12px;
            color: #ffffff;
            width: auto;
            display: inline-block;
            vertical-align: middle;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            border-width: 1px;
            border-style: solid;
            background-color: #4198d5;
        }
      </style>
</head>
<body class="shop-single-product detail-page">
    <!-- MAIN HEADER -->
    <div class="main-header">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 logo">
                    <a href="index.php"><img alt="Cavada market" src="assets/images/logo9.png" /></a>
                </div>
                <div class="tool-header">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 header-search">
                        <span class="toggle-icon"></span>
                        <form class="form-search toggle-mobile" name="_formsearch">
                            <div class="input-search">
                                <input onkeyup="topsearch()" type="text" name="inputsearch"  placeholder="Search everything">
                            </div>
                            
                            <div class="form-category dropdown">
                            <?php
                                include ("db.php");
                                $sql1 = $db->query("SELECT * FROM `levels` WHERE parentId = 0");
                                echo'<select class="box-category">
                                    <option>All Category</option>
                                ';
                                    
                                while($row = mysqli_fetch_array($sql1)){
                                    $CatID = $row['catId'];
                                    echo'
                                            <option value="'.$row['id'].'">'.$row['name'].'</option>
                                    ';
                                }
                                echo'</select>';

                            ?>
                            </div>
                            <button type="submit" class="btn-search"></button>
                            <div class="getresult" id="getresult"></div>
                        </form>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 right-main-header">
                        
                        <div class="action">
                            <a title="Login" class="compare fa fa-user" href="admin/login.php"></a>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
    <!-- END MANIN HEADER -->
    <div class="breadcrumb clearfix">
        <div class="container">
            <ul class="list-breadcr">
                <li class="home"><a href="index.php" title="Back to Home">Shop</a></li>
                <li><span><?php echo $postTitle;?></span></li>
            </ul>
        </div>
    </div>
    <div class="container">
      <div class="row">                
        <div id="content" class="col-sm-12">      
          <div class="row">
            <?php
              if(isset($_GET['postId'])) {
                $postId = $_GET['postId'];
                include("db.php");
                $sql = $db->query("SELECT * FROM `items1` WHERE itemId = '$postId'");
                while($row = mysqli_fetch_array($sql))
                {
                  $postTitle = $row['itemName'];
                  $quantity = $row['quantity'];
                  $price = $row['unityPrice'];
                  $priceStatus = $row['unit'];
                  $postDesc = $row['description'];
                  $postedDate = $row['inDate'];
                  $postedBy = $row['itemCompanyCode'];
                  $productCode = $row['productCode'];
                  $ncpp = $row['ncpp'];
                  $newNncpp = $ncpp+1;
                }
                ?>
                  <div class="col-sm-5">
                    <div class="thumbnails-image ">
                        <a class="thumbnail" title="<?php echo $postTitle;?>">
                          <img id="product-zoom" src="products/<?php echo $postId;?>.jpg" data-zoom-image="products/<?php echo $postId;?>.jpg" alt="<?php echo $postTitle;?>" title="<?php echo $postTitle;?>"/>
                        </a>
                    </div>
                    <div class="row ">
                      <div class="wrapper-img-additional">
                        <div class="image-additional" id="gallery_01">
                          <a class="thumbnail" href="javascript:void(0);" data-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_accessories/4-720x1000.jpg" data-zoom-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_accessories/4-720x1000.jpg" title="Batfoom">
                            <img src="image/cache/catalog/demo/product/image_product_accessories/4-720x1000.jpg" title="Batfoom" alt="Batfoom" />
                          </a>
                          <a class="thumbnail" href="javascript:void(0);" data-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_hair/5-720x1000.jpg" data-zoom-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_hair/5-720x1000.jpg" title="Batfoom"> 
                            <img  src="image/cache/catalog/demo/product/image_product_hair/5-720x1000.jpg" title="Batfoom" alt="Batfoom" />
                          </a>
                          <a class="thumbnail" href="javascript:void(0);" data-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_hair/2-720x1000.jpg" data-zoom-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_hair/2-720x1000.jpg" title="Batfoom"> 
                            <img  src="image/cache/catalog/demo/product/image_product_hair/2-720x1000.jpg" title="Batfoom" alt="Batfoom" />
                          </a>
                          <a class="thumbnail" href="javascript:void(0);" data-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_hair/9-720x1000.jpg" data-zoom-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_hair/9-720x1000.jpg" title="Batfoom"> 
                            <img  src="image/cache/catalog/demo/product/image_product_hair/9-720x1000.jpg" title="Batfoom" alt="Batfoom" />
                          </a>
                          <a class="thumbnail" href="javascript:void(0);" data-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_hair/13-720x1000.jpg" data-zoom-image="http://valeri3.demo.towerthemes.com/image/cache/catalog/demo/product/image_product_hair/13-720x1000.jpg" title="Batfoom"> 
                            <img  src="image/cache/catalog/demo/product/image_product_hair/13-720x1000.jpg" title="Batfoom" alt="Batfoom" />
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <h1 class="product-name product-name-product"><?php echo $postTitle;?></h1><hr>
                    <div class="product-price">
                      <h2 class="price"><?php echo ''.number_format($price).' Rwf / '.$priceStatus.'';?></h2>
                    </div><hr>
                    <div class="rating rating-product">
                      <p>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>|
                        <a href="#">
                          <?php 
                            $sql5 = $db->query("SELECT * FROM `postscomments` WHERE visibilityStatus='Public' AND postCode='$postId' ORDER by commentId DESC") or die(mysqli_error());
                            $countReviews = mysqli_num_rows($sql5);
                            echo $countReviews;
                          ?> reviews
                        </a> 
                      </p>
                    </div><hr>
                    <div class="short-description-content">
                      <p class="availability in-stock">
                        Availability: <span style="font-weight:bold;"><?php echo ''.number_format($quantity).'';?></span><br><br>
                        <?php echo $postDesc;?>
                      </p>
                      <hr>
                    </div> 

                    <div id="product">
                      <form class="form-group" action="cart.php" method="get">
                        <div class="form-quantity form-quantity-product">  
                          <div class="box-input-qty">
                            <input type="number" name="qty" value="1" min="1" id="input-quantity" class="form-control" max="<?php echo $quantity?>" />
                            <input id="quantity" type="hidden" value="<?php echo $quantity;?>" />
                            <input name="pid" type="hidden" value="<?php echo $postId;?>" />
                            <div class="btn-plus"><input type="button" id="plus" value="+" class="qty"/></div>
                            <div class="btn-minus"><input type="button" id="minus" value="-" class="qty" /></div>
                          </div>
                          <input type="hidden" name="product_id" value="43" />
                        </div>
                        <div class="box-button button-group button-group-product actions">
                          <button class="button btn-cart" id="button-cart" type="submit"  title="Add to Cart">
                            <i class="fa fa-shopping-cart"></i><span>Add to Cart</span> 
                          </button>
                          <button class="btn-wishlist" type="button"  title="Add to Wish List">
                            <i class="fa fa-heart-o"></i>
                          </button>
                        </div>

                        <script type="text/javascript">
                          $(document).ready(function() {
                            var minimum = 1;
                            var maximum = $("#quantity").val();
                            $("#input-quantity").change(function(){
                              if ($(this).val() < minimum) {
                                alert("Minimum Quantity: "+minimum);
                                $("#input-quantity").val(minimum);
                              }
                              if($(this).val() > maximum){
                                alert("Maximum Quantity: "+maximum);
                                $("#input-quantity").val(maximum);
                              }
                            });
                            // increase number of product
                            function minus(minimum){
                              var currentval = parseInt($("#input-quantity").val());
                              $("#input-quantity").val(currentval-1);
                              if($("#input-quantity").val() <= 0 || $("#input-quantity").val() < minimum){
                                alert("Minimum Quantity: "+minimum);
                                $("#input-quantity").val(minimum);
                              }
                            };
                            // decrease of product
                            function plus(maximum){
                              var currentval = parseInt($("#input-quantity").val());
                              $("#input-quantity").val(currentval+1);
                              if($("#input-quantity").val() > maximum){
                                alert("Maximum Quantity: "+maximum);
                                $("#input-quantity").val(maximum);
                              }
                            };
                            $('#minus').click(function(){
                              minus(minimum);
                            });
                            $('#plus').click(function(){
                             plus(maximum);
                            });
                          });
                        </script>
                      </form>
                    </div>
                  </div>
                <?php
              }
            ?>
            <div class="col-sm-3">
              <h3 class="title"><span>Suggested Shipping</span></h3>
              <div style="overflow-y: auto;max-height: 500px;">
                  <?php 
                      $selectshipping = $db->query("SELECT * FROM `shipper`") or die(mysqli_error());

                      function distbtnshipperandpro($companylatitude, $companylongitude, $shipperlatitude, $shipperlongitude) {
                          $earthRadius = 6371; // Earth’s mean radius in meter

                          $latFrom = deg2rad($companylatitude);
                          $lonFrom = deg2rad($companylongitude);
                          $latTo = deg2rad($shipperlatitude);
                          $lonTo = deg2rad($shipperlongitude);

                          $latDelta = $latTo - $latFrom;
                          $lonDelta = $lonTo - $lonFrom;

                          $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                          return $angle * $earthRadius; // returns the distance
                      }
                      while($shipper = mysqli_fetch_array($selectshipping)){
                          $shipperId = $shipper['shipperId'];
                          $shipperlat = $shipper['latitude'];
                          $shipperlong = $shipper['longitude'];

                          $companylatitude = $companylatitude;
                          $companylongitude = $companylongitude;

                          $shipperlatitude = $shipperlat;
                          $shipperlongitude = $shipperlong;

                          $clientlatitude = $cityLat;
                          $clientlongitude = $cityLng;

                          $distbtnshandpro = distbtnshipperandpro($companylatitude, $companylongitude, $shipperlatitude, $shipperlongitude);

                          $distbtnproandcli = distbtnshipperandpro($companylatitude, $companylongitude, $clientlatitude, $clientlongitude);

                          $totalDistance = $distbtnproandcli + $distbtnshandpro;

                          $selectshipper = $db->query("SELECT * FROM `users` WHERE id = '$shipperId'") or die(mysqli_error());
                          $shipperInfo = mysqli_fetch_array($selectshipper);
                         ?>
                              <div data-toggle="modal" data-target="#<?php echo $shipper['shippingId'];?>" class="shipp-card" title="click for more information">
                                  <div style="text-align: center;"><h2><?php echo $shipper['title'];?></h2></div>
                                  <div style="position: relative; margin-top: 15px;">
                                      <img src="shipper/<?php echo $shipper['shippingId'];?>.jpg">
                                  </div>
                                  <div class="row" style="margin: 5px -10px;">
                                      <div class="col-md-3">
                                          <div style=" height: 35px;width: 35px;border-radius: 50%; background-image: url(assets/images/users/<?php echo $shipperId;?>.jpg); background-repeat: no-repeat;background-attachment: fixed;background-position: center;background-color: #33d09d;"></div>
                                      </div>
                                      <div class="col-md-9">
                                          <?php echo $shipperInfo['names'];?><br>
                                          <?php echo $shipperInfo['phone'];?>
                                      </div>
                                  </div>
                              </div>
                              <!-- Modal -->
                              <div id="<?php echo $shipper['shippingId'];?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                  <!-- Modal content-->
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                      <h4 class="modal-title"><?php echo $shipper['title'];?></h4>
                                    </div>
                                    <div class="modal-body">
                                      <img src="shipper/<?php echo $shipper['shippingId'];?>.jpg">
                                    </div>
                                    <div class="modal-footer">
                                      <div class="col-md-3"><img class="img-circle" src="assets/images/users/<?php echo $shipperId;?>.jpg"></div>
                                      <div class="col-md-9">
                                          <p style="text-align:left;">
                                              <b>Names: </b> <?php echo $shipperInfo['names'];?><br>
                                              <b>Contact: </b><?php echo $shipperInfo['phone'];?><br>
                                              <b>Limit: </b> <?php echo $shipper['WeightLimit'];?> Kg<br>
                                              <b>Price Per Km: </b> <?php echo $shipper['pricepkilo'];?> frw<br>
                                               <b>Car Address: </b> <?php echo $shipper['address'];?><br>
                                              <b>car lat: </b> <?php echo $shipper['latitude'];?><br>
                                              <b>Car long: </b> <?php echo $shipper['longitude'];?><br>
                                              <b>pro lat: </b> <?php echo $companylatitude;?><br>
                                              <b>pro long: </b> <?php echo $companylongitude;?><br>
                                              <b>client lat: </b> <?php echo $clientlatitude;?><br>
                                              <b>client long: </b> <?php echo $clientlongitude;?><br>
                                              <b>Dist btn S and P: </b> <?php echo $distbtnshandpro;?> km<br>
                                              <b>Dist btn P and C: </b> <?php echo $distbtnproandcli;?> km<br>
                                              <b>Total Distance: </b> <?php echo $totalDistance;?> km<br>
                                              <b>Amount: </b> <?php echo number_format($totalDistance * $shipper['pricepkilo']) ;?> frw<br>
                                          </p>
                                      </div>
                                      <hr>
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                  </div>

                                </div>
                              </div>
                          <?php 
                      }
                  ?>
              </div>
            </div>
          </div>
            <div class="tab-detail">
                <!-- tab product -->
                <div class="product-tab">
                    <ul class="nav-tab">
                        <li class="active">
                            <a aria-expanded="false" data-toggle="tab" href="#description">Descriptions</a>
                        </li>
                        <li>
                            <a aria-expanded="true" data-toggle="tab" href="#specification">Company</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#reviews">reviews</a>
                        </li>
                    </ul>
                    <div class="tab-container">
                        <div id="description" class="tab-panel active">
                            <p><?php echo $postDesc;?>.</p>
                        </div>
                        <div id="specification" class="tab-panel">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Company Name</td>
                                    <td><?php echo $companyName; ?></td>
                                </tr>
                                <tr>
                                    <td>companyDescription</td>
                                    <td><?php echo $companyDescription; ?></td>
                                </tr>
                                <tr>
                                    <td>location</td>
                                    <td><?php echo $location; ?></td>
                                </tr>
                                <tr>
                                    <td>businessType</td>
                                    <td><?php echo $businessType; ?></td>
                                </tr>
                                <tr>
                                    <td>dateIn</td>
                                    <td><?php echo $dateIn; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div id="reviews" class="tab-panel">
                            <div class="product-comments-block-tab">
                        <p>
                        <div id="newComment">
                        <a class="add-review" href="javascript:void()" onclick="initiateComment(postCode=<?php echo $postId;?>)">Add your review</a></div>
                        </p>
                            <?php 
                                $n=0;
                                $sql4 = $db->query("SELECT * FROM `postscomments` WHERE visibilityStatus='Public' AND postCode='$postId' ORDER by commentId DESC") or die(mysqli_error());
                                while($row = mysqli_fetch_array($sql4)){
                                    $n++;
                                    $commentCode=$row['commentId'];
                                echo'<div class="comment row">
                                        <div class="col-sm-3 author">
                                            <div class="grade">
                                                <span>'.$n.' Grade</span>
                                                <div class="product-star">
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                            </div>
                                            <div class="info-author">
                                                <span><strong>'.$row['commentBy'].'</strong></span>
                                                <em>04/08/2015</em>
                                            </div>
                                        </div>
                                        <div class="col-sm-9 commnet-dettail">
                                            '.$row['commentNote'].'
                                        <div id="reply'.$row['commentId'].'">
                                            <i><a href="javascript:void()" onclick="reply(commentId='.$row['commentId'].', postCode='.$postId.')">Reply</a></i>
                                        </div>  
                                    ';
                                $f=0;
                                $sql5 = $db->query("SELECT * FROM `commentreplies` WHERE visibilityStatus='Public' AND commentCode='$commentCode' ORDER by replyID DESC") or die(mysqli_error());
                                while($row = mysqli_fetch_array($sql5))
                                    {
                                        $f++;
                                        echo'.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f.' <b>'.$row['replyBy'].'</b>: '.$row['replyNotes'].'<br/>';
                                    }
                                echo'</div>
                                   </div>';
                                }
                            ?>

                                
                            </div>
                        </div>
                      </div>
                </div><!-- end tab product -->
            </div>
          <div class="box-title group-title">
            <h2 class="title title-group title-fx">Related Products</h2>
          </div>
          <div class="row">
            <div class="view-related ">
              <?php echo $samecompany; ?>
            </div><!-- view-related -->
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.thumbnails').magnificPopup({
          type:'image',
          delegate: 'a',
          gallery: {
            enabled:true
          }
        });
      });

      //view-related
      $(".view-related").owlCarousel({
        autoPlay : false,
        slideSpeed : 3000,
        paginationSpeed : 3000,
        rewindSpeed : 3000,
        navigation : true,
        stopOnHover : true,
        pagination : false,
        scrollPerPage:false,
        items : 4,
        itemsDesktop : [1199,4],
        itemsDesktopSmall : [991,3],
        itemsTablet: [768,2],
        itemsMobile : [400,1],
      });

      //image-additional
      $(".image-additional").owlCarousel({
        navigation:true,
        pagination: false,
        slideSpeed : 1000,
        goToFirstSpeed : 1500,
        autoHeight : true,
        items :4, //10 items above 1000px browser width
        itemsDesktop : [1199,4], //5 items between 1000px and 901px
        itemsDesktopSmall : [991,3], //4.3 betweem 900px and 601px
        itemsTablet: [767,3], //2 items between 600 and 0
        itemsMobile : [479,2] // itemsMobile disabled - inherit from itemsTablet option
      }); 
    </script>
    <footer>
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">
              <form class="form-search toggle-mobile" name="trackingCodeform">
                  <div class="input-search">
                      <input onkeyup="trackingCode()" type="text" name="trackingcode"  placeholder="Enter tracking code to see your order"
                      class="form-control">
                  </div>
              </form>
              <div class="trackingCode" id="trackingCodeSpace"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="container">
          <p>Copyright © <a href="home.php">Sawasawa Online store</a> All rights reserved.</p>
          <span>
              <ul class="list-unstyled social">
                <li><a href="#"><em class="fa fa-twitter"></em></a></li>
                <li><a href="#"><em class="fa fa-facebook"></em></a></li>
                <li><a href="#"><em class="fa fa-instagram"></em></a></li>
                <li><a href="#"><em class="fa fa-linkedin"></em></a></li>
                <li><a href="#"><em class="fa fa-pinterest-p"></em></a></li>
              </ul>
            </span>
        </div>
      </div>
    </footer>
    <div id="back-top" class="hidden-xs"></div>
    <script type="text/javascript">
        $(document).ready(function(){
         // hide #back-top first
         $("#back-top").hide();
         // fade in #back-top
         $(function () {
          $(window).scroll(function () {
           if ($(this).scrollTop() > 300) {
            $('#back-top').fadeIn();
             $('#back-top').addClass("show");
           } else {
            $('#back-top').fadeOut();
            $('#back-top').removeClass("show");
           }
          });
          // scroll body to 0px on click
          $('#back-top').click(function () {
           $('body,html').animate({
            scrollTop: 0
           }, 800);
           return false;
          });
         });
        });
    </script>

    <script type="text/javascript">
      function Comment(postCode) {
        var postCode = postCode;
        var commentNote = document.getElementById('commentNote').value;
        var visibilityStatus = document.getElementById('visibilityStatus').value;
        if (commentNote == '' || visibilityStatus == 'Select visibility mode') {
          alert('un filled filled! please fill it');
          return false;
        }
        else {
          $.ajax({
            type : "GET",
            url : "reviewproduct.php",
            dataType : "html",
            cache : "false",
            data : {
              postCode : postCode,
              commentNote : commentNote,
              visibilityStatus : visibilityStatus,
            },
            success : function(data){
              $("#newComment").html(data);
              $("#visibilityStatus").html('');
              $("#commentNote").html('');
            },
            error : function(xht, textStatus, errorThrown){
              alert("Error : " + errorThrown);
            }
          });
        }
      }
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