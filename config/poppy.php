<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Custom Module Driver
	|--------------------------------------------------------------------------
	|
	| This option controls the module storage driver that will be utilized.
	| This driver manages the retrieval and management of module properties.
	| Setting 'driver' to 'custom' can use your self definition driver.
	|
	*/

	'custom_driver' => '',

	/*
	|--------------------------------------------------------------------------
	| Remap Module Subdirectories
	|--------------------------------------------------------------------------
	|
	| Redefine how module directories are structured. The mapping here will
	| be respected by all commands and generators.
	|
	*/

	'path_map' => [
		// To change where migrations go, specify the default
		// location as the key and the new location as the value:
		// 'Database/Migrations' => 'src/Database/Migrations',
	],

	/*
	|--------------------------------------------------------------------------
	| Default Pagination Num
	|--------------------------------------------------------------------------
	|
	*/
	'pagesize' => 15,
];