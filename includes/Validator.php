<?php

// Direct access not allowed.
if ( ! defined("ABSPATH"))
{
	die;
}

if ( ! class_exists("PWST_Validator"))
{
	class PWST_Validator
	{
		public static function notEmpty($value)
		{
			if (empty($value))
			{
				echo "Error empty value";
			}
		}

		public static function keyExists($key, $array)
		{
			if ( ! array_key_exists($key, $array))
			{
				echo "Error key '$key' not defined";
			}
		}
	}
}