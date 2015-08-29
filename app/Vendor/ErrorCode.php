<?php

class ErrorCode {
    
	/* Common controller */
	const REQUEST_SUCCESS = 'C000';
    const ACCESS_DENIED = 'C001';
    const NOT_API = 'C002';
	
	/* Authorize validate */
    const AUTH_INVALID_TOKEN = "A001";
    const AUTH_TOKEN_EXPIRED = "A002";
	
	/* User controller */	
    const WRONG_LOGIN_INFO = 'U000';
    const USER_NOT_EXISTS = 'U001';

}

?>