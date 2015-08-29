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
    
    public function api_login() {
		$error_code = null;
		$data = array();
		if($this->request->isPost()) {
            $error_code = ErrorCode::REQUEST_SUCCESS;
            $this->renderWS($error_code, $data);
        } else {
            $error_code = ErrorCode::ACCESS_DENIED;
            $this->renderWS($error_code, $data);
        }
	}
}