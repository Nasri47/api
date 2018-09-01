<?php
require_once('connection.php');

// ***********************geting all admins***********************
function getAdmins($response){
    $conn = getConn();
    $query = $conn->prepare("SELECT * FROM users WHERE block_state = 2"); 
    $query->execute();
    $result = $query->fetchall();
    if($query->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'id' => $row['user_id'],
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
// ***********************sending complaints***********************
function sendComplaint($field_id , $userphone , $complaint , $response){
    $conn = getConn();
    if (checkUser($userphone)) {
        $userQuery = $conn->query("SELECT user_id FROM users WHERE user_phone LIKE '$userphone'");
        $userId = $userQuery->fetchColumn();
        $query = $conn->prepare("INSERT INTO complaint (field_id , user_id , complaint) VALUES ('$field_id' , '$userId' , '$complaint')"); 
    $query->execute();
    $selsctQ = $conn->prepare("SELECT * FROM complaint WHERE user_id = $userId");
    $selsctQ->execute();
    $result = $selsctQ->fetchall();
    if($selsctQ->fetchColumn() >= 0){
        foreach($result as $row){
            $arr = array();
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
    }else{
        $arr = array();
            $dev = array(
                'response' => 2,
            );
            array_push($arr, $dev);
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }
    return json_encode($response, 128);
}
// ***********************reserve field***********************
function reserve($field_id , $user_phone , $username , $start , $end , $response){
    $conn = getConn();
    if (registerUser($username , $user_phone)) {
        $userQuery = $conn->query("SELECT user_id FROM users WHERE user_phone LIKE '$user_phone'");
        $userId = $userQuery->fetchColumn();
        $query = $conn->prepare("INSERT INTO reserve (field_id , user_id , reserve_beginning_time , reserve_end_time) VALUES ('$field_id' , '$userId' , '$start' , '$end')"); 
    $query->execute();
    $selsctQ = $conn->prepare("SELECT * FROM reserve WHERE field_id = $field_id");
    $selsctQ->execute();
    $result = $selsctQ->fetchall();
    if($selsctQ->fetchColumn() >= 0){
        $arr = array();
        foreach($result as $row){
            $dev = array(
                'user_id' => $row['user_id'],
                'field_id' => $row['field_id'],
                'complaint' => $row['reserve_end_time'],
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }else {
        $response['data'] = 'nothing to show right now !';
    }
    }else{
     $response['data'] = 'this user is blocked !';   
    }
    return json_encode($response, 128);
}
// ***********************registering users***********************
function registerUser($username , $userphone){
    $conn = getConn();
    $flag = true ;
    $query = $conn->prepare("SELECT * FROM users WHERE user_phone LIKE :phone");
    $query->bindParam(':phone', $userphone);
    $query->execute();
    $result = $query->fetchall();
    $arr = array();
    if($query->fetchColumn() >= 0){
        foreach($result as $row){
            if ($row['block_state'] == 1) {
                $response['data'] = "sorry ! this user is blocked frome using the app..";
                $flag = false ;
            }else{
                $dev = array(
                'user_id' => $row['user_id'],
                'user_name' => $row['user_name'],
                'user_phone' => $row['user_phone'],
            );
            array_push($arr, $dev);
            $response['error'] = FALSE;
            $response['data'] = $arr;
            }  
        }
    }else {
        $query = $conn->prepare("INSERT INTO users (user_name , user_phone , block_state) VALUES ('$username' , '$userphone' , 0)"); 
        $query->execute();
        $sQuery = $conn->prepare("SELECT * FROM users WHERE user_phone LIKE '$userphone'");
    $sQuery->execute();
    $result = $sQuery->fetchall();
    foreach($result as $row){
        $dev = array(
                'user_id' => $row['user_id'],
                'user_name' => $row['user_name'],
                'user_phone' => $row['user_phone'],
            );
            array_push($arr, $dev);
    }
    }
    return $flag;
}
// ***********************geting owner using id***********************
function getOwnerById($userid ,$response){
    $conn = getConn();
    $ownerQ = $conn->prepare("SELECT * FROM users WHERE user_id = '$userid'"); 
    $ownerQ->execute();
    $ownerR = $ownerQ->fetchall();
    $arr = array();
    if($ownerQ->fetchColumn() >= 0){
    foreach($ownerR as $ownerRow){
            $dev = array(
                'id' => $ownerRow['user_id'],
                'user_name' => $ownerRow['user_name'],
                'owner_phone' => $ownerRow['user_phone'],
            );
            array_push($arr, $dev);
        }
        $response['error'] = FALSE;
        $response['data'] = $arr;
    }
    else {
        $response['data'] = 'nothing to show right now !';
    }
    return json_encode($response, 128);
}

function checkUser($userphone){
    $conn = getConn();
    $flag = false ;
    $query = $conn->prepare("SELECT * FROM users WHERE user_phone LIKE :phone");
    $query->bindParam(':phone', $userphone);
    $query->execute();
    $result = $query->fetchall();
    $arr = array();
    if($query->fetchColumn() >= 0){
        foreach($result as $row){
            if ($row['block_state'] == 1) {
                $response['data'] = "sorry ! this user is blocked frome using the app..";
            }else{
                $flag = true ;
                $dev = array(
                'user_id' => $row['user_id'],
                'user_name' => $row['user_name'],
                'user_phone' => $row['user_phone'],
            );
            array_push($arr, $dev);
            $response['error'] = FALSE;
            $response['data'] = $arr;
            }  
        }
    }
    return $flag;
}
?>