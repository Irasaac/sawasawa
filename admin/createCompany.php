<?php // Destry session if it hasn't been used for 15 minute.
session_start();
	$inactive = 900;
    if(isset($_SESSION['timeout']) ) 
	{
		$session_life = time() - $_SESSION['timeout'];
		if($session_life > $inactive)
		{
		header("Location: ../logout.php"); 
		}
    }
    $_SESSION['timeout'] = time();
	if (!isset($_SESSION["username"])) 
	{
		header("location: login.php"); 
		exit();
	}
include "../db.php"; 
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
		Or<p><a href='../logout.php'>Click Here to login again</a></p>
		
		";
	    exit();
	}
		
?>
<?php

if(isset($_POST['companyName']))
	{
		$companyName = $_POST['companyName'];
		$companyType = $_POST['companyType'];
		$cumpanyUserCode = $_POST['cumpanyUserCode'];
		$businessType = $_POST['businessType'];
		$location = $_POST['locationId'];
		$companyDescription = $_POST['companyDescription'];
		$companyTin = $_POST['companyTin'];
		include ("../db.php");
		$sqlcpn = $db->query("INSERT INTO company1 (companyName, companyType, cumpanyUserCode, businessType, location, companyDescription, companyTin) 
		VALUES ('$companyName', '$companyType', '$cumpanyUserCode', '$businessType', '$location', '$companyDescription', '$companyTin')")or die (mysqli_error());
		$updateUser = $db ->query("UPDATE users SET account_type = '$businessType' WHERE id = '$cumpanyUserCode'");
		$sql2 = $db->query("SELECT * FROM company1 ORDER BY companyId DESC limit 1");
			while($row = mysqli_fetch_array($sql2)){
				$Imagename = $row['companyId'];
			}
		if ($_FILES['fileField']['tmp_name'] != "") {																	 										 
			$newname = ''.$Imagename.'.jpg';
			move_uploaded_file( $_FILES['fileField']['tmp_name'], "../company/$newname");
		}
		header("location: user.php"); 
		exit();
	}
?>
 <div id="page_content">
        <div id="page_content_inner">
			<h3 class="heading_b uk-margin-bottom">Creating A New Company</h3>
			<div class="uk-grid uk-grid-medium" data-uk-grid-margin="">
                <div class="uk-width-xLarge-2-10 uk-width-large-3-10 uk-row-first">
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text" >
                                Company
                            </h3>
                        </div>
                        <div class="md-card-content">
							<form method="post" action="createCompany.php" enctype="multipart/form-data" class="form-group">
								<label>Company Name:</label>
								<div class="md-input-wrapper">
									<input type="text" class="md-input" name="companyName">
									<input type="text" name="cumpanyUserCode" value="<?php echo $thisid;?>" hidden />
									<span class="md-input-bar "></span>
								</div>
								<label>Company Industry:</label>
								<div class="md-input-wrapper">
									<input type="text" class="md-input" name="businessType">
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<select name="companyType">
										<option>-- Company Type --</option>
										<option value="saler">Saler</option>
										<option value="shipper">Shipper</option>
									</select>
								</div>
								<label>Company Location:</label>
								<div class="md-input-wrapper">
									<div id="locationsDiv" >
		
										<select class="form-control" name="locationId" id="locationId" onchange="changelocation()">
											<option value="0">-- Select Country --</option>
											<option value="0">Rwanda</option>
										</select>
									</div>
								</div>
								<label>Company Description:</label>
								<div class="md-input-wrapper">
									<textarea name="companyDescription"></textarea>
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<label>Logo:</label>
                                	<div class="uk-form-file md-btn md-btn-primary" data-uk-tooltip="">
			                            Import image
			                            <input type="file" name="fileField" id="fileField"/> 
                                	</div>
									<span class="md-input-bar "></span>
								</div>
								<div class="md-input-wrapper">
									<input type="submit" value="Save" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" name="Signup"/>
								</div>
							</form>	
						</div>
                    </div>
                </div>
			</div>				 
        </div>
    </div>
<script type="text/javascript">
	function changelocation() {
		var locationId = document.getElementById('locationId').value;
		//alert(locationId);
		$.ajax({
			type : "GET",
			url : "../locations.php",
			dataType : "html",
			cache : "false",
			data : {
				locationId : locationId,
			},
			success : function(html, textStatus){
				$("#locationsDiv").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				//alert("Error : " + errorThrown);
			}
		});
	}
</script>


