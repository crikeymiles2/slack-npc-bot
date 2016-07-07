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
  die($msg);
  echo $msg;
}

# Get the NPC Name parts
$last_name = file_get_contents('last-names.txt');
$last_name = explode("\n", $last_name);
shuffle($last_name);

# Handle the gender argument
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

# If no gender specified, pick one at random
if (!$gender) { 
	if (rand(0, 1)) { $gender = "m"; }
	else { $gender = "f"; }
}

# Generate an NPC.
if ( $gender == "m") {
	$first_name = file_get_contents('first-names-male.txt');
	$first_name = explode("\n", $first_name);
	shuffle($first_name);
	
	$emoji = file_get_contents('emoji-male.txt');
	$emoji = explode("\n", $emoji);
	shuffle($emoji);
	
	$reply = "$emoji[0] $first_name[0] $last_name[0]";	
}

if ( $gender == "f") {
	$first_name = file_get_contents('first-names-female.txt');
	$first_name = explode("\n", $first_name);
	shuffle($first_name);
	
	$emoji = file_get_contents('emoji-female.txt');
	$emoji = explode("\n", $emoji);
	shuffle($emoji);
	
	$reply = "$emoji[0] $first_name[0] $last_name[0]";	
}

# Send the reply back to the user. 
echo $reply;
