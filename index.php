<?php 


/*
SLACKBOT - MODERN UK NPC NAME GENERATOR
*/


# Grab all of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];
$team_id = $_POST['team_id'];
$team_domain = $_POST['team_domain'];
$channel_id = $_POST['channel_id'];
$channel_name = $_POST['channel_name'];
$user_id = $_POST['user_id'];
$user_name = $_POST['user_name'];
$response_url = $_POST['response_url'];


# Check the token and make sure the request is from our team 
if($token != 'SAL9CqWkuPZtN7mZnTKKxRZF'){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  //die($msg);
  echo $msg;
}


////////////////////////////////////////////
// Check if we got a regional selector   ///
////////////////////////////////////////////
$countrycode = array("jp", "uk", "gb", "en", "us");
$region = shuffle($countrycode);;

foreach ( $countrycode as $code ) {
	if ( strpos(strtolower($text), $code) !== false ) {
		$region = $code;
	}
}


////////////////////////////////
// Get the NPC Name parts  /////
////////////////////////////////

if ( $region == "uk" || $region == "gb" || $region == "en") {
	// UK Last Name
	$last_name = file_get_contents('last-names.txt');
	$last_name = explode("\n", $last_name);
	shuffle($last_name);

	// UK Female Names
	$first_name_female = file_get_contents('first-names-female.txt');
	$first_name_female = explode("\n", $first_name_female);
	shuffle($first_name_female);

	// UK Male Name
	$first_name_male = file_get_contents('first-names-male.txt');
	$first_name_male = explode("\n", $first_name_male);
	shuffle($first_name_male);
	
	// Regional Flag
	$flag = ":flag-gb:";
}

elseif ( $region = "jp" ) {
	// JP Last Name
	$last_name = file_get_contents('last-names-jp.txt');
	$last_name = explode("\n", $last_name);
	shuffle($last_name);
	
	// JP Female Names
	$first_name_female = file_get_contents('first-names-female-jp.txt');
	$first_name_female = explode("\n", $first_name_female);
	shuffle($first_name_female);

	// JP Male Name
	$first_name_male = file_get_contents('first-names-male-jp.txt');
	$first_name_male = explode("\n", $first_name_male);
	shuffle($first_name_male);
	
	// Regional Flag
	$flag = ":flag-jp:";	
}




////////////////////////////////
// Emojis				     ///
////////////////////////////////

// Female Emoji
$emoji_female = file_get_contents('emoji-female.txt');
$emoji_female = explode("\n", $emoji_female);
shuffle($emoji_female);

// Male Emoji
$emoji_male = file_get_contents('emoji-male.txt');
$emoji_male = explode("\n", $emoji_male);
shuffle($emoji_male);




////////////////////////////////
// Check if we have a gender ///
////////////////////////////////
$male = array("male", "man", "boy", "m");
$female = array("female", "woman", "girl", "f");
$gender = 0;

foreach($male as $needle)
{
    if(preg_match('/'. $needle .'/i', $text))
    {
        $gender = "m";
    }
}
foreach($female as $needle)
{
    if(preg_match('/'. $needle .'/i', $text))
    {
        $gender = "f";
    }
}

if (!$gender) { 
	if (rand(0, 1)) { $gender = "m"; }
	else { $gender = "f"; }
}



////////////////////////////////
// Generate an NPC Name!     ///
////////////////////////////////

if ( $gender == "m") {

	$first_name = $first_name_male;
	$emoji = $emoji_male;
	$reply = "$flag $emoji[0] $first_name[0] $last_name[0]";
}

if ( $gender == "f") {

	$first_name = $first_name_female;	
	$emoji = $emoji_female;
	$reply = "$flag $emoji[0] $first_name[0] $last_name[0]";	
}

# Send the reply back to the user. 
echo $reply;
