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
	public $components = array('RequestHandler');
    
    public function beforeFilter(){
		parent::beforeFilter();
		if ($this->request->accepts('application/json')) {
			$this->RequestHandler->renderAs($this, 'json');
		}
	}
    
    /*
        Input: fbId, udid, fbToken
    */
    public function api_login() {
		$error_code = null;
		$data = array();
		if($this->request->isPost()) {    
            $fbId = @$this->request->data['fbId'];
		    $udid = @$this->request->data['udid'] ;
            $fbToken = @$this->request->data['fbToken'];
            $userId = @$this->request->data['userId'];
            
            $deviceModel = @$this->request->data['deviceModel'];
            $osVersion = @$this->request->data['osVersion'];
            
            if(!empty($fbId) && !empty($udid) && !empty($fbToken)) {
                
                //Check fbId is exist in DB or not?
                $user_fbId = $this->User->find('first', array('fields' => array('id'), 'conditions' => array('fbId' => $fbId)));
                
                if (empty($userId) && empty($user_fbId)) {
                    //Register a new account
                    //-> insert a new record
                    $this->User->create();
                    $data_for_insert = array (
                            'User' => array(
                                'fbId' => $fbId,
                                'fbToken' => $fbToken,
                                'loginDate' => date("Y-m-d H:i:s"),
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
                    //-> Update fbToken + udid again in row with userId
                    if ($this->User->updateAll(array("fbToken" => $fbToken, "udidDevice" => $udid), array("id" => $userId))){
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
}