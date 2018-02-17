<?php // Destry session if it hasn't been used for 15 minute.
session_start();
	
if (!isset($_SESSION["username"])) {
 header("location: login.php"); 
    exit();
}
?>

<?php 
$session_id = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$username = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
include "../db.php"; 
$sql = $db->query("SELECT * FROM users WHERE loginId='$username' AND pwd='$password' LIMIT 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysqli_num_rows($sql); // count the row nums
if ($existCount > 0) { 
	while($row = mysqli_fetch_array($sql)){ 
	 $thisid = $row["id"];
	 $names = $row["names"];
	}
} 
else{
	echo "
	
	<br/><br/><br/><h3>Your account has been temporally deactivated</h3>
	<p>Please contact: <br/><em>(+25) 078 484-8236</em><br/><b>muhirwaclement@gmail.com</b></p>		
	Or<p><a href='../pages/logout.php'>Click Here to login again</a></p>
	
	";
    exit();
}
?>

<?php
//error_reporting(0);
if(isset($_POST['add']))
{
	$cat_name = $_POST['cat_name'];
	$cat_desc = $_POST['cat_desc'];
	include ("../db.php");
	$sql = $db->query("INSERT INTO `productcategory`(`catNane`, `catDesc`) 
	VALUES ('$cat_name', '$cat_desc')")or die (mysqli_error());
	header("location: admin.php");
	}
	elseif(isset($_POST['edit']))
	{
		$cat_id = $_POST['cat_id'];
		$cat_name = $_POST['cat_name'];
		$cat_desc = $_POST['cat_desc'];
		include ("../db.php");
		$sql = $db->query("UPDATE productcategory SET catNane = '$cat_name', catDesc= '$cat_desc' WHERE catId = '$cat_id'")or die (mysqli_error());
		header("location: admin.php");
	}
	
if(isset($_POST['adds']))
{
	$CatCode = $_POST['CatCode'];
	$subCatName = $_POST['subCatName'];
	$subCatDesc = $_POST['subCatDesc'];
	include ("../db.php");
	$sql = $db->query("INSERT INTO productsubcategory(subCatName, subCatDesc, CatCode) 
	VALUES ('$subCatName', '$subCatDesc', '$CatCode')")or die (mysqli_error());
	header("location: admin.php");
	}
if(isset($_POST['addcat']))
{
	$levelname = $_POST['levelname'];
	$locationId = $_POST['SavelocationId'];
	$cumpanyUserCode = $_POST['cumpanyUserCode'];
	//include ("../db.php");
	$sql = $db->query("INSERT INTO `levels` 
	(`name`, `parentId`, `createdBy`)
	VALUES ('$levelname', '$locationId', '$cumpanyUserCode')
	")or die (mysqli_error());
	header("location: admin.php");
}

if(isset($_POST['addtag']))
{
	$tagname = $_POST['tagname'];
	$levelId = $_POST['SavelocationId'];
	$cumpanyUserCode = $_POST['cumpanyUserCode'];
	//include ("../db.php");
	$sql = $db->query("INSERT INTO `tags` 
	(`name`, `levelId`, `createdBy`, `propertyId`)
	VALUES ('$tagname', '$levelId', '$cumpanyUserCode', 0)
	")or die (mysqli_error());
	header("location: admin.php");
}
	elseif(isset($_POST['edits']))
	{
		$subCatId = $_POST['subCatId'];
		$subCatName = $_POST['subCatName'];
		$subCatDesc = $_POST['subCatDesc'];
		include ("../db.php");
		$sql = $db->query("UPDATE productsubcategory SET subCatName = '$subCatName', subCatDesc= '$subCatDesc' WHERE subCatId = '$subCatId'")or die (mysqli_error());
		header("location: admin.php");
	}

// Add product	
if(isset($_POST['addp']))
{
	$productName = $_POST['productName'];
	$subCatCode = $_POST['subCatCode'];
	$productDesc = $_POST['productDesc'];
	echo $productName;
	echo '<br/>';
	echo $subCatCode;
	echo '<br/>';
	echo $productDesc;
	echo '<br/>';
	include ("../db.php");
	$sql = $db->query("INSERT INTO products(productName, productDesc, subCatCode, unit, status, createDate_By) 
	VALUES ('$productName', '$productDesc', '$subCatCode', '10', 'Active', 'me')")or die (mysqli_error());
	header("location: admin.php");
	}
// Edit product
	elseif(isset($_POST['editp']))
	{
		$productId = $_POST['productId'];
		$productName = $_POST['productName'];
		$productDesc = $_POST['productDesc'];
		include ("../db.php");
		$sql = $db->query("UPDATE products SET productName = '$productName', productDesc= '$productDesc' WHERE productId = '$productId'")or die (mysqli_error());
		header("location: admin.php");
	}
?>
<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php include'header.php' ;?>
	<!-- main sidebar -->
   
    <div id="page_content">
        <div id="page_content_inner">

            <h3 class="heading_b uk-margin-bottom">MANAGE CATEGORIES</h3>
			<div class="uk-grid uk-grid-width-medium-1-2" data-uk-grid="{gutter:24}">
                <div>
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text" >
                                Add Categories
                            </h3>
                        </div>
                        <div class="md-card-content">
							<form method="post" action="admin.php" enctype="multipart/form-data" class="form-group">
								<input type="text" name="cumpanyUserCode" value="<?php echo $thisid;?>" hidden/>
								<div id="locations">
									New Category:<br/>
									<input type="text" name="levelname"/>
									<button type="submit" name="addcat" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light">Add</button>
									<br/><?php 
											$sqllocation = $db->query("SELECT * FROM levels WHERE parentId = 0");
											$countResults = mysqli_num_rows($sqllocation);
											if($countResults > 0){
											echo '<select name="locationId" id="locationId" onchange="changelocation()">';
												echo'<option >--Select a Category--</option>';
												while($row = mysqli_fetch_array($sqllocation))
													{ 
														echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
													}
											echo'</select></br>';
											}
												echo'<input type="text" name="SavelocationId" hidden value="0"/>';
											
										?>
									</br>
									<!--
									New Tag:<br/>
									<input type="text" name="tagname"/>
									<button type="submit" name="addtag" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light">Add</button>
									<br/><?php 
											$sqltags = $db->query("SELECT * FROM tags WHERE levelId = 0");
											$countTags = mysqli_num_rows($sqltags);
											if($countTags > 0){
											echo '<select name="tagId" id="tagId" onchange="changeTags()">';
												echo'<option >--Select a Tag--</option>';
												while($row = mysqli_fetch_array($sqltags))
													{ 
														echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
													}
											echo'</select></br>';
											}else{
												echo'<input type="text" name="SaveTagParentId" hidden value="0"/>';
											}
										?>
									</br>-->
								</div>
								
							</form>	
						</div>
                    </div>
				</div>
			    <div>
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text" >
                                Categories
                            </h3>
                        </div>
                        <div class="md-card-content">
						<?php
							
							function getlocations($parentIds)
							{
								include("db.php");
								$getlocations = $db->query("SELECT * FROM levels WHERE parentId = '$parentIds'");
								$countLocations = mysqli_num_rows($getlocations);
								if($countLocations > 0)
								{
									echo '
									<ol class="tree">';
									while($rowLoc = mysqli_fetch_array($getlocations))
									{
										$parentIds = $checkId= $rowLoc['id'];
										echo '
										<li>
											<label for="folder'.$checkId.'"> '.$rowLoc['name'].' </label> 
											<input type="checkbox" id="folder'.$checkId.'"/>';
											getlocations($parentIds = $checkId);
												
										echo'
										</li>';
									}
									echo '
									</ol>';
								}
							}
							getlocations($parentIds = 0);
						?>
						</div>
                    </div>
				</div>
				
        </div>
    </div>

    <!-- google web fonts -->
    <script>
        WebFontConfig = {
            google: {
                families: [
                    'Source+Code+Pro:400,700:latin',
                    'Roboto:400,300,500,700,400italic:latin'
                ]
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>

    <!-- common functions -->
    <script src="assets/js/common.min.js"></script>
    <!-- uikit functions -->
    <script src="assets/js/uikit_custom.min.js"></script>
    <!-- altair common functions/helpers -->
    <script src="assets/js/altair_admin_common.min.js"></script>


    <script>
        $(function() {
            if(isHighDensity()) {
                $.getScript( "bower_components/dense/src/dense.js", function() {
                    // enable hires images
                    altair_helpers.retina_images();
                });
            }
            if(Modernizr.touch) {
                // fastClick (touch devices)
                FastClick.attach(document.body);
            }
        });
        $window.load(function() {
            // ie fixes
            altair_helpers.ie_fix();
        });
    </script>
<script> <!--1 Load cat in the cat to edit-->
function cat(catID){
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				catID : catID,
			},
			success : function(html, textStatus){
				$("#cat").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
<!--Remove category-->
function removeCat(catID){
	var r = confirm("Are you sure you want to remove this category from the list");
    if (r == true)
		{
   			removeCatId=catID;
			$.ajax({
					type : "GET",
					url : "adminscript.php",
					dataType : "html",
					cache : "false",
					data : {
						
						removeCatId : removeCatId,
						checkRemoveCatId : removeCatId,
					},
					success : function(html, textStatus){
						$("#catTable").html(html);
					},
					error : function(xht, textStatus, errorThrown){
						alert("Error : " + errorThrown);
					}
			});
		}
}
<!--Remove category-->
function removeSubCat(subCatId){
	var r = confirm("Are you sure you want to remove this category from the list");
    if (r == true)
		{
   			removeSubCatId=subCatId;
			$.ajax({
					type : "GET",
					url : "adminscript.php",
					dataType : "html",
					cache : "false",
					data : {
						
						removeSubCatId : removeSubCatId,
					},
					success : function(html, textStatus){
						$("#SucatTable").html(html);
					},
					error : function(xht, textStatus, errorThrown){
						alert("Error : " + errorThrown);
					}
			});
		}
}<!--Remove Product-->
function removeProduct(productId){
	var r = confirm("Are you sure you want to remove this category from the list");
    if (r == true)
		{
   			removeProductId=productId;
			$.ajax({
				type : "GET",
				url : "adminscript.php",
				dataType : "html",
				cache : "false",
				data : {
					
					removeProductId : removeProductId,
				},
				success : function(html, textStatus){
					$("#productTable").html(html);
				},
				error : function(xht, textStatus, errorThrown){
					alert("Error : " + errorThrown);
				}
			});
		}
}
</script>
<script> <!--2 Show subcat-->
function showsub(showCatId){
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				showCatId : showCatId,
			},
			success : function(html, textStatus){
				$("#sub").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
<script> <!--3 Load subcat in the subcat to edit-->
function subcatfill(subcatfillID){
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				subcatfillID : subcatfillID,
			},
			success : function(html, textStatus){
				$("#subcat").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
<script> <!--4 Show product-->
function product(subCatId){
//	alert(subCatCode);
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				subCatId : subCatId,
			},
			success : function(html, textStatus){
				$("#product").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
<script> <!--5 Load product to Edit-->
function productEdit(productEditID){
//	alert(subCatCode);
	$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				productEditID : productEditID,
			},
			success : function(html, textStatus){
				$("#productEdit").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}

<!--5 Add a user-->
function insertUser()
{
	
	var name = document.getElementById('name').value;
	//alert(purchaseOrder);
	if (name == null || name == "") {
        alert("name must be filled out");
        return false;
    }
	var Phone = document.getElementById('Phone').value;
	if (Phone == null || Phone == "") {
        alert("Phone must be filled out");
        return false;
    }
	var Email = document.getElementById('Email').value;
	if (Email == null || Email == "") {
        alert("Email must be filled out");
        return false;
    }
	var account_type = document.getElementById('account_type').value;
	if (account_type == null || account_type == "") {
        alert("account_type must be filled out");
        return false;
    }
	var username = document.getElementById('username').value;
	if (username == null || username == "") {
        alert("username must be filled out");
        return false;
    }
	var password = document.getElementById('password').value;
	if (password == null || password == "") {
        alert("password must be filled out");
        return false;
    }
	
	//document.getElementById('tempTable').innerHTML = '';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				name : name,
				Phone : Phone,
				Email : Email,
				account_type : account_type,
				username : username,
				password : password,
				
				
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
<!--5 load user to Edit-->
function editUser(userId)
{
	var editUser = userId;
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				editUser : editUser,
				
				
			},
			success : function(html, textStatus){
				$("#userdiv").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
<!--5 Edit user-->
function updateUser()
{
	
	var Ename = document.getElementById('Ename').value;
	//alert(purchaseOrder);
	if (Ename == null || Ename == "") {
        alert("name must be filled out");
        return false;
    }
	var EPhone = document.getElementById('EPhone').value;
	if (EPhone == null || EPhone == "") {
        alert("EPhone must be filled out");
        return false;
    }
	var EEmail = document.getElementById('EEmail').value;
	if (EEmail == null || EEmail == "") {
        alert("EEmail must be filled out");
        return false;
    }
	var Eaccount_type = document.getElementById('Eaccount_type').value;
	if (Eaccount_type == null || Eaccount_type == "") {
        alert("Eaccount_type must be filled out");
        return false;
    }
	var Eusername = document.getElementById('Eusername').value;
	if (Eusername == null || Eusername == "") {
        alert("Eusername must be filled out");
        return false;
    }
	var Epassword = document.getElementById('Epassword').value;
	if (Epassword == null || Epassword == "") {
        alert("Epassword must be filled out");
        return false;
    }
	var Eid = document.getElementById('Eid').value;
	if (Eid == null || Eid == "") {
        alert("Eid must be filled out");
        return false;
    }
	
	//document.getElementById('tempTable').innerHTML = '';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				Ename : Ename,
				Eid : Eid,
				EPhone : EPhone,
				EEmail : EEmail,
				Eaccount_type : Eaccount_type,
				Eusername : Eusername,
				Epassword : Epassword,
				
				
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
// BRING TABLE
function bringTable()
{
	var bringTable = '1';
		$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				bringTable : bringTable,
				
				
			},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
<script type="text/javascript">
	function changelocation(){
		var locationId = document.getElementById('locationId').value;
		//alert(locationId);
		$.ajax({
			type : "GET",
			url : "selectLocationadmin.php",
			dataType : "html",
			cache : "false",
			data : {
				locationId : locationId,
			},
			success : function(html, textStatus){
				$("#locations").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
		});
	}
</script>
</body>
</html>