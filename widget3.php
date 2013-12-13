<?php

/**
 * Copyright (c) 2012 Desire2Learn Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the license at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */



require_once 'libsrc/D2LAppContextFactory.php';
session_start();
/**
 * App ID, APP key, user ID and user key should all be unavailable to the end user of the application in real world scenarios
 */
if (isset($_SESSION['appId'])) {
    $appId = $_SESSION['appId'];
} else {
    // default Application ID
    $appId = 'G9nUpvbZQyiPrk3um2YAkQ';
    $_SESSION['appId']= $appId;
}



if (isset($_SESSION['appKey'])) {
    $appKey = $_SESSION['appKey'];
} else {
    // default Application key
    $appKey = 'ybZu7fm_JKJTFwKEHfoZ7Q';
    $_SESSION['appKey'] = $appKey;
}
if (isset($_SESSION['host'])) {
    $host = $_SESSION['host'];
} else {
    $host="valence.desire2learn.com";
}
if (isset($_SESSION['port'])) {
    $port = $_SESSION['port'];
} else {
    $port=443;
}
if (isset($_SESSION['scheme'])) {
    $scheme = $_SESSION['scheme'];
} else {
    $scheme = 'https';
}

$authContextFactory = new D2LAppContextFactory();
$authContext = $authContextFactory->createSecurityContext($appId, $appKey);
$hostSpec = new D2LHostSpec($host, $port, $scheme);
$opContext = $authContext->createUserContextFromHostSpec($hostSpec, null, null, $_SERVER["REQUEST_URI"]);
$uri = "";


if ($opContext!=null) {
    $userId = $opContext->getUserId();
    $userKey = $opContext->getUserKey();
    $_SESSION['userId'] = $userId;
    $_SESSION['userKey'] = $userKey;
	$uri = $opContext->createAuthenticatedUri("/d2l/api/lp/1.0/profile/myProfile/image", "GET");
} elseif (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    if (isset($_SESSION['userKey'])) {
        $userKey = $_SESSION['userKey'];
    } else {
        $userKey = '';
    }
} else {
    $userId = '';
    $userKey = '';
}
session_write_close();
?>

<!DOCTYPE html>
<html>
<head>
<title>User Profile D2L Widget</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<script src="sample.js"></script>
<script src="js/base64.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type = "text/javascript"></script>
<script src="js/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css">
<!--<link href="developer.css" rel="stylesheet" type="text/css">-->

</head>
<body>
<div class="box">
  <div id="map">
    <div class="roadVert4 "></div>
    <div class="roadVert5 "></div>
    <div class="roadVert3 "></div>
    <div class="roadVert2"></div>
    <div class="roadVert1 "></div>
    <div class="roadVert10 "></div>
    <div class="roadHor3_copy_3 "></div>
    <div class="roadHor3_copy"></div>
    <div class="roadHor3_copy_5"></div>
    <div class="roadHor3_copy_4 "></div>
    <div class="roadHor3_copy_6 "></div>
    <div class="roadHor3_copy_2 "></div>
    <div class="roadHor3 "></div>
    <div class="roadHor2"></div>
    <div class="roadHor1 "></div>
    <div class="round6 round "></div>
    <div class="round1 round"></div>
    <div class="round5 round"></div>
    <div class="round4 round"></div>
    <div class="round3 round"></div>
    <div class="round2 round"></div>
  </div>
  <div id="profile">
    <div class="Fullname"> <span id="firstName"></span> <span id="nickName"></span> <span id="lastName"></span> </div>
    <div class="level"></div>
    <?php /*?>    <?php echo "<img src='" . $uri . "' />"; ?><?php */?>
    <div class="Denominator" style="text-align:right;"></div>
    <div class="progress progress-striped active" style="width: 60%;">
      <div class="bar" style="width: 0%;"></div>
    </div>
  </div>
  <div id="case">
    <ul id="badgeCase" style="margin-left:0;">
      <ul id="blue">
      </ul>
      <li class="badges basic"></li>
      <li class="badges toxic"></li>
      <li class="badges insect"></li>
      <li class="badges bolt"></li>
      <li class="badges quake"></li>
      <li class="badges jet"></li>
      <li class="badges legend"></li>
      <li class="badges wave"></li>
    </ul>
  </div>
</div>
<div style="float:left;">
  <div id="fire"></div>
  <h3>TOC</h3>
  <pre id="TOC"></pre>
  <h2>Root</h2>
  <pre id="root"></pre>
  <div id="grades">
    <h3>grades</h3>
    <pre class="grades"></pre>
  </div>
</div>
</body>
<script type="text/javascript">

//1- Decalartions you should change these to fit your LMS, also the AppID and the AppKey should not be visible as they are here
	var host = "fusionresources.desire2learn.com";
	var AppID = "z3vGZGtLSMGCRhoDHHvFzw";
	var AppKey = "9-b4GdL47m-H32sW7vgUQA";
	var scheme = "https";
	var port = "443";
	var orgID = "7400";
	var userID = "<?php echo $userId; ?>";
	var userKey ="<?php echo $userKey; ?>";

	

//2 - Inital Styling
$('.basic,.toxic,.insect,.bolt,.quake,.jet,.legend,.wave,.roadVert10,.roadHor3,.roadHor2  ').hide();
//3-Classes
//4-Functions - each of these here are callbacks for ajax calls later on
	function getUser(userInfo) {
		userInfo = JSON.parse(userInfo);
		$("#firstName").append(userInfo["FirstName"]);
		$("#lastName").append(userInfo["LastName"]);
	}

	function getUserInfo(userProfile) {
		userProfile = JSON.parse(userProfile);
		var Nname = userProfile["Nickname"];
		
		if (userProfile["Nickname"]!=null) {
			$("#nickName").append('"' + userProfile["Nickname"] + '"');
		}
	}
	
	function createFullURL(urlPart, id) {
	//	console.log(id + " " + urlPart);
		var base = scheme + "://" + host;
		if (urlPart.indexOf("http:") === -1) {
			base += "/d2l/le/content/"+ orgID + "/viewContent/" +id+"/View";
		} else {
			base = urlPart;
		}
		return base;
	}

	//Get Table of Contents
	function getTOC(TOC){
		TOC = JSON.parse(TOC);
		var length=0;
		for(var dummy in TOC) length++;
		$('#TOC').append(JSON.stringify(TOC,null,4));
		
		//badges for simply reading things
		if (TOC.Modules[0].Topics[0].Unread == "false"){
			$(".toxic").show();
		}
		if (TOC.Modules[1].Topics[2].Unread == "false"){
			$(".bolt").show();
		}
		if (TOC.Modules[1].Topics[3].Unread == "false"){
			$(".wave").show();
		}
		if (TOC.Modules[2].Modules[2].Topics[0].Unread == "false"){
			$(".quake").show();
		}
		
		//Map creation
		var html = '<ul>';
		var fullURL = "";
		//Loop through Modules
   		for (var a = 0; a < TOC.Modules.length; a++) {
			var allRead = 'read';
			var topicList =  '<ul>';
			var fire= '<ul>';
			
			//handle inner modules first, aka modules inside modules
			//note - we only go 2 levels deep here, if you want more, a dynamic solution would be better
			for(var b=0;b<TOC.Modules[a].Modules.length;b++){
				//topics inside inner modules
				for(var bb=0;bb<TOC.Modules[a].Modules[b].Topics.length;bb++){
					
					//for each topic add each to a list, color according to whether the topic is read or not
					var read= 'read';
					if (TOC.Modules[a].Modules[b].Topics[bb].Unread){
						read='unread';
						allRead='unread';
					}
					fullURL = createFullURL(TOC.Modules[a].Modules[b].Topics[bb].Url, TOC.Modules[a].Modules[b].Topics[bb].TopicId);
					fire += '<a target="_parent" class="contentLink" href="' + fullURL +'" title="' +  TOC.Modules[a].Modules[b].Topics[bb].Title +  '"><li class="topic ' + 'T' + TOC.Modules[a].Modules[b].Topics[bb].TopicId + '  ' + read + ' "></li></a>';
					
				}
			}
			//Topics inside the outer module
        	for (var c = 0; c < TOC.Modules[a].Topics.length; c++) {
				var read= 'read';
				if (TOC.Modules[a].Topics[c].Unread){
					read='unread';
					allRead='unread';
				}
				//topicList += '<li class="topic ' + 'T' + TOC.Modules[a].Topics[b].TopicId + '  ' + read + ' ">"';
				fullURL = createFullURL(TOC.Modules[a].Topics[c].Url, TOC.Modules[a].Topics[c].TopicId);
				topicList += '<a target="_parent" class="contentLink" href="' + fullURL +'" title="' +  TOC.Modules[a].Topics[c].Title +  '"><li class="topic ' + 'T' + TOC.Modules[a].Topics[c].TopicId + '  ' + read + ' "></li></a>';
				//console.log(c);
			}
			topicList+=fire;
			html += topicList;
       		html += '</ul></li>';
   		}
   		html += '</ul>';
		
  		$('#map').append(html);
	}
	
	
	function getContentRoot(root){
		root = JSON.parse(root);
		//$('#root').append(JSON.stringify(root,null,4));
	}
	
	
	//Badge System and Leveling up
	var badgeClasses=['basic','toxic','insect','bolt','quake','jet','legend','wave'];
	function getGrades(grades) {
		grades = JSON.parse(grades);
		var sumD = 0;
		var sumN = 0;
	    for (var i = 0; i < grades.length; i++) {
			if (grades[i].GradeObjectIdentifier != 290 && grades[i].GradeObjectIdentifier != 941) { //skip the module grades, would like better way to identify these eventually
       			sumD += parseInt(grades[i].PointsDenominator);
				sumN += parseInt(grades[i].PointsNumerator);
			}
		}
		
		//medals system need to be refactored. Something similar to the level system would be better
		//Medals  one
		//GradeObjectName 6: Individual Identification Plan - Objectives Pretest
		console.log('GradeObjectName 6: '+grades[6].GradeObjectName);
		var oneD=grades[6].PointsDenominator;
		var oneN=grades[6].PointsNumerator;
		var oneT=oneN/oneD*100;
		
		console.log('GradeObjectName 6: '+oneT);
		
		//reveal shortcut if the user got above a 90%
		if(oneT>=90) {
		$(".roadHor3").show();
		}
		
		if (oneT>=80) {
			$("#blue").append('<li title="'+grades[6].GradeObjectName +'"class="medals bronze one"></li>');
		} else if (oneT>=60) {
			$("#blue").append('<li title="'+grades[6].GradeObjectName +'"class="medals purple one"></li>');
		} else if (oneT>=40) {
			$("#blue").append('<li title="'+grades[6].GradeObjectName +'"class="medals red one"></li>');
		} else if (oneT>9) {
			$("#blue").append('<li title="'+grades[6].GradeObjectName +'"class="medals blue one"></li>');
		}
		
		//Medals  two
		//Individual Identification Plan - Technologies Pretest 
		console.log('GradeObjectName 9: '+grades[9].GradeObjectName);

		var twoD=grades[9].PointsDenominator;
		var twoN=grades[9].PointsNumerator;
		var twoT=twoN/twoD*100;
		console.log('GradeObjectName 9: '+twoT);
		
		//show shortcut pathway if grade is above 90%
		if(twoT>=90){
			$(".roadVert10").show();
		}
		
		if (twoT>=90){
			$("#blue").append('<li title="'+grades[9].GradeObjectName +'"class="medals bronze two"></li>');
		} else if (twoT>=60){
			$("#blue").append('<li title="'+grades[9].GradeObjectName +'"class="medals purple two"></li>');
		}else if (twoT>=40){
			$("#blue").append('<li title="'+grades[9].GradeObjectName +'"class="medals red two"></li>');
		}else if (twoT>9){
			$("#blue").append('<li title="'+grades[9].GradeObjectName +'"class="medals blue two"></li>');
		}
		
		//Medals  three
		console.log('GradeObjectName 10: '+grades[10].GradeObjectName);
		var threeD=grades[10].PointsDenominator;
		var threeN=grades[10].PointsNumerator;
		var threeT=threeN/threeD*100;
		console.log('GradeObjectName 10: '+threeT);
		
		//show shortcut pathway if grade is above 90%
		if(oneT>=90){
			$(".roadHor3").show();
		}
		
		if (threeT>=80){
			$("#blue").append('<li title="'+grades[10].GradeObjectName +'"class="medals bronze three"></li>');
		} else if (threeT>=60){
			$("#blue").append('<li title="'+grades[10].GradeObjectName +'"class="medals purple three"></li>');
		}else if (threeT>=40){
			$("#blue").append('<li title="'+grades[10].GradeObjectName +'"class="medals red three"></li>');
		}else if (threeT>10){
			$("#blue").append('<li title="'+grades[10].GradeObjectName +'"class="medals blue three"></li>');
		}
		
		//Medals  four
		var fourD=grades[11].PointsDenominator;
		var fourN=grades[11].PointsNumerator;
		var fourT=fourN/fourD*100;
		
		if (oneT>=90) {
			$(".roadHor2").show();
		}

		if (fourT>=80){
			$("#blue").append('<li  title="'+grades[11].GradeObjectName +'"class="medals bronze four"></li>');
		}else if (fourT>=60){
			$("#blue").append('<li  title="'+grades[11].GradeObjectName +'"class="medals purple four"></li>');
		}else if (fourT>=40){
			$("#blue").append('<li  title="'+grades[11].GradeObjectName +'"class="medals red four"></li>');
		}else if (fourT>10){
			$("#blue").append('<li title="'+grades[11].GradeObjectName +'"class="medals blue four"></li>');
		}
		
		//Medals  five
		var fiveD=grades[12].PointsDenominator;
		var fiveN=grades[12].PointsNumerator;
		var fiveT=fiveN/fiveD*100;
		if (fiveT>11){
			$("#blue").append('<li  title="'+grades[12].GradeObjectName +'"class="medals blue five"></li>');
		} else if (fiveT>=40){
			$("#blue").append('<li  title="'+grades[12].GradeObjectName +'"class="medals red five"></li>');
		}else if (fiveT>=60){
			$("#blue").append('<li  title="'+grades[12].GradeObjectName +'"class="medals purple five"></li>');
		}else if (fiveT>=80){
			$("#blue").append('<li  title="'+grades[12].GradeObjectName +'"class="medals bronze five"></li>');
		}			
		
		//badges should also be redone, again something closer to the levels system would be better
		
		//basic badge
		var basicD=grades[0].PointsDenominator;
		var basicN=grades[0].PointsNumerator;
		var basicT=basicN/basicD*100;
		if (basicT>=90){
			$(".basic").show();
		}
		
		//legend badge
		var legendD=grades[4].PointsDenominator;
		var legendN=grades[4].PointsNumerator;
		var legendT=legendN/legendD*100;
		if (legendT>=89){
			$(".legend").show();
		}
		
		//insect badge
		var insectD=grades[5].PointsDenominator;
		var insectN=grades[5].PointsNumerator;
		var insectT=insectN/insectD*100;
		if (insectT>=89){
			$(".insect").show();
		}
		
		
		//jet jet
		var jetD=grades[8].PointsDenominator;
		var jetN=grades[8].PointsNumerator;
		var jetT=jetN/jetD*100;
		if (jetT>=89){
			$(".jet").show();
		}
		
		//Level System
		var exP=[0, 50, 100, 175, 250, 340];
		var  percent2Level = 0;
		for (var i=exP.length; i>1;i-- ){
			if (sumN >= exP[i-1]){
				$('.level').append('<span class="levelTXT">LV.</span> ' + i);
				var earnedNextEXP = sumN-exP[i-1];
				var totalNextEXP = exP[i]-exP[i-1];
				percent2Level = earnedNextEXP/totalNextEXP*100.0;
				break;
			}
		}
		
		//Jquery Read Outs
		$('.Denominator').append('<div class="greg" >XP:'+ sumN + '/' +sumD+'</div>');
    	$('.fire').append(grades[0].PointsNumerator);
		$('.bar').css('width',percent2Level+'%');
		$('.totalEXP').css('width',percent2Level+'%');
		$('.grades').append(JSON.stringify(grades,null,4));

	}

	//Initial Setup.
	$(document).ready(function() {
		//if the user id was not set by php
		if (userID  == "") {
			var dataToSend  = {
				"hostField": host,
				"portField": port,
				"schemeField": scheme,
				"appIDField": AppID,
				"appKeyField": AppKey,
				"authBtn": ""
			}

			//inital ajax request to get the user key and user id
			$.ajax({

				url: "authenticateUser.php",
				data: dataToSend,
				type: "get",
				success: function(data) {
					console.log("connected to D2L");
					data = JSON.parse(data);
					console.log(data);
					window.location = data['url'];

				}, 
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(jqXHR);
					console.log(textStatus);
					console.log(errorThrown);
				}
			});

		} else {
			//set of valence calls, each with their own callback
			doAPIRequest2(host, port, scheme, "/d2l/api/lp/1.0/profile/myProfile", "GET", "", false, getUserInfo);
			doAPIRequest2(host, port, scheme, "/d2l/api/le/1.0/" + orgID + "/content/toc", "GET", "", false, getTOC);
			doAPIRequest2(host, port, scheme, "/d2l/api/lp/1.0/users/whoami", "GET", "", false, getUser);
			doAPIRequest2(host, port, scheme, "/d2l/api/le/1.0/" + orgID + "/content/root/", "GET", "", false, getContentRoot);
			doAPIRequest2(host, port, scheme, "/d2l/api/le/1.0/" + orgID + "/grades/values/myGradeValues/", "GET", "", false, getGrades);

			//request to get the image of the current user, the request works, but the data is difficult to use in javascript, best solution may be to use server side code to write the stream to a file and get the url from there
			//doAPIRequest2(host, port, scheme, "/d2l/api/lp/1.0/profile/myProfile/image", "GET", "", false, getIMG);
		}
	});

    $("body").ajaxError(function(e, request) {
        console.log("AJAX error!");
    });
</script>
</html>