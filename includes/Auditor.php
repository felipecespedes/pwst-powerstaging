<?php

// Direct access not allowed.
if ( ! defined("ABSPATH"))
{
	die;
}

if ( ! class_exists("PWST_Auditor"))
{
	class PWST_Auditor
	{
		/**
		* The value being searched for, otherwise known as the needle.
		* An array may be used to designate multiple needles.
		*
		* @var mixed
		*/
		private $search;

		/**
		* The replacement value that replaces found search values.
		* An array may be used to designate multiple replacements.
		*
		* @var mixed
		*/
		private $replace;

		public function __construct($search, $replace)
		{
			$this->search = $search;
			$this->replace = $replace;
		}

		public function replaceMatches($data)
		{
			return str_replace($this->search, $this->replace, $data);
		}
	}
}