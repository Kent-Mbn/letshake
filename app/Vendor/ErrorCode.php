<?php

class ErrorCode {
    
	/* Common controller */
	const REQUEST_SUCCESS = 'C000';
    const ACCESS_DENIED = 'C001';
    const NOT_API = 'C002';
    const NOT_IS_POST = 'C003';
	
	/* Authorize validate */
    const AUTH_INVALID_TOKEN = "A001";
    const AUTH_TOKEN_EXPIRED = "A002";
    const AUTH_USER_INVALID = "A003";
	
	/* User controller */	
    const WRONG_LOGIN_INFO = 'U000';
    const USER_NOT_EXISTS = 'U001';
    const INPUT_LOGIN_INVALID = 'U002';
    const CAN_NOT_INSERT_FOR_REGISTER = 'U003';
    const CAN_NOT_UPDATE_FOR_LOGIN = 'U004';

}

?>