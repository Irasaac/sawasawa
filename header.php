<div id="header" class="header">
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
    <div id="nav-top-menu" class="nav-top-menu option inherit-width">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-xs-6 col-md-3" id="box-vertical-megamenus">
                    <div class="box-vertical-megamenus">
                        <h4 class="title">
                            <span class="btn-open-mobile home-page"><i class="fa fa-bars"></i></span>
                            <span class="title-menu">Categories Menu</span>
                        </h4>
                        <div class="vertical-menu-content is-home">
                            <ul class="vertical-menu-list">
                               <?php
                                    include ("db.php");
                                    $sql1 = $db->query("SELECT * FROM levels where parentId = 0");
                                    while($row = mysqli_fetch_array($sql1))
                                    {
                                    	$CatID = $row['id'];
                                    	echo'
                                        <li>
                                        <a class="parent" href="cat.php?CatID='.$CatID.'"><img class="icon-menu" alt="cavada" src="assets/images/icon/iconvetical_1.png">'.$row['name'].'</a>
                                    	<div class="vertical-dropdown-menu smartphone">
                                            <div class="vertical-groups clearfix">
                                    			';
                                    	$sql2 = $db->query("SELECT * FROM levels WHERE parentId='$CatID' LIMIT 2");
                                    	while($row = mysqli_fetch_array($sql2)){
                                    		$subCatId = $row['id'];
                                    		echo'<div class="mega-group width col-md-12">
                                    				<h4 class="mega-group-header" href="javascript:void()" onclick ="subshow(subshowid= '.$row['id'].')"><span>'.$row['name'].' </span></h4>
                                    				<ul class="group-link-default">
                                    			';
                                    		$sql3 = $db->query("SELECT * FROM levels WHERE parentId='$subCatId' LIMIT 4");
                                    		while($row = mysqli_fetch_array($sql3)){
                                    			echo'
                                    	<li><a href="cat.php?CatID='.$subCatId.'">'.$row['name'].'</a></li>
                                    	
                                    ';
                                    			}
                                    echo'<li><a href="cat.php?CatID='.$subCatId.'">more...</a></li>
                                    	</ul>
                                    </div>';
                                    	}
                                    echo'</div>
                                    	<div class="mega-custom-html col-sm-12">
                                    		<a href="#"><img class="img-responsive" src="assets/data/market/menu-img/bn-smarphone.jpg" alt="Banner"></a>
                                    	</div>
                                    </div>
                                    </li>';
                                    }
                                ?>             
                            </ul>
							<div class="all-category"><span><img class="icon-menu" alt="Cavada" src="assets/images/icon/iconvetical_10.png">All Categories</span><span style="display:none"><i class="fa fa-minus"></i>Close</span></div>
                        </div>
                    </div>
                </div>
                <a href="javascript:void()" class="menu-toggle col-md-6 col-sm-6 col-xs-6"><i class="fa fa-bars"></i>menu</a>
                <div id="main-menu" class="col-sm-9 col-md-9 main-menu menu-index9 inherit-custom-width navigation">
                    
                    <nav class="navbar navbar-default">
                        <div id="navbar" class="navbar-collapse collapse">
                            <a href="javascript:void()" class="menu-toggle-close"><i class="fa fa-times"></i></a>
                            <ul class="nav navbar-nav level0">
                                <li class="active dropdown home">
                                    <a class="dropdown-toggle" href="#">Home</a>
                                    <ul class="dropdown-menu level1">
                                        <li class="link_container"><a href="#">About</a></li>
                                        <li class="link_container"><a href="#">SiteMap</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function topsearch() {
        xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET","autosearched.php?search="+document._formsearch.inputsearch.value,false);
        xmlhttp.send(null);
        document.getElementById('getresult').innerHTML=xmlhttp.responseText;
    }
</script>