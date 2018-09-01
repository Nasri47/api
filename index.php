<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
header("Pragma: no-cache");
header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: POST, GET");
header('Content-type:application/json;charset=utf-8');
date_default_timezone_set('Africa/Khartoum');

$response['error'] = TRUE ;
if($_SERVER['REQUEST_METHOD'] == "GET"){
	switch($_GET['url']){
		case "fieldslist":{
            require_once('fields.php');
            echo getAllfields(
                $response
            );
        }
        break;
        case "getfieldbycity":{
            require_once('fields.php');
            echo getFieldByCity(
            	$_GET['city_name'],
                $response
            );
        }
        break;
        case "getfieldbyid":{
            require_once('fields.php');
            echo getFieldById(
            	$_GET['field_id'],
                $response
            );
        }
        break;
        case "getownerbyid":{
            require_once('users.php');
            echo getOwnerById(
                $_GET['userid'],
                $response
            );
        }
        break;
        case "gettimetable":{
            require_once('fields.php');
            echo getTimetable(
            	$_GET['field_id'],
                $response
            );
        }
        break;
        case "getcomplaints":{
            require_once('fields.php');
            echo getComplaints(
            	$_GET['field_id'],
                $response
            );
        }
        break;
        case "getresearvations":{
            require_once('fields.php');
            echo getResearvations(
            	$_GET['field_id'],
                $response
            );
        }
        break;
        case "updatefieldinfo":{
            require_once('fields.php');
            echo updateFieldInfo(
            	$_GET['field_id'],
            	$_GET['field_name'],
            	$_GET['field_size'],
            	$_GET['field_city'],
            	$_GET['open_time'],
            	$_GET['close_time'],
            	$_GET['hour_price'],
                $response
            );
        }
        break;
        case "updateownerinfo":{
            require_once('fields.php');
            echo updateOwnerInfo(
            	$_GET['owner_id'],
            	$_GET['owner_name'],
            	$_GET['owner_phone'],
                $response
            );
        }
        break;
        case "getadmins":{
            require_once('users.php');
            echo getAdmins(
                $response
            );
        }
        break;
        case "sendcomplaint":{
            require_once('users.php');
            echo sendComplaint(
            	$_GET['field_id'],
            	$_GET['userphone'],
            	$_GET['complaint'],
                $response
            );
        }
        break;
        case "acceptreserve":{
            require_once('fields.php');
            echo acceptReserve(
            	$_GET['reserve_id'],
                $response
            );
        }
        break;
        case "cancelreserve":{
            require_once('fields.php');
            echo cancelReserve(
            	$_GET['reserve_id'],
                $response
            );
        }
        break;
        case "blockuser":{
            require_once('fields.php');
            echo blockUser(
            	$_GET['field_id'],
            	$_GET['user_phone'],
            	$_GET['resons'],
                $response
            );
        }
        break;
        case "reserve":{
            require_once('users.php');
            echo reserve(
            	$_GET['field_id'],
            	$_GET['user_phone'],
            	$_GET['user_name'],
            	$_GET['reservestart'],
            	$_GET['reserveend'],
                $response
            );
        }
        break;
        case "closefield":{
            require_once('fields.php');
            echo closeField(
                $_GET['field_id'],
                $_GET['suspend_resons'],
                $response
            );
        }
        break;
        case "unblockfield":{
            require_once('fields.php');
            echo unblockField(
                $_GET['field_id'],
                $response
            );
        }
        break;
	}
}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
	switch($_GET['url']){

        case "fieldlogin":{
            require_once('fields.php');
            echo fieldLogin(
                $_POST['owner_phone'],
                $_POST['password'],
                $response
            );
        }
        break;
        case "changepassword":{
            require_once('fields.php');
            echo changePassword(
                $_POST['field_id'],
                $_POST['old_pass'],
                $_POST['new_pass'],
                $response
            );
        }
        break;
        case "registerfield":{
            require_once('fields.php');
            echo registerField(
                $_POST['owner_name'],
                $_POST['field_name'],
                $_POST['field_city'],
                $_POST['owner_phone'],
                $_POST['password'],
                $response
            );
        }
        break;

	}
}
?>