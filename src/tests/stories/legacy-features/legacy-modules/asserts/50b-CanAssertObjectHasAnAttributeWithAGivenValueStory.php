<?php

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup(['Modules', 'AssertsObject'])
         ->called('Can check that an object has an attribute with a given value');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	$checkpoint = getCheckpoint();

	// we'll use this in our comparisons
	$actualData = new stdClass;
	$actualData->attribute1 = null;
	$actualData->attribute2 = [];
	$actualData->attribute3 = true;
	$actualData->attribute4 = false;
	$actualData->attribute5 = 0.0;
	$actualData->attribute6 = 3.1415927;
	$actualData->attribute7 = 0;
	$actualData->attribute8 = 99;
	$actualData->attribute9 = $checkpoint;
	$actualData->attribute10 = "";
	$actualData->attribute11 = "hello, Storyplayer";

	// these should pass
	assertsObject($actualData)->hasAttributeWithValue('attribute1', null);
	assertsObject($actualData)->hasAttributeWithValue('attribute2', []);
	assertsObject($actualData)->hasAttributeWithValue('attribute3', true);
	assertsObject($actualData)->hasAttributeWithValue('attribute4', false);
	assertsObject($actualData)->hasAttributeWithValue('attribute5', 0.0);
	assertsObject($actualData)->hasAttributeWithValue('attribute6', 3.1415927);
	assertsObject($actualData)->hasAttributeWithValue('attribute7', 0);
	assertsObject($actualData)->hasAttributeWithValue('attribute8', 99);
	assertsObject($actualData)->hasAttributeWithValue('attribute9', $checkpoint);
	assertsObject($actualData)->hasAttributeWithValue('attribute10', "");
	assertsObject($actualData)->hasAttributeWithValue('attribute11', "hello, Storyplayer");

	// and all these should fail
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute0', true);
	}
	catch (Exception $e) {
		$checkpoint->test1Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', []);
	}
	catch (Exception $e) {
		$checkpoint->test2Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', true);
	}
	catch (Exception $e) {
		$checkpoint->test3Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', false);
	}
	catch (Exception $e) {
		$checkpoint->test4Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test5Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test6Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', 0);
	}
	catch (Exception $e) {
		$checkpoint->test7Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', 99);
	}
	catch (Exception $e) {
		$checkpoint->test8Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test9Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', "");
	}
	catch (Exception $e) {
		$checkpoint->test10Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute1', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test11Passed = true;
	}

	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', null);
	}
	catch (Exception $e) {
		$checkpoint->test12Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', true);
	}
	catch (Exception $e) {
		$checkpoint->test13Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', false);
	}
	catch (Exception $e) {
		$checkpoint->test14Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test15Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test16Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', 0);
	}
	catch (Exception $e) {
		$checkpoint->test17Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', 99);
	}
	catch (Exception $e) {
		$checkpoint->test18Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test19Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', "");
	}
	catch (Exception $e) {
		$checkpoint->test20Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute2', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test21Passed = true;
	}


	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', null);
	}
	catch (Exception $e) {
		$checkpoint->test22Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', []);
	}
	catch (Exception $e) {
		$checkpoint->test23Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', false);
	}
	catch (Exception $e) {
		$checkpoint->test24Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test25Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test26Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', 0);
	}
	catch (Exception $e) {
		$checkpoint->test27Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', 99);
	}
	catch (Exception $e) {
		$checkpoint->test28Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test29Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', "");
	}
	catch (Exception $e) {
		$checkpoint->test30Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute3', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test31Passed = true;
	}


	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', null);
	}
	catch (Exception $e) {
		$checkpoint->test32Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', []);
	}
	catch (Exception $e) {
		$checkpoint->test33Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', true);
	}
	catch (Exception $e) {
		$checkpoint->test34Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test35Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test36Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', 0);
	}
	catch (Exception $e) {
		$checkpoint->test37Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', 99);
	}
	catch (Exception $e) {
		$checkpoint->test38Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test39Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', "");
	}
	catch (Exception $e) {
		$checkpoint->test40Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute4', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test41Passed = true;
	}


	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', null);
	}
	catch (Exception $e) {
		$checkpoint->test42Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', []);
	}
	catch (Exception $e) {
		$checkpoint->test43Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', true);
	}
	catch (Exception $e) {
		$checkpoint->test44Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', false);
	}
	catch (Exception $e) {
		$checkpoint->test45Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test46Passed = true;
	}
	assertsObject($actualData)->hasAttributeWithValue('attribute5', 0.0);
	$checkpoint->test47Passed = true;
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', 99);
	}
	catch (Exception $e) {
		$checkpoint->test48Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test49Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', "");
	}
	catch (Exception $e) {
		$checkpoint->test50Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute5', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test51Passed = true;
	}

	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', null);
	}
	catch (Exception $e) {
		$checkpoint->test52Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', []);
	}
	catch (Exception $e) {
		$checkpoint->test53Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', true);
	}
	catch (Exception $e) {
		$checkpoint->test54Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', false);
	}
	catch (Exception $e) {
		$checkpoint->test55Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test56Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', 0);
	}
	catch (Exception $e) {
		$checkpoint->test57Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', 99);
	}
	catch (Exception $e) {
		$checkpoint->test58Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test59Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', "");
	}
	catch (Exception $e) {
		$checkpoint->test60Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute6', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test61Passed = true;
	}

	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', null);
	}
	catch (Exception $e) {
		$checkpoint->test62Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', []);
	}
	catch (Exception $e) {
		$checkpoint->test63Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', true);
	}
	catch (Exception $e) {
		$checkpoint->test64Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', false);
	}
	catch (Exception $e) {
		$checkpoint->test65Passed = true;
	}
	assertsObject($actualData)->hasAttributeWithValue('attribute7', 0);
	$checkpoint->test66Passed = true;
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test67Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', 99);
	}
	catch (Exception $e) {
		$checkpoint->test68Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test69Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', "");
	}
	catch (Exception $e) {
		$checkpoint->test70Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute7', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test71Passed = true;
	}

	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', null);
	}
	catch (Exception $e) {
		$checkpoint->test72Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', []);
	}
	catch (Exception $e) {
		$checkpoint->test73Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', true);
	}
	catch (Exception $e) {
		$checkpoint->test74Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', false);
	}
	catch (Exception $e) {
		$checkpoint->test75Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test76Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test77Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', 0);
	}
	catch (Exception $e) {
		$checkpoint->test78Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test79Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', "");
	}
	catch (Exception $e) {
		$checkpoint->test80Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute8', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test81Passed = true;
	}

	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', null);
	}
	catch (Exception $e) {
		$checkpoint->test82Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', []);
	}
	catch (Exception $e) {
		$checkpoint->test83Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', true);
	}
	catch (Exception $e) {
		$checkpoint->test84Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', false);
	}
	catch (Exception $e) {
		$checkpoint->test85Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test86Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test87Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', 0);
	}
	catch (Exception $e) {
		$checkpoint->test88Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', 99);
	}
	catch (Exception $e) {
		$checkpoint->test89Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', "");
	}
	catch (Exception $e) {
		$checkpoint->test90Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute9', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test91Passed = true;
	}

	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', null);
	}
	catch (Exception $e) {
		$checkpoint->test92Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', []);
	}
	catch (Exception $e) {
		$checkpoint->test93Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', true);
	}
	catch (Exception $e) {
		$checkpoint->test94Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', false);
	}
	catch (Exception $e) {
		$checkpoint->test95Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test96Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test97Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', 0);
	}
	catch (Exception $e) {
		$checkpoint->test98Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', 99);
	}
	catch (Exception $e) {
		$checkpoint->test99Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test100Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute10', "hello, Storyplayer");
	}
	catch (Exception $e) {
		$checkpoint->test101Passed = true;
	}

	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', null);
	}
	catch (Exception $e) {
		$checkpoint->test102Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', []);
	}
	catch (Exception $e) {
		$checkpoint->test103Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', true);
	}
	catch (Exception $e) {
		$checkpoint->test104Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', false);
	}
	catch (Exception $e) {
		$checkpoint->test105Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', 0.0);
	}
	catch (Exception $e) {
		$checkpoint->test106Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', 3.1415927);
	}
	catch (Exception $e) {
		$checkpoint->test107Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', 0);
	}
	catch (Exception $e) {
		$checkpoint->test108Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', 99);
	}
	catch (Exception $e) {
		$checkpoint->test109Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', $checkpoint);
	}
	catch (Exception $e) {
		$checkpoint->test110Passed = true;
	}
	try {
		assertsObject($actualData)->hasAttributeWithValue('attribute11', "");
	}
	catch (Exception $e) {
		$checkpoint->test111Passed = true;
	}

});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	for ($x = 1; $x <= 111; $x++) {
		$attributeName = "test{$x}Passed";
		assertsObject($checkpoint)->hasAttribute($attributeName);
		assertsBoolean($checkpoint->$attributeName)->equals(true);
	}
});
