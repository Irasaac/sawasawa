<?php
	session_start();
	$postId = '';
	if (isset($_GET['postId'])) {
		$postId = $_GET['postId'];
	}
    if (isset($_POST['cityLat'])) {
        $_SESSION['Latitude'] = $_POST['cityLat'];
        $_SESSION['Longitude'] = $_POST['cityLng'];
        $_SESSION['address'] = $_POST['inputlocation'];
        $postId = $_POST['postId'];
        header("location: post.php?postId=$postId");
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
        <!--[if IE]>
        <style>.form-category .icon {display: none;}</style>
        <![endif]--> 
        <link rel="stylesheet" type="text/css" href="assets/css/quick-view.css" />
        <link rel="stylesheet" type="text/css" href="assets/css/responsive9.css" />
        <title>SAWASAWA</title>
    </head>
    <body class="home market-home">
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
        <!-- Modal of client location input -->
        <div id="clientLocation" class="" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter your Location please</h4>
              </div>
              <form action="clientlocation.php" method="post">
              <div class="modal-body">
                <input class="form-control" type="text" name="inputlocation" id="inputlocation" placeholder=" Enter client Location">
                <input type="text" id="cityLng" name="cityLng" value="" hidden>
                <input type="text" id="cityLat" name="cityLat" value="" hidden>
                <input type="text" id="city2" name="city2" value="" hidden>
                <input type="text" id="postId" name="postId" value="<?php echo $postId; ?>" hidden>
              </div>
              <div class="modal-footer">
                <input type="submit" class="btn btn-default" value="Submit">
              </div>
            </form>
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
            google.maps.event.addDomListener(window, 'load', initialize);
            function initialize() {
                var autocomplete = new google.maps.places.Autocomplete(document.getElementById('inputlocation'));
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    var place = autocomplete.getPlace();
                    document.getElementById('city2').value = place.name;
                    document.getElementById('cityLat').value = place.geometry.location.lat();
                    document.getElementById('cityLng').value = place.geometry.location.lng();

                });
            }
        </script>

        <script type="text/javascript">
            function topsearch() {
                xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET","autosearched.php?search="+document._formsearch.inputsearch.value,false);
                xmlhttp.send(null);
                document.getElementById('getresult').innerHTML=xmlhttp.responseText;
            }
        </script>
    </body>
</html>