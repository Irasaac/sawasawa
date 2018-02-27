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
$sql = $db->query("SELECT * FROM users u INNER JOIN useraccounttype ua WHERE u.id = ua.userId AND loginId = '$username' AND pwd = '$password' limit 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysqli_num_rows($sql); // count the row nums
if ($existCount > 0) { 
	while($row = mysqli_fetch_array($sql)){ 
		$thisid = $row["id"];
		$names = $row["names"];
		$userprofile = $row["profile"];
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

    if (isset($_POST['shippingId'])) {
    	$shippingId = $_POST['shippingId'];
    	include 'db.php';
    	$Deletethis = $db -> query("DELETE FROM shipper WHERE shippingId = '$shippingId'");
    	unlink('../assets/images/shipper/'.$shippingId.'.jpg');
    }
    ?>
		<div class="form-head">
			<h2>Your Shipping List</h2>
		</div>
		<table width="100%" border="1" cellspacing="0" cellpadding="0" style="text-align: left; border: 1px #638062 solid;">
			<thead>
				<tr bgcolor="#638062">
					<th>No</th>
					<th>Title</th>
					<th>Limited Weight</th>
					<th>Price per kilometer</th>
					<th>Shipping Picture</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$selectship = $db -> query("SELECT * FROM shipper WHERE shipperId = '$thisid'");
					$n = 1;
					while ($myshipper = mysqli_fetch_array($selectship)) {
						echo'
							<tr>
								<td>'.$n.'</td>
								<td>'.$myshipper['title'].'</td>
								<td>'.$myshipper['WeightLimit'].'</td>
								<td>'.$myshipper['pricepkilo'].'</td>
								<td><img src="../assets/images/shipper/'.$myshipper['Photo'].'" width="100px" style="max-height: 100px;"></td>
								<td><a href="javascript:;" onclick="deleteshipper('.$myshipper['shippingId'].')">Delete</a> |<a href="javascript:;">Edit</a> </td>
							</tr>
						';
						$n++;
					}

				?>
			</tbody>
		</table>
    <?php

?>