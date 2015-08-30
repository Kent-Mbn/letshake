<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

App::uses('Controller', 'Controller');
App::uses("ErrorCode", "Vendor");
//App::uses("ConnectionManager", "Model");

class AppController extends Controller {
    
    function beforeFilter() {
        $errorCode = ErrorCode::NOT_API;
        if ($this->request->prefix == 'api') {
            
            //checking App Id and App Password
            $errorCode = $this->authorizeApplication();
			if($errorCode!==true){
				$this->renderWS($errorCode);
				$this->renderAsJSON();
                return;
			}            
            
            //continue to checking auth user
            if(!in_array($this->action, array('api_login'))){
                $errorCode = $this->authorizeUser();
                if($errorCode!==true) {
				    $this->renderWS($errorCode);
				    $this->renderAsJSON();
                    return;
			     }
            }
        } else {
            $this->renderWS($errorCode);
            $this->renderAsJSON();
        }
    }
    
    protected function authorizeApplication() {
        $headers = getallheaders();
        $token = @$headers['ApplicationId'];
        $token_secret = @$headers['ApplicationSecret'];
        if (!empty($token) && !empty($token_secret)){
			$this->loadModel('Apikey');
            $user = $this->Apikey->find('first', array('fields' => array('appId'), 'conditions' => array('appId' => $token, 'appSecret' => $token_secret)));
            if(!empty($user)) {
                return true;
            }
        }
        return ErrorCode::ACCESS_DENIED;
    }
    
    protected function authorizeUser() {
        //Checking fbId + fbToken + udid in header fields is exist in DB or not?
        $headers = getallheaders();
        $fbId = @$headers['facebookId'];
        $fbToken = @$headers['facebookToken'];
        $udidDevice = @$headers['udidDevice'];
        
        if (!empty($fbId) && !empty($fbToken) && !empty($udidDevice)) {
            $this->loadModel('User');
            $user = $this->User->find('first', array('fields' => array('id'), 'conditions' => array('fbId' => $fbId, 'fbToken' => $fbToken, 'udidDevice' => $udidDevice)));
            if (!empty($user)) {
                return true;
            }
        }
        return ErrorCode::AUTH_USER_INVALID;
    }
    
    function renderAsJSON(){
		$output = array();
		if(!empty($this->viewVars['_serialize'])){
			foreach($this->viewVars['_serialize'] as $key) $output[$key] = @$this->viewVars[$key];
		}else{
			$output = $this->viewVars;
		}
		$this->layout = null;
		ob_clean();
		header('Content-Type: application/json');
		echo json_encode($output); die();
	}
    
    public function renderWS($errorCode=null, $data=array()){
		$data['server_time_format'] = date('Y-m-d H:i:s');
		$this->set(compact(array('errorCode', 'data')));
		$this->set('_serialize', array('errorCode', 'data'));
	}
}
