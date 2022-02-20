<link rel="stylesheet" href="styles.css">
<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

include('./classes/DB.php');
include('./classes/Login.php');
include('./classes/User.php');


if(Login::isLoggedIn()){
  if(User::isTutor()){
    echo '<div class="body_class div_div">';
    echo ' <a href="index.php"><button type="button"; id="logo_menu"><img src="https://i.imgur.com/DwL1VVl.png" width="350px" style="position:relative; top:0px;"></button></a>';
	echo '<div class="menu_button_div">';
	echo'
	<a href="profile.php?id='.Login::isLoggedIn().'" title="Profile"><button type="button" class="top_menu_button";"><img src="https://i.imgur.com/gu6rwGm.png" id="menu_search_img"><label class="menu_subscript text" style="left:-2px;">Account</label></button></a>
    <a href="friends.php" title="Friends"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/YfnMxSs.png" id="menu_search_img"><label class="menu_subscript text">Friends</label></button></a>  
    <a href="all-requests.php" title="View all requests"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/9CDc7Zh.png" id="menu_search_img"><label class="menu_subscript text" style="left:-5px;">Requests</label></button></a>  
    <a href="messages.php" title="Messages"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/UmyOltF.png" id="menu_search_img"><label class="menu_subscript text" style="left:-7px">Messages</label></button></a>  
    <a href="create-offer.php" title="Create a new offer"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/58GKSMi.png" id="menu_search_img"><label class="menu_subscript text">Offer</label></button></a>
    <a href="search.php" title="Search"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/oWS0lE6.png" id="menu_search_img"><label class="menu_subscript text" style="left:1px;">Search</label></button></a>
    <a href="logout.php" title="Logout"><button type="button" class="top_menu_button" style=""><img src="https://i.imgur.com/HyiVmBR.png" id="menu_search_img"><label class="menu_subscript text" style="left:1px;">Logout</label></button></a> <br> ';
	echo '</div>';
	echo '</div>';
  } else {
    echo '<div class="body_class div_div">';
    echo ' <a href="index.php"><button type="button"; id="logo_menu"><img src="https://i.imgur.com/DwL1VVl.png" width="350px" style="position:relative; top:0px;"></button></a>';
	echo '<div class="menu_button_div">';
	echo '
	<a href="profile.php?id='.Login::isLoggedIn().'" title="Profile"><button type="button" class="top_menu_button";"><img src="https://i.imgur.com/gu6rwGm.png" id="menu_search_img"><label class="menu_subscript text" style="left:-2px;">Account</label></button></a>
    <a href="friends.php" title="Friends"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/YfnMxSs.png" id="menu_search_img"><label class="menu_subscript text">Friends</label></button></a>  
    <a href="all-offers.php" title="View all offers"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/9CDc7Zh.png" id="menu_search_img"><label class="menu_subscript text" style="left:0px;">Offers</label></button></a>  
    <a href="messages.php" title="Messages"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/UmyOltF.png" id="menu_search_img"><label class="menu_subscript text" style="left:-7px">Messages</label></button></a>  
    <a href="create-request.php" title="Create a new request"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/58GKSMi.png" id="menu_search_img"><label class="menu_subscript text">Request</label></button></a>
    <a href="search.php" title="Search"><button type="button" class="top_menu_button"><img src="https://i.imgur.com/oWS0lE6.png" id="menu_search_img"><label class="menu_subscript text" style="left:1px;">Search</label></button></a>
    <a href="logout.php" title="Logout"><button type="button" class="top_menu_button" style=""><img src="https://i.imgur.com/HyiVmBR.png" id="menu_search_img"><label class="menu_subscript text" style="left:1px;">Logout</label></button></a> <br> ';
	echo '</div>';
	echo '</div>';
    }
  } else {
    echo '<script type="text/javascript">';
    echo 'window.location.replace("login-failed.php");';
    echo '</script>';
}

if(Login::isLoggedIn()){
	if (isset($_POST['addfriend'])) {
    	$userid = Login::isLoggedIn();
    	if(isset($_POST['ftarget'])) {
    		$ftarget = $_POST['ftarget'];
        	if(DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$ftarget))){
            	$ftargetid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$ftarget))[0]['id'];
            	$isValid = '1';
            	$userfriendrequests = DB::query('SELECT * FROM friend_requests WHERE sender=:user OR receiver=:user', array(':user'=>$userid));
            	$userfriends = DB::query('SELECT * FROM friends WHERE user_1=:user_1 OR user_2=:user_1', array(':user_1'=>$userid));
            	foreach($userfriends as $p){
            	    $user1 = $p['user_1'];
            	    $user2 = $p['user_2'];
            	    if($user1 == $ftargetid){
            	        $isValid = '0';
            	    } else if($user2 == $ftargetid){
            	        $isValid = '0';
            	    }
            	}
            	foreach($userfriendrequests as $p){
            	    if($p['status'] == 'pending'){
            	        if($p['sender'] == $userid){
            	            $isValid = '2';
            	        } else if($p['receiver'] == $userid){
            	            $isValid = '3';
            	        }
            	    }
            	}
            	if($isValid == '1') {
            	    $user_2 = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_POST['ftarget']))[0]['id'];
            	    DB::query('INSERT INTO friend_requests (sender, receiver, status) VALUES (:sender, :receiver, :status)', array(':sender'=>$userid, ':receiver'=>$user_2, ':status'=>'pending'));
            	    print ("Successfully sent friend request to {$_POST['ftarget']}!");
            	} else if($isValid == '2'){
            	    print('You already have an outgoing friend request to this person!');
            	} else if($isValid == '3'){
            	    print('This person has already sent you a friend request! Check your pending friend requests!');
            	} else if($isValid == '0'){
            	    print ("You're friends with this person already!");
            	}
        	} else {
				print("This person does not exist. Did you mistype?");
            }
        }
    }
	if (isset($_POST['fremove'])) {
		$userid = Login::isLoggedIn();
		if(isset($_GET['target_id'])){
			$user_2 = $_GET['target_id'];
			$friendsid = $_GET['id'];
			$user_2_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$user_2))[0]['username'];
			DB::query('DELETE FROM friends WHERE id=:id', array(':id'=>$friendsid));
			print("Successfully removed $user_2_name from your friends list!");
		} else {
			print ('Error: We do not know what friend to remove!');
		}
		
	}
	if(isset($_POST['accept'])){
		$userid = Login::isLoggedIn();
		if(isset($_GET['req_id'])){
			$request_id = $_GET['req_id'];
			$sender = DB::query('SELECT sender FROM friend_requests WHERE id=:id', array(':id'=>$request_id))[0]['sender'];
			if(empty(DB::query('SELECT * FROM friends WHERE user_1=:user AND user_2=:user1', array(':user'=>$userid, ':user1'=>$sender)))){
				if(empty(DB::query('SELECT * FROM friends WHERE user_1=:user AND user_2=:user1', array(':user'=>$sender, ':user1'=>$userid)))){
					DB::query('INSERT INTO friends (user_1, user_2) VALUES (:user_1, :user_2)', array(':user_1'=>$userid, ':user_2'=>$sender));
					DB::query('UPDATE friend_requests SET status=:status WHERE id=:id', array(':status'=>'accepted', ':id'=>$request_id));
				} else {
					print('ERROR: You cannot accept a friend request from someone who is already on your friends list!');
				}
			} else {
				print('ERROR: You cannot accept a friend request from someone who is already on your friends list!');
			}

		} else {
			print('Error: We do not know what friend request to accept!');
		}
	}
	if(isset($_POST['reject'])){
		$userid = Login::isLoggedIn();
		if(isset($_GET['req_id'])){
			$request_id = $_GET['req_id'];
			$sender = DB::query('SELECT sender FROM friend_requests WHERE id=:id', array(':id'=>$request_id))[0]['sender'];
			DB::query('UPDATE friend_requests SET status=:status WHERE id=:id', array(':status'=>'rejected', ':id'=>$request_id));
		} else {
			print('Error: We do not know what friend request to reject!');
		}
	}
	if(isset($_POST['message'])){
		if(isset($_GET['target_id'])){
			$target = $_GET['target_id'];
			header("Location: messages.php?usr_id=$target");
		}
	}

} else {
	die('Not logged in');
}

?>
<link rel="stylesheet" href="styles.css">

<div class="body_class big_div">
<div class="body_class grid_layout">
<h1 class="text page_header" id="friends_header" style="grid-column:2; grid-row:1;">Friends Menu</h1>
<div class="text" style="position:relative; top: 80px; left:80px; grid-column:1; grid-row:2;">
<form action="friends.php" method="post">
	<input type="text" name="ftarget" placeholder="username" class="text input_box" id="search_friends">
	<input type="submit" name="addfriend" value="Add Friend" class="text button" style="position:relative; top:-2px;">
</form>
</div>

<?php
$pendingrequests = '0';
if(DB::query('SELECT * FROM friend_requests WHERE receiver=:receiver AND status=:status', array(':receiver'=>Login::isLoggedIn(), ':status'=>'pending'))){
	$pendingrequests = DB::query('SELECT * FROM friend_requests WHERE receiver=:receiver AND status=:status', array(':receiver'=>Login::isLoggedIn(), ':status'=>'pending'));
}
print('<div class="body_class" id="div_friends">');
if($pendingrequests!='0'){
	foreach($pendingrequests as $p){
		$sender_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$p['sender']))[0]['username'];
		print("<div style=\"gird-column:2; grid-row:2;\">");
    	print($sender_name);
		print("</br />");
		print("<form action=\"friends.php?req_id={$p['id']}\"");
		print("method=\"post\">");
		print("<input type=\"submit\" name=\"accept\" id=\"accept_friend\" value=\"Accept\" class=\"button text\">");
		print("<input type=\"submit\" name=\"reject\" id=\"reject_friend\" value=\"Reject\" class=\"button text\">"."</br />");
		print("</form>");
    	print("</div>");
	}
} else {
	print('No pending friend requests </br />');
}
print('</div>');

?>

<h2 style="grid-column:1; grid-row:2;">Your friends</h2>
<?php

$userid = Login::isLoggedIn();
$friends = array_merge(DB::query('SELECT * FROM friends WHERE user_1=:userid', array(':userid'=>$userid)), DB::query('SELECT * FROM friends WHERE user_2=:userid', array(':userid'=>$userid)));
if(empty($friends)){
	print('<p style="grid-column:1; grid-row:3;">');
	print('You seem to have added no friends. Add some to have them display here!');
	print('</p>');
}
foreach($friends as $p){
	$friend_name = '';
	$friend_id = '';
	if($p['user_1']==Login::isLoggedIn()){
		$friend_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$p['user_2']))[0]['username'];
		$friend_id = $p['user_2'];
	} else {
		$friend_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$p['user_1']))[0]['username'];
		$friend_id = $p['user_1'];
	}
	print("</br />");
	print("<div>");
	print($friend_name);
	print("<form action=\"friends.php?target_id=$friend_id&id={$p['id']}\"");
	print("method=\"post\">");
	print("<input type=\"submit\" name=\"fremove\" value=\"Remove Friend\">"."</br />");
	print("<input type=\"submit\" name=\"message\" value=\"Send Message\">"."</br />");
	print("</form>");
	print("</div>");
	print("<hr />");
}

print("</div>");
print("</div>");
?>
