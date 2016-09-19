<?php
/**
* Plugin Name: Power Staging
* Plugin URI: http://someurl.com
* Description: The easiest way to create custom staging instances
* Author: Felipe Cespedes
* Version: 0.0.1
*/

// Direct access not allowed.
if ( ! defined("ABSPATH"))
{
	die;
}

class PWST_PowerStaging
{
	public function __construct()
	{
		define("PWST_POWERSTAGING_PLUGIN_PATH", plugin_dir_path(__FILE__));
		define("PWST_POWERSTAGING_PLUGIN_URL", plugin_dir_url(__FILE__));

		add_action("admin_menu", array($this, "pwst_add_admin_menu"));
		add_action("pwst_do_staging", array($this, "pwst_do_staging"), 10, 2);		

		add_filter("pwst_build_staging_params", array($this, "pwst_build_staging_params"), 10, 3);
		add_filter("pwst_str_encode", array($this, "pwst_str_encode"));
		add_filter("pwst_str_decode", array($this, "pwst_str_decode"));

		wp_enqueue_style(
    		"pwst_powerstaging_script",
    		PWST_POWERSTAGING_PLUGIN_URL."css/pwst-powerstaging.css",
    		array(),
    		"1.0.0"
		);

		wp_enqueue_script(
			"pwst_powerstaging_script",
			PWST_POWERSTAGING_PLUGIN_URL."js/pwst-powerstaging.js",
			array("jquery"),
			"1.0.0",
			true
		);
	}

	public function pwst_add_admin_menu()
	{
		add_options_page(
			"Power Staging",
			"Power Staging",
			"manage_options",
			__FILE__,
			array($this, "pwst_render_admin_view")
		);
	}

	public function pwst_render_admin_view()
	{
		$pwst_siteurl			= get_option("siteurl");
		$pwst_windows_staging	= false;
		$pwst_home_folder		= get_home_path();

		if ($_POST)
		{
			$params = apply_filters("pwst_build_staging_params", $pwst_siteurl, $pwst_home_folder, $_POST);
			do_action("pwst_do_staging", $params["search"], $params["replace"]);
		}

		$options = get_option("pwst_powerstaging_plugin_options");
		if (isset($options["staging_file"]) && ! empty($options["staging_file"]))
		{
			$staging_file = $options["staging_file"];
		}

		require_once(PWST_POWERSTAGING_PLUGIN_PATH."includes/template.php");
	}

	public function pwst_do_staging($search, $replace)
	{

		require_once PWST_POWERSTAGING_PLUGIN_PATH . "includes/PWST_Auditor.php";
		require_once PWST_POWERSTAGING_PLUGIN_PATH . "mysql-dump/FKMySQLDump.php";

		//Connects to mysql server
		@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

		//Set encoding
		mysql_query("SET CHARSET utf8");
		mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");

		$stagingFilepath = PWST_POWERSTAGING_PLUGIN_PATH."staging-files/staging_db.sql";

		$auditor = new PWST_Auditor($search, $replace);
		//Creates a new instance of FKMySQLDump: it exports without compress and base-16 file
		$dumper = new FKMySQLDump(DB_NAME, $stagingFilepath, false, false, $auditor);

		$params = array(
			//'skip_structure' => TRUE,
			//'skip_data' => TRUE,
		);

		/**
		* TODO SQL generated file
		* 1. Some default values are not specified
		* 2. Add Charset and Collate to create table statement
		*/

		//Make dump
		$dumper->doFKDump($params);

		// TODO save multiple files and allow user select which want to download

		$stagingFileUrl = PWST_POWERSTAGING_PLUGIN_URL."staging-files/staging_db.sql";
		update_option("pwst_powerstaging_plugin_options", array("staging_file" => $stagingFileUrl));
	}

	public function pwst_build_staging_params($siteUrl, $homeFolder, $request)
	{
		// TODO Run needed validations
		//require_once PWST_POWERSTAGING_PLUGIN_PATH . "includes/PWST_Validator.php";
		//PWST_Validator::keyExists("pwst_sql_file_route", $post);
		//PWST_Validator::keyExists("pwst_sql_file_route", $post);
		//PWST_Validator::notEmpty($post["pwst_sql_file_route"]);
		//PWST_Validator::notEmpty($post["pwst_sql_file_route"]);

		$search = array();
		$replace = array();

		array_push($search, rtrim($siteUrl, "/"));
		array_push($replace, rtrim($request["pwst_new_siteurl"], "/"));

		// Path replacement validations based on the current OS
		if (DIRECTORY_SEPARATOR === "\\")
		{
			array_push($search, str_replace("/", "\\", rtrim($homeFolder, "/")));
			array_push($replace, rtrim($request["pwst_new_home_folder"], "/"));

			array_push($search, str_replace("/", "\\\\", rtrim($homeFolder, "/")));
			array_push($replace, rtrim($request["pwst_new_home_folder"], "/"));
		}
		else
		{
			array_push($search, rtrim($homeFolder, "/"));
			array_push($replace, rtrim($request["pwst_new_home_folder"], "/"));
		}

		if (
			isset($request["pwst_custom_field_old_value"]) && 
			! empty($request["pwst_custom_field_old_value"]) &&
			isset($request["pwst_custom_field_new_value"]) && 
			! empty($request["pwst_custom_field_new_value"])
		) {
			foreach ($request["pwst_custom_field_old_value"] as $index => $value)
			{
				array_push($search, $value);
				array_push($replace, $request["pwst_custom_field_new_value"][$index]);
			}
		}

		// TODO add windows special path replacement
		/*
		if ($request["pwst_windows_staging"])
		{

		}
		*/

		// TODO save options in a config file

		return array("search" => $search, "replace" => $replace);
	}

	public function pwst_str_encode($str)
	{
		return base64_encode($str);
	}

	public function pwst_str_decode($str)
	{
		return base64_decode($str);
	}
}

$pwst_powerstaging = new PWST_PowerStaging();