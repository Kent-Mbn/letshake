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
            $fbId = @$this->request->post_body['fbId'];
		    $udid = @$this->request->post_body['udid'] ;
            $fbToken = @$this->request->post_body['fbToken'];
            $userId = @$this->request->post_body['userId'];
            
            $deviceModel = @$this->request->post_body['deviceModel'];
            $osVersion = @$this->request->post_body['osVersion'];
            
            if(!empty($fbId) && !empty($udid) && !empty($fbToken)) {
                
                //Check fbId is exist in DB or not?
                $user_fbId = $this->User->find('first', array('fields' => array('id'), 'conditions' => array('fbId' => $fbId, 'fbToken' => $fbToken, 'udidDevice' => $udidDevice)));
                
                if (empty($userId) && empty($user_fbId)) {
                    //Register a new account
                    //-> insert a new record
                    $this->User->create();
                    $data_for_insert = array (
                            'User' => array(
                                'fbId' => $fbId,
                                'fbToken' => $fbToken,
                                'loginDate' => new DateTime("now"),
                                'deviceModel' => $deviceModel,
                                'osVersion' => $osVersion,
                                'udidDevice' => $udid,
                            )
                        );
                    $id_new_record = $this->User->save($data_for_insert);
                    if ($id_new_record) {
                        $data = array(
                            'userId' => $id_new_record,
                            'fbId' => $fbId,
                            'fbToken' => $fbToken,
                            'udidDevice' => $udid
                        );
                        $error_code = ErrorCode::REQUEST_SUCCESS; 
                    } else {
                        $error_code = ErrorCode::CAN_NOT_INSERT_FOR_REGISTER;
                    }
                } else {
                    //Login
                    
                }
            } else {
                $error_code = ErrorCode::INPUT_LOGIN_INVALID;
            }
            $error_code = ErrorCode::REQUEST_SUCCESS;
            
        } else {
            $error_code = ErrorCode::NOT_IS_POST;
        }
        
        $this->renderWS($error_code, $data);
	}
}