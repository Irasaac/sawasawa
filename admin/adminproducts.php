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
		if ($account_type != 'Admin') {
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
<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php include 'header.php';?>  
 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
 	
 </script>
        <script type="text/javascript">
            $(document).ready(function() {
                var options = {
                    chart: {
                        renderTo: 'container',
                        type: 'line'
                    },
                    title: {
                        text: '',
                        x: -20 //center
                    },
                    xAxis: {
                        categories: [],
                        title: {
                            text: 'Days'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Bids'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    tooltip: {
                        valueSuffix: 'Bids'
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: []
                };
                $.getJSON("productsdata.php", function(json) {
                    options.xAxis.categories = json[0]['data']; //xAxis: {categories: []}
                    options.series[0] = json[1];
                    chart = new Highcharts.Chart(options);
                });
            });
        </script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>      
   
    <div id="page_content">
        <div id="page_content_inner">

            <h3 class="heading_b uk-margin-bottom">MANAGE PRODUCTS</h3>

            <div class="uk-grid uk-grid-medium" data-uk-grid-margin="">
                <div class="uk-width-xLarge-10-10 uk-width-large-10-10 uk-row-first">
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text">
                                Report
                            </h3>
                        </div>
                        <div class="md-card-content">
							
							<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>	
						</div>
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
<script> <!--5 USER-->
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

function removeuser(userId)
{
	var r = confirm("Are you sure you want to remove this user?!");
    if (r == true) {
			$.ajax({
			type : "GET",
			url : "adminscript.php",
			dataType : "html",
			cache : "false",
			data : {
				removeuserid : userId,
				},
			success : function(html, textStatus){
				$("#listTable").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
		});
		}
	else{
		alert('Okay then, We wont remove the user, thanks!');
	}

}
</script>

</body>
</html>