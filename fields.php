<?php
require_once('connection.php');
// ***********************get all fields***********************
function getAllfields($response){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM field"); 
    $query->execute();
    $result = $query->fetchall();
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'id' => $row['field_id'],
                'field_name' => $row['field_name'],
                'field_city' => $row['field_city'],
                'field_size' => $row['field_size'],
                'field_lat' => $row['field_lat'],
                'field_lng' => $row['filed_lng'],
                'open_time' => $row['open_time'],
                'close_time' => $row['close_time'],
                'field_hour_price' => $row['field_hour_price'],
                'block_state' => $row['block_state'],
                'suspend_resons' => $row['suspend_resons'],
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}

// ***********************getting field using city name***********************
function getFieldByCity($fieldcity ,$response){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM field WHERE field_city = '$fieldcity'"); 
    $query->execute();
    $result = $query->fetchall();
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'id' => $row['field_id'],
                'field_name' => $row['field_name'],
                'field_city' => $row['field_city'],
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}
// ***********************geting field using id***********************
function getFieldById($fieldid ,$response){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM field WHERE field_id = '$fieldid'"); 
    $query->execute();
    $result = $query->fetchall();
    $reserves = getReserves($fieldid) ;
    $ownershipQ = $conn->prepare("SELECT * FROM ownership WHERE field_id = '$fieldid'"); 
    $ownershipQ->execute();
    $ownershipR = $ownershipQ->fetchall();
    foreach($ownershipR as $ownershipRow){
    	$userId = $ownershipRow['user_id'] ;
    $ownerQ = $conn->prepare("SELECT * FROM users WHERE user_id = '$userId'"); 
    $ownerQ->execute();
    $ownerR = $ownerQ->fetchall();
    $userName = "";
    $userPhone = "";
    foreach($ownerR as $ownerRow){
    		$userName = $ownerRow['user_name'];
    		$userPhone = $ownerRow['user_phone'];
    }
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'id' => $row['field_id'],
                'field_name' => $row['field_name'],
                'field_city' => $row['field_city'],
                'field_size' => $row['field_size'],
                'field_lat' => $row['field_lat'],
                'field_lng' => $row['filed_lng'],
                'open_time' => $row['open_time'],
                'close_time' => $row['close_time'],
                'field_hour_price' => $row['field_hour_price'],
                'block_state' => $row['block_state'],
                'suspend_resons' => $row['suspend_resons'],
                'owner_name' => $userName,
                'owner_phone' => $userPhone,
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
        $response['reserves'] = $reserves;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
}
    return json_encode($response, 128);
}
// ***********************geting field timetable***********************
function getTimetable($fieldid ,$response){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM field WHERE field_id = '$fieldid'"); 
    $query->execute();
    $result = $query->fetchall();

    $ownershipQ = $conn->prepare("SELECT * FROM ownership WHERE field_id = '$fieldid'"); 
    $ownershipQ->execute();
    $ownershipR = $ownershipQ->fetchall();
    foreach($ownershipR as $ownershipRow){
    	$userId = $ownershipRow['user_id'] ;
    $ownerQ = $conn->prepare("SELECT * FROM users WHERE user_id = '$userId'"); 
    $ownerQ->execute();
    $ownerR = $ownerQ->fetchall();
    $userName = "";
    $userPhone = "";
    foreach($ownerR as $ownerRow){
    		$userName = $ownerRow['user_name'];
    		$userPhone = $ownerRow['user_phone'];
    }
    if($query->fetchColumn() >= 0){
    	$reservesQ = $conn->prepare("SELECT * FROM reserve WHERE field_id = '$fieldid'");
        $reservesQ->execute();
    	$reserveR = $reservesQ->fetchall();
    	if($reservesQ->fetchColumn() >= 0){
    		$reserveArray = array();
    		foreach($reserveR as $rRow){
            $dev = array(
                'id' => $rRow['reserve_id'],
                'open_time' => $rRow['reserve_beginning_time'],
                'close_time' => $rRow['reserve_end_time'],
            );
            array_push($reserveArray, $dev);
        }
    	}
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'id' => $row['field_id'],
                'open_time' => $row['open_time'],
                'close_time' => $row['close_time'],
                'reserves' => $reserveArray,
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
}
    return json_encode($response, 128);
}
// ***********************getting field complaints***********************
function getComplaints($fieldid ,$response){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM complaint WHERE field_id = '$fieldid' AND aprove = 1"); 
    $query->execute();
    $result = $query->fetchall();
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
        	$userid = $row['user_id'];
        	$userQ = $conn->prepare("SELECT * FROM users WHERE user_id = '$userid'");
    		$userQ->execute();
    		$userR = $userQ->fetchall();
    		foreach($userR as $userRow){
            $dev = array(
                'id' => $row['complaint_id'],
                'complaint' => $row['complaint'],
                'user_name' => $userRow['user_name'],
                'user_phone' => $userRow['user_phone'],
            );
            array_push($arr, $dev);
        }
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}
// ***********************getting field researvations***********************
function getResearvations($fieldid ,$response){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM reserve WHERE field_id = $fieldid AND is_confirmd = 0"); 
    $query->execute();
    $result = $query->fetchall();
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
        	$userid = $row['user_id'];
        	$userQ = $conn->prepare("SELECT * FROM users WHERE user_id = $userid"); 
    		$userQ->execute();
    		$userR = $userQ->fetchall();
    		foreach($userR as $userRow){
            $dev = array(
                'id' => $row['reserve_id'],
                'reserve_frome' => $row['reserve_beginning_time'],
                'reserve_to' => $row['reserve_end_time'],
                'user_name' => $userRow['user_name'],
                'user_phone' => $userRow['user_phone'],
            );
            array_push($arr, $dev);
        }
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}
// ***********************updating field informations***********************
function updateFieldInfo($id , $name , $size , $city , $open , $close , $price ,$response){
    $conn = getConn();
    $query = $conn->prepare("UPDATE field SET field_name = :name , field_size = :size , field_city = :city , open_time = :open , close_time = :close , field_hour_price = :price WHERE field_id = :id"); 
    $query->bindParam(':name', $name);
    $query->bindParam(':size', $size);
    $query->bindParam(':city', $city);
    $query->bindParam(':open', $open);
    $query->bindParam(':close', $close);
    $query->bindParam(':price', $price);
    $query->bindParam(':id', $id);

    $query->execute();
    $query = $conn->prepare("SELECT * FROM field WHERE field_id = $id"); 
    $query->execute();
    $result = $query->fetchall();
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'id' => $row['field_id'],
                'field_name' => $row['field_name'],
                'field_city' => $row['field_city'],
                'field_hour_price' => $row['field_hour_price'],
                'open_time' => $row['open_time'],
                'close_time' => $row['close_time'],
                'field_size' => $row['field_size'],
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}
// ***********************updating owner informations***********************
function updateOwnerInfo($id , $name , $phone ,$response){
    $conn = getConn();
    $query = $conn->prepare("UPDATE users SET user_name = $name , user_phone = $phone  WHERE user_id = $id"); 
    $query->execute();
    $query = $conn->prepare("SELECT * FROM users WHERE field_id = $id"); 
    $query->execute();
    $result = $query->fetchall();
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'id' => $row['field_id'],
                'user_name' => $row['user_name'],
                'user_phone' => $row['user_phone'],
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}
// ***********************creating new field***********************
function registerField($ownername , $fieldname , $fieldcity , $ownerphone , $pass ,$response){
    $conn = getConn();
    $arr = array();
    $query = $conn->prepare("SELECT * FROM users WHERE user_phone LIKE '$ownerphone' AND block_state = 3"); 
    $query->execute();
    if($query->fetchColumn() > 0){
    		$dev = array(
                'response' => 0,
            );
            array_push($arr, $dev);
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }elseif ($query->fetchColumn() == 0) {
     $result = $query->fetchall();
    $query = $conn->prepare("INSERT INTO field (field_name , field_city) VALUES ('$fieldname' , '$fieldcity')"); 
    $query->execute();
    $stringPhone = strval($ownerphone);
    $salt = encrypt($stringPhone , $ownername);
    $salt = encrypt($salt , $fieldname);
    $saltTow = encrypt($salt, $stringPhone);
    //Hashing Field password
    $options = [
      'salt' => $saltTow, //write your own code to generate a suitable salt
      'cost' => 12 // the default cost is 10
      ] ;
      $hash = password_hash($pass , PASSWORD_DEFAULT , $options);
    $query = $conn->prepare("INSERT INTO users (user_name , user_phone , user_pass , block_state) VALUES ('$ownername' , '$ownerphone' , '$hash' , 3)"); 
    $query->execute();
    $result = $query->fetchall();
    $userQuery = $conn->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1");
    $userId = $userQuery->fetchColumn();
    $fieldQuery = $conn->query("SELECT field_id FROM field ORDER BY field_id DESC LIMIT 1");
    $fieldId = $fieldQuery->fetchColumn();
    $ownerQuery = $conn->prepare("INSERT INTO ownership (field_id , user_id) VALUES ($fieldId , $userId)"); 
      $ownerQuery->execute();
            $dev = array(
                'id' => $fieldId,
                'response' => 1,
            );
            array_push($arr, $dev);
        $response['error'] = FALSE;
        $response['data'] = $arr;
   
    }
    return json_encode($response, 128);
}
// ***********************field login***********************
function fieldLogin($ownerphone , $pass , $response){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM users WHERE user_phone LIKE '$ownerphone' AND block_state = 3");
    $query->execute();
    $result = $query->fetchall();
    $arr = array();
    if($query->fetchColumn() >= 0){
        foreach($result as $row){
        	if (password_verify($pass , $row['user_pass'])) {
                    $userId = $row['user_id'] ;
                    $ownershipQuery = $conn->query("SELECT field_id FROM ownership WHERE user_id = '$userId'");
                $fieldId = $ownershipQuery->fetchColumn();
                $fieldQuery = $conn->prepare("SELECT * FROM field WHERE field_id = '$fieldId'"); 
                $fieldQuery->execute();
                $fieldResult = $fieldQuery->fetchall();
                foreach($fieldResult as $fieldRow){
                    $dev = array(
                'response' => 2,
                'field_id' => $fieldId,
                'user_id' => $row['user_id'],
                'block_state' => $fieldRow['block_state'],
                'suspend_resons' => $fieldRow['suspend_resons'],
                
            );
            array_push($arr, $dev);
            $response['error'] = FALSE;
            $response['data'] = $arr;
        }
            }else{
                $dev = array(
                'response' => 2,
                'field_id' => -1,
                'user_id' => -1,
                'block_state' => 4,
                'suspend_resons' => "",
                
            );
            array_push($arr, $dev);
            $response['error'] = FALSE;
            $response['data'] = $arr;
            }
            }
        }
    return json_encode($response, 128);
}
// ***********************update password***********************
function changePassword($fieldid , $oldpass , $newpass , $response){
    $conn = getConn();
    $ownershipQuery = $conn->query("SELECT user_id FROM ownership WHERE field_id = '$fieldid'");
    $userId = $ownershipQuery->fetchColumn();
    $userQuery = $conn->prepare("SELECT * FROM users WHERE user_id LIKE '$userId'");
    $userQuery->execute();
    $result = $userQuery->fetchall();
    $userPass = "";
    $userName = "";
    $userPhone = "";
    $arr = array();
    foreach($result as $row){
    	$userPhone = $row['user_phone'];
    	$userName = $row['user_name'];
    	$userPass = $row['user_pass'] ;
    }
    if (password_verify($oldpass , $userPass)) {
    	$fieldQ = $conn->prepare("SELECT * FROM field WHERE field_id = '$fieldid'");
    $fieldQ->execute();
    $result = $fieldQ->fetchall();
    foreach($result as $row){
    	$stringPhone = strval($userPhone);
    	$salt = encrypt($stringPhone , $userName);
    	$salt = encrypt($salt , $row['field_name']);
    	$saltTow = encrypt($salt, $stringPhone);
    	//Hashing Field password
    	$options = [
      	'salt' => $saltTow, //write your own code to generate a suitable salt
      	'cost' => 12 // the default cost is 10
      	] ;
      	$hash = password_hash($newpass , PASSWORD_DEFAULT , $options);
    	$query = $conn->prepare("UPDATE users SET user_pass = '$hash' WHERE user_id = '$userId'"); 
    	$query->execute();
        $dev = array(
                'id' => $row['field_id'],
                'respons' => 1,
            );
            array_push($arr, $dev);
            $response['error'] = FALSE;
    	$response['data'] = $arr;
    }
    }else{
        $dev = array(
                'id' => -1,
                'respons' => 2,
            );
            array_push($arr, $dev);
            $response['error'] = FALSE;
    	$response['data'] = $arr;
    }
    return json_encode($response, 128);
}
// ***********************Accept researvation***********************
function acceptReserve($reserveid , $response){
    $conn = getConn();
    $query = $conn->prepare("UPDATE reserve SET is_confirmd = 1 WHERE reserve_id = '$reserveid'");
    $query->execute();
    	$response['data'] = "researved succefully..";
    return json_encode($response, 128);
}
// ***********************Cancel researvation***********************
function cancelReserve($reserveid , $response){
    $conn = getConn();
    $query = $conn->prepare("UPDATE reserve SET is_confirmd = 2 WHERE reserve_id = '$reserveid'");
    $query->execute();
    	$response['data'] = "researvation canceled..";
    return json_encode($response, 128);
}
// ***********************block user***********************
function blockUser($field_id , $userphone , $resons , $response){
    $conn = getConn();
    $userQuery = $conn->prepare("SELECT * FROM users WHERE user_phone LIKE '$userphone'");
    $userQuery->execute();
    $result = $userQuery->fetchall();
    foreach ($result as $row) {
    	$userId = $row['user_id'] ;
    	$query = $conn->prepare("INSERT INTO blockrequests (field_id , user_id , block_resons) VALUES ('$field_id' , '$userId' , '$resons')"); 
    $query->execute();
    $response['data'] = "request is sent..";
    }
    return json_encode($response, 128);
}
// ***********************getting field researvations***********************
function getReserves($fieldid){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM reserve WHERE field_id = $fieldid AND is_confirmd = 1"); 
    $query->execute();
    $result = $query->fetchall();
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $userid = $row['user_id'];
            $userQ = $conn->prepare("SELECT * FROM users WHERE user_id = $userid"); 
            $userQ->execute();
            $userR = $userQ->fetchall();
            foreach($userR as $userRow){
            $dev = array(
                'id' => $row['reserve_id'],
                'reserve_frome' => $row['reserve_beginning_time'],
                'reserve_to' => $row['reserve_end_time'],
                'user_name' => $userRow['user_name'],
                'user_phone' => $userRow['user_phone'],
            );
            array_push($arr, $dev);
        }
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return $arr;
}

// ***********************getting field researvations***********************
function closeField($fieldid , $suspendres ,$response){
    $conn = getConn();
    $query = $conn->prepare("UPDATE field SET block_state = 2 , suspend_resons = :resons  WHERE field_id = :id"); 
    $query->bindParam(':resons', $suspendres);
    $query->bindParam(':id', $fieldid); 
    $query->execute();
    $userQ = $conn->prepare("SELECT * FROM field WHERE field_id = :id"); 
    $userQ->bindParam(':id', $fieldid); 
    $userQ->execute();
    $result = $userQ->fetchall();
    if($userQ->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'id' => $row['field_id'],
                'block_state' => $row['block_state'],
                'suspend_resons' => $row['suspend_resons'],
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}

// ***********************getting field researvations***********************
function unblockField($fieldid ,$response){
    $conn = getConn();
    $reson = "" ;
    $query = $conn->prepare("UPDATE field SET block_state = 1 , suspend_resons = :resons  WHERE field_id = :id"); 
    $query->bindParam(':resons', $reson);
    $query->bindParam(':id', $fieldid); 
    $query->execute();
    $userQ = $conn->prepare("SELECT * FROM field WHERE field_id = :id"); 
    $userQ->bindParam(':id', $fieldid); 
    $userQ->execute();
    $result = $userQ->fetchall();
    if($userQ->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'response' => 1,
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}

// ***********************encrypt method***********************
function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}
?>