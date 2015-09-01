<?php
App::uses('AppController', 'Controller');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ScoresController extends AppController {
    
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
        Input: userId, score
    */
    public function api_save() {
		$error_code = null;
		$data = array();
		if($this->request->isPost()) {
            
            //Input:
            $userId = @$this->request->data['userId'];
		    $score = @$this->request->data['score'];
            
            if(!empty($userId) && !empty($score)) {
                
                //Check record with userId
                $user_score = $this->Score->find('first', array('fields' => array('id'), 'conditions' => array('userId' => $userId)));
                if (empty($user_score)) {
                    //Create new score record
                    $this->Score->create();
                    $data_for_insert = array (
                            'Score' => array(
                                'userId' => $userId,
                                'score' => $score,
                            )
                        );
                    $new_record = $this->Score->save($data_for_insert);
                    if ($new_record) {
                        $error_code = ErrorCode::REQUEST_SUCCESS;
                    } else {
                        $error_code = ErrorCode::CAN_NOT_UPDATE_FOR_SCORE;
                    }
                } else {
                    //Update new score to DB
                    if ($this->Score->updateAll(array(
                        "score" => "'$score'"), array("userId" => $userId))) {
                        $error_code = ErrorCode::REQUEST_SUCCESS;
                    } else {
                        $error_code = ErrorCode::CAN_NOT_UPDATE_FOR_SCORE;
                    }
                }
	       } else {
                $error_code = ErrorCode::INPUT_SCORE_INVALID;
           }
        } else {
           $error_code = ErrorCode::NOT_IS_POST; 
        }
        $this->renderWS($error_code, $data);
    }
}