#!/bin/bash

echo "Script Starting...";

# $1 == httpGetParams
function call() {
	_httpGetParams = $1;
	curl "http://www.catchme.krishanmadan.website/api/fake?" + _httpGetParams;
}

# $1 == function
# $2 == min p1
# $3 == max p2
# $4 == range
function callWithSplit() {
	_function = $1;
	_p1 = $2;
	_p2 = $3;
	_range = $4;
	
	for ((i = _p1; i < _p2; i = i + range)) {
		call "function=" + _function + "&p1=" + _p1 + "&p2=" + (_p1 + range);
	}
	
}

echo "Generating fake users...";
call "function=generateFakeUsers";

echo "Generating fake locations...";
call "function=generateFakeLocations";

echo "Generating fake Location images...";
call "function=generateFakeLocationImages";

echo "Generating fake user connections...";
callWithSplit "generateFakeUserConnections" 0 5000 50;

echo "Generating fake user favorites...";
callWithSplit "generateFakeUserFavorites" 0 5000 50;

echo "Generating fake user locations...";
callWithSplit "generateFakeUserLocations" 0 5000 50;

echo "Generating fake user locations expired...";
callWithSplit "generateFakeUserLocationExpired" 0 5000 50;

echo "Script complete...";