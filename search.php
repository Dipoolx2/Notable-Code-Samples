<link rel="stylesheet" href="styles.css">
<?php

include('./classes/DB.php');
include('./classes/Login.php');
include('./classes/User.php');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

$type_init = 'offers';
$user_id = 0;
$switch_type_init = 'offers';

if(Login::isLoggedIn()){
  $user_id = Login::isLoggedIn();
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

if(!isset($_GET['type'])){
  if(User::isTutor()){
      $type_init = 'requests';
      $switch_type_init = 'offers';
  } else {
      $type_init = 'offers';
      $switch_type_init = 'requests';
  }
} else {
  $type_init = $_GET['type'];
  if($_GET['type'] == 'requests'){
      $switch_type_init = 'offers';
    } else {
      $switch_type_init = 'requests';
    }
  
}

$switch_type = $switch_type_init;
$type = $type_init;

if(isset($_POST['switch'])){
  if($_GET['type'] == 'requests'){
    header('Location: search.php?type=requests');
    } else {
    header('Location: search.php?type=offers');
    }
  
}

echo '
    <div class="big_div">
    <h1 class="text page_header">Search '.$type.'</h1>
    <form action="search.php?type='.$switch_type.'" method="post" style="position: relative; top: -40px; margin-left: 50px; padding:20px;">
    <label for="switchoffers" class="text" style="color: var(--dark-blue); font-size: 20px;">Want to switch search type?</label><br>
    <input type="submit" name="switch" value="Click here" class="button text" style="top:13px; left:45px;">
    </form>';
?>

<form action="search.php?type=<?php print($type)?>" method="post">
<div class="body_class grid_layout">
<div class="body_class" id="search_subject">
  <label for="subjects" id="subjects" class="text" style="position:relative; left: 40px;">Subject:</label>
  <select name="subjects" id="subjects" class="input_box  cr_req_selv">
    <option value="empty" disabled selected><label class="text">Please select a subject</label></option>
    <option value="mathematics_a" class="text">Mathematics A</option>
    <option value="mathematics_b" class="text">Mathematics B</option>
    <option value="mathematics_c" class="text">Mathematics C</option>
    <option value="mathematics_d" class="text">Mathematics D</option>
    <option value="physics" class="text">Physics</option>
    <option value="chemistry" class="text">Chemistry</option>
    <option value="biology" class="text">Biology</option>
    <option value="NLT" class="text">NLT</option>
    <option value="geography" class="text">Geography</option>
    <option value="history" class="text">History</option>
    <option value="economy" class="text">Economy</option>
    <option value="business_economy" class="text">Business Economy</option>
    <option value="social_sciences" class="text">Social Sciences</option>
    <option value="social_studies" class="text">Social Studies</option>
    <option value="history_of_arts" class="text">History of Arts</option>
    <option value="BSM" class="text">BSM</option>
    <option value="drama" class="text">Drama</option>
    <option value="music" class="text">Music</option>
    <option value="visual_arts" class="text">Visual Arts</option>
    <option value="english_language" class="text">English Language</option>
    <option value="dutch_language" class="text">>Dutch Language</option>
    <option value="french_language" class="text">French Language</option>
    <option value="german_language" class="text">German Language</option>
  </select>
  </div>

<div class="body_class" id="search_place">
  <label class="text cr_req_pos_box">Online or physical?:</label><br>
  <input type="radio" id="physical" name="place" value="physical" class="selection_cirkle cr_req_pos_box">
  <label for="physical" class="text cr_req_pos_box">Physical</label><br>
  <input type="radio" id="online" name="place" value="online" class="selection_cirkle cr_req_pos_box">
  <label for="online" class="text cr_req_pos_box">Online</label><br>
  <input type="radio" id="both" name="place" value="both" class="selection_cirkle cr_req_pos_box">
  <label for="both" class="text cr_req_pos_box">Fine with both</label>
  </div>
<div class="body_class" id="search_days">
  <label for="mon" class="text cr_req_pos_box2" id="cr_req_seld">Days available:</label><br>
  <input type="checkbox" name="mon" value="Monday"><label class="text cr_req_pos_box2">Monday</label><br>
  <input type="checkbox" name="tue" value="tuesday"><label class="text cr_req_pos_box2">Tuesday</label><br>
  <input type="checkbox" name="wed" value="Wednesday"><label class="text cr_req_pos_box2">Wednesday</label><br>
  <input type="checkbox" name="thur" value="Thursday"><label class="text cr_req_pos_box2">Thursday</label><br>
  <input type="checkbox" name="fri" value="Friday"><label class="text cr_req_pos_box2">Friday</label><br>
  <input type="checkbox" name="sat" value="Saturday"><label class="text cr_req_pos_box2">Saturday</label><br>
  <input type="checkbox" name="sun" value="Sunday"><label class="text cr_req_pos_box2">Sunday</label><br>
  </div>
<div class="body_class" id="search_payment">
  <label for="payment" class="text cr_req_pos_box2" style="top:-2px;">Preferred payment:</label><br>
  <input type="number" id="payment" name="payment" min="0" max="20" step="0.01" class="input_box cr_req_inpm">
  </div>
  <input type="submit" name="search" value="Search" class="button text" id="search_button">
</form>
</div>

<?php

if(isset($_POST['search'])) {
  
  if(isset($_POST['subjects'])){
  
    $search_subject = $_POST['subjects'];
    if(isset($_POST['place'])){
      $monInt = (!isset($_POST['mon'])) ? 0 : 1;
      $tueInt = (!isset($_POST['tue'])) ? 0 : 1;
      $wedInt = (!isset($_POST['wed'])) ? 0 : 1;
      $thuInt = (!isset($_POST['thur'])) ? 0 : 1;
      $friInt = (!isset($_POST['fri'])) ? 0 : 1;
      $satInt = (!isset($_POST['sat'])) ? 0 : 1;
      $sunInt = (!isset($_POST['sun'])) ? 0 : 1;

      if($monInt+$tueInt+$wedInt+$thuInt+$friInt+$satInt+$sunInt != 0){

        if($search_subject != 'empty'){
          $search_place = $_POST['place'];
          $search_place_int = 3;
          if($search_place = 'online'){
            $search_place_int = 0;
          } else if($search_place = 'physical'){
            $search_place_int = 1;
          } else if($search_place = 'both'){
            $search_place_int = 2;
          } else {
            die('Something went wrong, if this continuously reoccurs place report this issue (1)');
          }
          $search_userid = Login::isLoggedIn();
          $search_available = $monInt.$tueInt.$wedInt.$thuInt.$friInt.$satInt.$sunInt;
          $search_payment = ($_POST['payment']!='') ? $_POST['payment'] : 'tbd';
          $searchtype = 2;
          if($type == 'offers'){
            $searchtype = 1;
          } else if($type == 'requests'){
            $searchtype = 0;
          }
          $search_education = DB::query('SELECT education FROM user_characteristics WHERE user_id=:user_id', array(':user_id'=>$user_id))[0]['education'];
          $search_education_status = DB::query('SELECT education_status FROM user_characteristics WHERE user_id=:user_id', array(':user_id'=>$user_id))[0]['education_status'];
          $search_education_full = "{$search_education}_{$search_education_status}";
          
          // PYTHON INPUT DATA
          $search_data_user = "{$searchtype}-{$search_userid}-{$search_subject}-{$search_available}-{$search_payment}-{$search_place_int}-{$search_education_full}";
          //print($search_data_user);
          $search_data_input = '';
          if($type = 'offers'){
            $search_data_input = DB::query('SELECT id, user_id, subject, available, place, payment  FROM tutor_offers', array());
          } else if($type = 'requests'){
            $search_data_input = DB::query('SELECT id, user_id, subject, available, place, payment FROM tutor_requests', array());
          } else {
            die('Something went wrong, if this continuously reoccurs please report this issue (3)');
          }
          $search_data_input_json = json_encode($search_data_input);
          
          //print($search_data_input_json);
          // CALL PYTHON SCRIPT / PYTHON OUTPUT DATA
          $sorted_array = json_decode(shell_exec('python /var/www/TutorFinder/search-engine.py ' . $search_data_user . ' ' . $search_data_input_json), true);
        foreach($sorted_array as $p){
            print($p);
          }
          if(empty($sorted_array)){
            print("No search results!");
          }
          
          if($searchtype == 1){
            foreach($sorted_array as $e){
              $p = DB::query('SELECT * FROM tutor_offers WHERE id=:id', array(":id"=>$e));
              if($p[0]['visible']!='0'){
                $result_tutorer = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$p[0]['user_id']))[0]['username'];
                $result_tutor = "Offer posted by: <a href=\"profile.php?id={$p[0]['user_id']}\">$result_tutorer</a>"."</br />";
                $result_available = $p[0]['available'];
                $result_subject = "Subject: ".str_replace("_"," ", ucfirst($p[0]['subject']))."</br />";
                $result_availablestring = "Available on: ".(str_split($result_available)[0]=='1'?'monday, ':'').(str_split($result_available)[1]=='1'?'tuesday, ':'').(str_split($result_available)[2]=='1'?'wednesday, ':'').(str_split($result_available)[3]=='1'?'thursday, ':'').(str_split($result_available)[4]=='1'?'friday, ':'').(str_split($result_available)[5]=='1'?'saturday, ':'').(str_split($result_available)[6]=='1'?'sunday':'')."</br />";
                $result_place = $p[0]['place'];
                $result_placestring = "Physical or online: ".ucfirst($result_place)."</br />";
  
                if($result_place == "both"){
                  $result_placestring = "Physical or online: Fine with both physical and online"."</br />";
                }
                $result_payment = $p[0]['payment']=='-1'?'tbd':$p[0]['payment'];
                $result_paymentstring = "Preferred payment: ".($result_payment!="tbd"?number_format($result_payment, 2, '.', ','):"To be disclosed on")."</br />";
                $result_extra_comments = "Extra comments: ".$p[0]['extra_comments']."</br />";
                $result_created_on = "Created on: ".$p[0]['created_at']."</br />";
                $result_last_edited = "Last edited at: ".$p[0]['last_edited']."</br />";
                $result_visible = "Visibility: ".($p[0]['visible']=='0'?'Not listed publicly':'Listed publicly')."</br />";
                if(!$p[0]['visible']=='0'){
                  print($result_tutor.$result_subject.$result_created_on.$result_last_edited.$result_availablestring.$result_placestring.$result_paymentstring.$result_extra_comments);
                  print("</br />");
                  if($p[0]['user_id'] == Login::isLoggedIn()){
                    print("<form action=\"edit-offer.php?offer-id={$p[0]['id']}&ao=1");
                    print("\" method=\"post\">");
                    print("<input type=\"submit\" name=\"edit\" value=\"Edit\">"."</br />");
                    print("</form>");
                  
                  } else {
            print("<form action=\"messages.php?usr_id={$p[0]['user_id']}\" method=\"post\">");
            print("<input type=\"submit\" name=\"message\" value=\"Message User\">");
            print("</form>");
                  }
                }
                print("<hr />");
              }
            }
          } else {
            foreach($sorted_array as $e){
              $p = DB::query('SELECT * FROM tutor_requests WHERE id=:id', array(":id"=>$e));
              if($p[0]['visible']!='0'){
                  $result_requester1 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$p[0]['user_id']))[0]['username'];
                  $result_requester = "Request posted by: <a href=\"profile.php?id={$p[0]['user_id']}\">$result_requester1</a>"."</br />";
                $result_available = $p[0]['available'];
                $result_subject = "Subject: ".str_replace("_"," ", ucfirst($p[0]['subject']))."</br />";
                $result_availablestring = "Available on: ".(str_split($result_available)[0]=='1'?'monday, ':'').(str_split($result_available)[1]=='1'?'tuesday, ':'').(str_split($result_available)[2]=='1'?'wednesday, ':'').(str_split($result_available)[3]=='1'?'thursday, ':'').(str_split($result_available)[4]=='1'?'friday, ':'').(str_split($result_available)[5]=='1'?'saturday, ':'').(str_split($result_available)[6]=='1'?'sunday':'')."</br />";
                $result_place = $p[0]['place'];
                $result_placestring = "Physical or online: ".ucfirst($result_place)."</br />";
                if($result_place == "both"){
                  $result_placestring = "Physical or online: Fine with both physical and online"."</br />";
                }
                $result_payment = $p[0]['payment']=='-1'?'tbd':$p[0]['payment'];
                $result_paymentstring = "Preferred payment: ".($result_payment!="tbd"?number_format($result_payment, 2, '.', ','):"To be disclosed on")."</br />";
                $result_extra_comments = "Extra comments: ".$p[0]['extra_comments']."</br />";
                $result_created_on = "Created on: ".$p[0]['created_at']."</br />";
                $result_last_edited = "Last edited at: ".$p[0]['last_edited']."</br />";
                $result_visible = "Visibility: ".($p[0]['visible']=='0'?'Not listed publicly':'Listed publicly')."</br />";
                if(!$p[0]['visible']=='0'){
                  print($result_requester.$result_subject.$result_created_on.$result_last_edited.$result_availablestring.$result_placestring.$result_paymentstring.$result_extra_comments);
                  print("</br />");
                  if($p[0]['user_id'] == Login::isLoggedIn()){
                    print("<form action=\"edit-request.php?request-id={$p[0]['id']}&ar=1");
                    print("\" method=\"post\">");
                    print("<input type=\"submit\" name=\"edit\" value=\"Edit\">"."</br />");
                    print("</form>");
                  } else {
            print("<form action=\"messages.php?usr_id={$p[0]['user_id']}\" method=\"post\">");
            print("<input type=\"submit\" name=\"message\" value=\"Message User\">");
            print("</form>");
                  }
                  print("<hr />");
                }
              }
            }
          }

        } else {
          print 'Please specify a subject you want to be tutored in.';
        }
      } else {
        print 'Please specify on what day(s) you\'re available to be tutored.';
      }
    } else {
      print 'Please specify whether you prefer to be tutored online or in person.';
    }
  }
}

?>
