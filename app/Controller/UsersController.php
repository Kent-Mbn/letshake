<?php
App::uses('AppController', 'Controller');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UsersController extends AppController {
    
    /**
	 * Loaded components
	 */	
	public $components = array('RequestHandler', 'Common');
    
    public function beforeFilter(){
		parent::beforeFilter();
		if ($this->request->accepts('application/json')) {
			$this->RequestHandler->renderAs($this, 'json');
		}
	}
    
    /*
        Input: fbId, udid, deviceModel, osVersion
    */
    public function api_login() {
		$error_code = null;
		$data = array();
		if($this->request->isPost()) {
            
            //Input:
            $fbId = @$this->request->data['fbId'];
		    $udid = @$this->request->data['udid'];
            $deviceModel = @$this->request->data['deviceModel'];
            $osVersion = @$this->request->data['osVersion'];
            
            if(!empty($fbId) && !empty($udid)) {
                
                //Create new token string
                $new_token = $this->Common->generateAuthToken($fbId);
                
                //Create current date string
                $current_date = date("Y-m-d H:i:s");
                
                //Check fbId is exist in DB or not?
                $user_fbId = $this->User->find('first', array('fields' => array('id'), 'conditions' => array('fbId' => $fbId)));
                
                if (empty($user_fbId)) {
                    //Register a new account
                    //-> insert a new record
                    $this->User->create();
                    $data_for_insert = array (
                            'User' => array(
                                'fbId' => $fbId,
                                'token' => $new_token,
                                'loginDate' => $current_date,
                                'logoutDate' => null,
                                'deviceModel' => $deviceModel,
                                'osVersion' => $osVersion,
                                'udidDevice' => $udid,
                            )
                        );
                    $new_record = $this->User->save($data_for_insert);
                    if ($new_record) {
                        $data = $new_record;
                        $error_code = ErrorCode::REQUEST_SUCCESS; 
                    } else {
                        $error_code = ErrorCode::CAN_NOT_INSERT_FOR_REGISTER;
                    }
                } else {
                    //Login
                    //-> Update row with fbId
                    if ($this->User->updateAll(array(
                        "token" => "'$new_token'", 
                        "udidDevice" => "'$udid'", 
                        "loginDate" => "'$current_date'", 
                        "logoutDate" => null, 
                        "deviceModel" => "'$deviceModel'",
                        "osVersion" => "'$osVersion'"), array("fbId" => $fbId))) {
                        
                        $data = $this->User->find('first', array('conditions' => array('fbId' => $fbId)));
                        $error_code = ErrorCode::REQUEST_SUCCESS;
                        
                    } else {
                        $error_code = ErrorCode::CAN_NOT_UPDATE_FOR_LOGIN;
                    }
                }
            } else {
                $error_code = ErrorCode::INPUT_LOGIN_INVALID;
            }
        } else {
            $error_code = ErrorCode::NOT_IS_POST;
        }
        
        $this->renderWS($error_code, $data);
	}
    
    /*
        Input: $fbId, $udid
    */
    public function api_logout() {
        $data = array();
        $error_code = null;
        
        $headers = getallheaders();
        $fbId = @$headers['fbId'];
        $udid = @$headers['udidDevice'];
            
        if(!empty($fbId) && !empty($udid)) {

            //Create current date string
            $current_date = date("Y-m-d H:i:s");

            if ($this->User->updateAll(array(
                    "token" => null,   
                    "logoutDate" => "'$current_date'"), array("fbId" => $fbId, "udidDevice" => $udid))) {
                    $error_code = ErrorCode::REQUEST_SUCCESS;
                } else {
                    $error_code = ErrorCode::CAN_NOT_UPDATE_FOR_LOGOUT;
                }
        } else {
            $error_code = ErrorCode::INPUT_LOGOUT_INVALID;
        }
        
        $this->renderWS($error_code, $data);
    }
}