<?php
namespace Utils
{
	function array_key_default($key,$array,$defValue)
	{
		if (array_key_exists($key, $array)){
				return $array[$key];
		}
		else{
			return $defValue;
		}
	}

	function format_float($aFloat,$decimals = 2,$dec_point = '.',$thousands_sep = ',' )
	{
		return number_format($aFloat,$decimals,$dec_point,$thousands_sep);
	}

	function format_date($aDateString,$format='d.m.Y') /* 'H:i' - pt ora*/
	{
	   $aDate=new DateTime(''.$aDateString);
	   return $aDate->format($format);
	}
}