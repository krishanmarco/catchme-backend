<?php
die("FAIL");
echo "Script Starting...\n\n";

function call($httpGetParams) {
	 $ch = curl_init();
	 $url = "http://www.catchme.krishanmadan.website/api/fake?" . $httpGetParams;
	 echo "Calling " . $url . "\n";
	 curl_setopt($ch, CURLOPT_URL, $url);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 $output = curl_exec($ch);
	 echo $output . "\n";
	 curl_close($ch);
}

function callWithSplit($function, $p1, $p2, $range) {
	for ($i = $p1; $i < $p2; $i += $range)
		call("function=" . $function . "&p1=" . $i . "&p2=" . ($i + $range));
}

echo "Generating fake users...\n\n";
call("function=generateFakeUsers");

echo "Generating fake locations...\n\n";
call("function=generateFakeLocations");

echo "Generating fake Location images...\n\n";
call("function=generateFakeLocationImages");

echo "Generating fake user connections...\n\n";
callWithSplit("generateFakeUserConnections", 0, 5000, 50);

echo "Generating fake user favorites...\n\n";
callWithSplit("generateFakeUserFavorites", 0, 5000, 50);

echo "Generating fake user locations...\n\n";
callWithSplit("generateFakeUserLocations", 0, 5000, 50);

echo "Generating fake user locations expired...\n\n";
callWithSplit("generateFakeUserLocationExpired", 0, 5000, 50);

echo "Script complete...\n";