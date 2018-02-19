
<?php
	function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
		$earthRadius = 6371; // Earth’s mean radius in meter

		$latFrom = deg2rad($latitude1);
		$lonFrom = deg2rad($longitude1);
		$latTo = deg2rad($latitude2);
		$lonTo = deg2rad($longitude2);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * $earthRadius; // returns the distance
	};

	$latitude1 = 38.898556;
	$longitude1 = -77.037852;

	$latitude2 = 48.223434;
	$longitude2 = -77.063934;

	$distance = getDistance($latitude1, $longitude1, $latitude2, $longitude2);

	echo "".$distance."km";

?>