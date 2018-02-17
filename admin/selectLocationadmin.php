<?php
error_reporting(E_ALL); 
	ini_set('display_errors', 1);
if(isset($_GET['locationId']))
{
	$locationId = $_GET['locationId'];
	include 'db.php';
	$sql2 = $db->query("SELECT * FROM levels WHERE id = '$locationId'");
	while($row2 = mysqli_fetch_array($sql2))
	{
		$namefor = $row2['name'];
		$idfor = $row2['id'];
	}
	$sql = $db->query("SELECT * FROM levels WHERE parentId = '$locationId'");
	$countResults = mysqli_num_rows($sql);
	
	echo '
		Add a subcategory in '.$namefor.':<br/>
		<input type="text" name="levelname"/>
		<input hidden name="SavelocationId" value="'.$idfor.'">
		<button type="submit" name="addcat"  class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light">Add</button><br>';
	if($countResults > 0){
	echo'<select name="locationId" id="locationId" onchange="changelocation()">
	<option >--Select SubCategory--</option>';
		while($row = mysqli_fetch_array($sql))
			{ 
				echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}
	echo'</select></br>';
	}
	
}

if(isset($_GET['sortCat']))
{
	$sortCatid = $_GET['sortCat'];
	if ($sortCatid > 0)
	{
	echo'<label class="cl-filter-label">Sub Category</label>
		<select class="sc-input" id="sortKey" name="sortKey" data-list-uri="/results?desc=0&amp;powertype=kw&amp;pricetype=public&amp;pricefrom=500&amp;sort=price&amp;ustate=N%2CU&amp;atype=C">
			<option selected value="price">Chose a SubCategory</option>
			';
			include ("db.php");
			$sql2 = $db->query("SELECT * FROM `levels` WHERE parentId = '$sortCatid'");
			while($row = mysqli_fetch_array($sql2))
				{
					echo '<option value="-price">'.$row['name'].'</option>';
				}
		'</select>';
	}
	else
	{
		echo'
			<label class="cl-filter-label">Sub Category</label>
			<select disabled class="sc-input" id="sortKey" name="sortKey" data-list-uri="/results?desc=0&amp;powertype=kw&amp;pricetype=public&amp;pricefrom=500&amp;sort=price&amp;ustate=N%2CU&amp;atype=C">
				<option selected value="price">Chose a SubCategory</option>
					
			</select>
		';
	}
}

if(isset($_GET['sortCatres']))
{	echo'<ul>
							';
	$sortCatid = $_GET['sortCatres'];
	function settings($sortCatid)
	{$sortCatName ="";
	
		include ("db.php");
		$sql2 = $db->query("SELECT * FROM `levels` WHERE parentId = '$sortCatid'");
		while($row = mysqli_fetch_array($sql2))
			{
				echo $sortCatid = $row['id'];
				$sortCatName = $row['name'];
				$sql5 = $db->query("SELECT * FROM `items1` WHERE `productCode` = '$sortCatid'")or die (mysqli_error());
				$count = mysqli_num_rows($sql5);
				 $n=0;
				 if($count >0)
				 {
					while($row = mysqli_fetch_array($sql5))
					 {
						 $n++;
						 //echo $row['itemName'];
						 $postTitle = $row['itemName'];
									$itemId = $row['itemId'];
									$priceStatus = $row['unit'];
									$price = $row['unityPrice'];
									$description = $row['description'];
									?>
									<li class="classified-list-item">
									<div class="sc-grid-row">
										<div class="sc-grid-col-12">
											<div class="gallery-container">
												<div class="cldt-gallery-container">
													<div>
														<a href="post.php?postId=<?php echo $itemId;?>" data-item-name="detail-page-link">
															<as24-carousel id="id-c471b14e-0529-2b27-e053-e250040a5782" loop="infinite">
															<div class="as24-carousel__container" role="container">
																<div class="as24-carousel__item">
																<img src="products/<?php echo $itemId;?>.jpg" class="sc-lazy-image" alt="">
																</div>
																<div class="as24-carousel__item">
																<img data-src="https://secure.pic.autoscout24.net/images-420x315/349/279/0311279349002.jpg?31cf2cd718b184afd8fe2b6fa71836c5" src="" data-expand="-1" alt="" class="sc-lazy-image lazyload">
																</div>
																<div class="as24-carousel__item">
																<img data-src="https://secure.pic.autoscout24.net/images-420x315/349/279/0311279349003.jpg?31cf2cd718b184afd8fe2b6fa71836c5" src="" data-expand="-1" alt="" class="sc-lazy-image lazyload">
																</div>
																<div class="as24-carousel__item">
																<img data-src="https://secure.pic.autoscout24.net/images-420x315/349/279/0311279349004.jpg?31cf2cd718b184afd8fe2b6fa71836c5" src="" data-expand="-1" alt="" class="sc-lazy-image lazyload">
																</div>
															</div>
															<button class="as24-carousel__button" role="nav-button" data-direction="left"></button>
															<button class="as24-carousel__button" role="nav-button" data-direction="right"></button>
															<div class="as24-carousel__indicator" role="indicator">1/4</div>
															</as24-carousel>
														</a>
													</div>
												</div>
											</div>
											<div class="details-container">
												<a data-item-name="detail-page-link" href="post.php?postId=<?php echo $itemId;?>">
													<div class="title-container">
														<h2 class="title"><?php echo $postTitle;?></h2>
														<span data-test="price" data-long="false">
															<span data-test="price" data-long="false">
																<?php echo $price;?> Rwf
															</span>
														</span>
													</div>
													<h3 class="sub-title"></h3>
													<div class="details" data-has-categorized-price="false">
														<span data-test="price">
															<span data-test="price" data-long="false">
																<?php echo number_format($price);?> Rwf
															</span>
														</span>
														<span>|</span>
														<span data-test="milage">190,000 km</span>
														<span>|</span>
														<span data-test="firstRegistration">01/1999</span>
														<span>|</span>
														<span data-test="power">48 kW <span class="sc-font-s">65 hp</span></span>
													</div>
													<div class="envkv">
														<?php echo $description;?>
													</div>
													<div class="location">
														<hr/>
														<div class="sc-grid-row">
															<div data-item="vendor-data" class="sc-grid-col-8 sc-grid-col-s-12">
																<div data-item="vendorDataCompact">
																	Private
																	, NL-1271 NR Huizen
																</div>
																<div data-item="vendorType">
																	Private
																</div>
																<div data-item="vendorAddress">NL-1271 NR Huizen</div>
															</div>
														</div>
													</div>
												</a>
											</div>
										</div>
									</div>
								</li>
								
									<?php
									
									
					 }
				 }
				
				settings($sortCatid);
			}
		 $sortCatid;
		//echo $sortCatName;
		//echo '<br/>';
				
	}
		//echo $count;
	settings($sortCatid = $sortCatid);
	echo'</ul>';
}
?>