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
    
    public function api_ranking() {
        $error_code = null;
		$data = array();
        
		if($this->request->isPost()) {
            //Input:
            $userId = @$this->request->data['userId'];
            
            //Get all array score
            $arr_score = $this->Score->find('all', array('fields' => array('userId', 'score')));
            
            //Filter item in array to new array
            $arr_filter = array();
            
            foreach ($arr_score as $item) {
                if (isset($item["Score"])) {
                    array_push($arr_filter, $item["Score"]);
                }
            }
            
            //Sort array filter SORT_DESC
            $arr_sorted = array();
            foreach ($arr_filter as $key => $row) {
                $arr_sorted[$key] = $row['score'];
            }
            array_multisort($arr_sorted, SORT_DESC, $arr_filter);
            
            //Return ranking
            $ranking_user = 0;
            for ($i = 0; $i < count($arr_filter); $i++) {
                $item = $arr_filter[$i];
                if ($item['userId'] == $userId) {
                    $ranking_user = $i + 1;
                    break;
                }
            }
            $data = array(
                        'ranking' => $ranking_user
                    );
            $error_code = ErrorCode::REQUEST_SUCCESS;
        } else {
            $error_code = ErrorCode::NOT_IS_POST;
        }
        $this->renderWS($error_code, $data);
    }
    
    public function api_listFriend() {
        $error_code = null;
        $data = array();

        if ($this->request->isPost()) {
            //Input:
            $userId = @$this->request->data['userId'];
            
            //Load friend model -> select all friend id
            $this->loadModel('Friend');
            $friendIdListDB = $this->Friend->find('all', array('fields' => array('friendId'), 'conditions' => array('userId' => $userId)));
            $friendIdList = array($userId);
            foreach ($friendIdListDB as $item) {
                if (isset($item['Friend']['friendId'])) {
                    array_push($friendIdList, $item['Friend']['friendId']);
                }
            }
            var_dump($friendIdList);
            exit;
            
            
            
        } else {
            $error_code = ErrorCode::NOT_IS_POST;
        }
        $this->renderWS($error_code, $data);
    }
}