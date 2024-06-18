<?php 

spl_autoload_register(function(string $className) {
	
	$classesDir = __DIR__ . '/..';

	$classPath = str_replace('DaVinci', 'classes', $className);
	
	$classPath = $classesDir . '/' . $classPath . '.php';

	if(file_exists($classPath)) {
		require_once $classPath;
	}
});