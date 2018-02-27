<?php // Destry session if it hasn't been used for 15 minute.
session_start();
	
if (!isset($_SESSION["username"])) {
 header("location: ../login.php"); 
    exit();
}
?>

<?php 
$session_id = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$username = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
include "../db.php"; 
$sql = $db->query("SELECT * FROM users u INNER JOIN useraccounttype ua WHERE u.id = ua.userId AND loginId = '$username' AND pwd = '$password' limit 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysqli_num_rows($sql); // count the row nums
if ($existCount > 0) { 
	while($row = mysqli_fetch_array($sql)){ 
		$thisid = $row["id"];
		$names = $row["names"];
        $userpic = $row["Pic"];
		$account_type = $row["accName"];
		if ($account_type != 'admin') {
			header("location: user.php");
		}
	}
} 
else {
	echo "
	
	<br/><br/><br/><h3>Your account has been temporally deactivated</h3>
	<p>Please contact: <br/><em>(+25) 078 484-8236</em><br/><b>muhirwaclement@gmail.com</b></p>		
	Or<p><a href='../pages/logout.php'>Click Here to login again</a></p>
	
	";
    exit();
}
?>

<?php

	if (isset($_POST['addIndustry'])) {
		$industryName = $_POST['industryName'];
		$insertIt = $db ->query("INSERT INTO industries VALUES('', '$industryName')");
		header("location: industries.php");
	}

?>
<!doctype html>
<html lang="en">
<?php include'header.php' ;?>
	<!-- main sidebar -->
   
    <div id="page_content">
        <div id="page_content_inner">

            <h3 class="heading_b uk-margin-bottom">MANAGE INDUSTRIES</h3>
			<div class="uk-grid uk-grid-width-medium-1-2" data-uk-grid="{gutter:24}">
                <div>
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text" >
                                Add Industries
                            </h3>
                        </div>
                        <div class="md-card-content">
							<form method="post" action="industries.php" enctype="multipart/form-data" class="form-group">
								<div>
									New Industry:<br/>
									<input type="text" name="industryName" autofocus class="md-input"/>
									<span class="md-input-bar"></span>
									<input type="submit" name="addIndustry" class="md-btn md-btn-success" value="Add">
								</div>
								
							</form>	
						</div>
                    </div>
				</div>
			    <div>
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text" >
                                Industries
                            </h3>
                        </div>
                        <div class="md-card-content" id="industriesLocation">
							<table class="uk-table uk-table-striped" width="100%">
								<thead >
									<th>#</th>
									<th>Industry Name</th>
									<th>Actions</th>
								</thead>
								<tbody>
									<?php
										include("db.php");
										$getIndustries = $db->query("SELECT * FROM Industries");
										$countIndustries = mysqli_num_rows($getIndustries);
										if($countIndustries > 0) {
											$n = 0;
											while ($industry = mysqli_fetch_array($getIndustries)) {
												echo '
													<tr>
														<td>'.++$n.'</td>
														<td>'.$industry['industryName'].'</td>
														<td><a href="javascript:void()" onclick="removeindustry(industryId= '.$industry['industryId'].')">Remove</a></td>
													</tr>
												';
											}
										}
										else {
											echo '
												<tr>
													<td colspan="3">No Industry Found. Please Add</td>
												</tr>
											';
										}
									?>
								</tbody>
							</table>
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

    <!-- datatables -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <!-- datatables buttons-->
    <script src="bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
    <script src="assets/js/custom/datatables/buttons.uikit.js"></script>
    <script src="bower_components/jszip/dist/jszip.min.js"></script>
    <script src="bower_components/pdfmake/build/pdfmake.min.js"></script>
    <script src="bower_components/pdfmake/build/vfs_fonts.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.colVis.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.html5.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.print.js"></script>
    
      <!-- datatables custom integration -->
    <script src="assets/js/custom/datatables/datatables.uikit.min.js"></script>

    <!--  datatables functions -->
    <script src="assets/js/pages/plugins_datatables.min.js"></script>

    <script type="text/javascript">
    	function removeindustry(industryId) {
    		var industryId = industryId;
    		var a = confirm("When you remove this industry will remove all data related include all company. do you want to remove?");
    		if (a == true) {
    			$.ajax({
    				url: "userscript.php",
    				dataType: 'html',
    				type: 'post',
    				async: false,
    				data: {
    					industryId : industryId
    				},
    				success: function(data) {
    					$('#industriesLocation').html(data);
    				}
    			});
    		}
    		else {
    			return false;
    		}
    	}
    </script>

</body>
</html>