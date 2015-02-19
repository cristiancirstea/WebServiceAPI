<?php

class APIServerException extends Exception
{
	const HTTP_400 = 400;
	
	const HTTP_404 = 404;

	const METHOD_NOT_PUBLIC = 1001;

	const METHOD_NOT_FOUND = 1004;

	const PARAMS_LESS = 1101;
	
	const PARAMS_MORE = 1102;
	//......
}