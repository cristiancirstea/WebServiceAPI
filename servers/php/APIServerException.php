<?php

class APIServerException extends Exception
{
	const HTTP_400 = 400;
	
	const HTTP_404 = 404;

	const METHOD_NOT_PUBLIC = 1001;

	const METHOD_NOT_FOUND = 1004;

	const METHOD_NOT_IMPLEMENTED = 1005;

	const PARAMS_LESS = 1101;
	
	const PARAMS_MORE = 1102;

	const PARAM_REQUEST_NOT_GIVEN = 2001;

	const USER_NOT_FOUND = 4002;
}