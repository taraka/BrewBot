<?php

/**
 * The LSF application entry point
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Bootstrap.inc.php 55 2010-04-06 12:43:43Z tom $
 */

class LSF_Application
{
	private
		$_dispatch;
	
	private static
		$_profilingEnabled,
		$_cache,
		$_frameworkPath,
		$_applicationPath,
		$_baseUrl,
		$_environment = null,
		$_bootstrap,
		$_unitTest,
		$_executionStartTime;
	
	public function __construct()
	{
		self::$_executionStartTime = microtime(true);

		spl_autoload_register(array('LSF_Application', 'autoload'));
		set_exception_handler(array('LSF_Application', 'unhandledException'));
		set_error_handler(array('LSF_Application', 'unhandledError'));
		
		date_default_timezone_set('Europe/London');
		
		/**
		 * Set up paths
		 */
		self::$_frameworkPath = realpath(getcwd() . '/framework');
		self::$_applicationPath = realpath(getcwd() . '/application');
		
		
		/**
		 * Seup up CLI paths
		 */
		if (!self::$_frameworkPath) {
			self::$_frameworkPath = realpath(dirname(__FILE__));
		}
		if (!self::$_applicationPath) {
			self::$_applicationPath = self::$_frameworkPath . '/build/application';
		}
		
		self::$_baseUrl = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null);
		
		self::$_environment = $this->testEnviroment();
		
		$this->setUpDispatch();
		
		LSF_Config::setup();

		/**
		 * Include the DateTime object if the built in one doesnt exist
		 */
		if (!class_exists('DateTime', false)) {
			include_once (self::$_frameworkPath . '/Utils/DateTime/DateTime.inc.php');
		}
		
		$this->enableProfiling();
	}
	
	/**
	 * Look for an env file in the config directory and return its content
	 * 
	 * @return string
	 */
	private function testEnviroment()
	{
		$envFile = self::$_applicationPath . '/Config/env';
		if (file_exists($envFile)) {
			return trim(file_get_contents($envFile));
		}
		else {
			return false;
		}
	}
	
	/**
	 * Call the bootstrap, either the default LSF bootstrap or a custom app bootstrap if available
	 *
	 * @return void
	 */
	public function bootstrap()
	{
		if (file_exists(self::$_applicationPath . '/Bootstrap.inc.php') && !self::isUnitTest()) {
			self::$_bootstrap = new Bootstrap();
		}
		else {		
			self::$_bootstrap = new LSF_Bootstrap();
		}
		
		self::$_bootstrap->run();
		
		if(method_exists($this->_dispatch, 'getRouter'))
		{
			self::$_bootstrap->addRoutes($this->_dispatch->getRouter());
		}
	}
	
	/**
	 * Sets up the applications dispatch object in preperation for dispatching the request
	 * 
	 * @return void
	 */
	public function setUpDispatch()
	{
		if (empty($GLOBALS['argv'])) {
			$this->_dispatch = new LSF_Dispatch_Web();
		}
		else
		{
			if (self::isUnitTest()) {
				$this->_dispatch = new LSF_Dispatch_Test();
			}
			else {
				if (self::isApplicationCli())
				{
					$this->_dispatch = new LSF_Dispatch_AppConsole();
				}
				else
				{
					$this->_dispatch = new LSF_Dispatch_Console();
				}
			}
		}
	}
	
	/**
	 * Dispatch the request
	 *
	 * @return void
	 */
	public function run()
	{
		return $this->_dispatch->run();
	}
	
	/**
	 * Sets up profiling if the xhprof extension is enabled and the profiling_enabled config balue is true
	 */
	public function enableProfiling()
	{
		if (extension_loaded('xhprof') && LSF_Config::get('enable_profiling') && LSF_Config::get('xhprof_lib_dir'))
		{
			self::$_profilingEnabled = true;
			include_once LSF_Config::get('xhprof_lib_dir') . 'utils/xhprof_lib.php';
		    include_once LSF_Config::get('xhprof_lib_dir') . 'utils/xhprof_runs.php';
		    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY,
		    	array('ignored_functions' => array('call_user_func', 'call_user_func_array')));
		}
	}
	
	/**
	 * Set the application to not process profiling data
	 */
	public static function disableProfiling()
	{
		self::$_profilingEnabled = false;
	}
	
	/**
	 * Return the timestamp that this request began
	 *
	 * @return int
	 */
	public static function getExecutionStartTime()
	{
		return self::$_executionStartTime;
	}
	
	/**
	 * Returns whether or not this request is a unit test
	 *
	 * @return bool
	 */
	public static function isUnitTest()
	{
		return defined('UNIT_TEST');
	}
	
	/**
	 * Returns whether or not this request is an application cli
	 *
	 * @return bool
	 */
	public static function isApplicationCli()
	{
		return defined('APPLICATION_CLI');
	}
	
	/**
	 * Returns the path to the framework
	 *
	 * @return string
	 */
	public static function getFrameworkPath()
	{
		return self::$_frameworkPath;
	}
	
	/**
	 * Returns the path to the application
	 *
	 * @return string
	 */
	public static function getApplicationPath()
	{
		return self::$_applicationPath;
	}
	
	/**
	 * Returns the base URL for this application
	 *
	 * @return string
	 */
	public static function getBaseUrl()
	{
		return self::$_baseUrl;
	}
	
	/**
	 * Returns the bootstrap for this application
	 *
	 * @return LSF_BootstrapAbstract
	 */
	public static function getBootstrap()
	{
		return self::$_bootstrap;
	}
	
	/**
	 * Returns the environment of this app
	 *
	 * @return string
	 */
	public static function getEnvironment()
	{
		return self::$_environment;
	}
	
	/**
	 * Sets the enviroment for this app
	 *
	 * @return string
	 */
	public static function setEnvironment($env)
	{
		return self::$_environment = $env;
	}
	
	/**
	 * Returns whether or not a class file exists for the given class name
	 *
	 * @param string $className
	 * @return bool
	 */
	public static function classFileExists($className)
	{
		$classParts = explode('_', $className);
		
		foreach ($classParts as $key => $part) {
			$classParts[$key] = ucfirst($part);
		}
		
		$classPath = false;
		
		$filePathName = implode('/', $classParts);
		$lsfClass = substr($filePathName, 0, 4) == 'LSF/';
		$filePathName = str_replace('LSF', '', $filePathName);
		$fileName = $filePathName . '.inc.php';
		if ($lsfClass && file_exists(self::getFrameworkPath() . $fileName)) {
			$classPath = self::getFrameworkPath() . $fileName;
		}
		elseif (file_exists(self::getApplicationPath() . '/' . $fileName)) {
			$classPath = self::getApplicationPath() . '/' . $fileName;
		}
		
		return $classPath;
	}
	
	/**
	 * LSF application autoloader
	 *
	 * @param string $className
	 * @throws LSF_Exception_ClassNotFound
	 * @return void
	 */
	public static function autoload($className)
	{
		if (substr($className, 0, 7) == 'Smarty_') {
			return;
		}
		
		if (($file = self::classFileExists($className)) && file_exists($file) && !class_exists($className, false)) {
			include_once($file);
		}
		else
		{
			$newClassName = preg_replace('/[^\w]/', '', $className);
			$classError = 'Unable to load class: ' . $className;
                
			if ($newClassName == $className)
			{
				eval("class $className {}");
				throw new LSF_Exception_ClassNotFound($classError);
			}
			else {
				LSF_Application::unhandledException(new LSF_Exception_ClassNotFound($classError));
			}
		}
	}
	
	/**
	 * Send error headers
	 *
	 * @param Exception $e
	 */
	public static function unhandledException(Exception $e)
	{
		if (!headers_sent())
		{
			if ($e instanceof LSF_Exception_ControllerNotFound) {
				header("HTTP/1.1 404 Not Found");
				header("Status: 404 Not Found");
			}
			else {
				header("HTTP/1.1 503 Service Temporarily Unavailable");
				header("Status: 503 Service Temporarily Unavailable");
				header("Retry-After: 3600");
				header("Connection: Close");				
			}
		}
		
		if (!LSF_Config::get('show_errors') && !self::isApplicationCli())
		{
			if ($e instanceof LSF_Exception_ControllerNotFound) {
				readfile(LSF_Application::getFrameworkPath() . '/Errors/404.html');
			}
			else {
				readfile(LSF_Application::getFrameworkPath() . '/Errors/Exception.html');
			}
		}
		else
		{
			echo 'Exception (' . get_class($e) . '): ' . $e->getMessage() . "\n";
			
			if (LSF_Config::get('show_debug')) {
				$debug = new LSF_Debug();
				$debug->flush();
			}
		}
		
		if (!($e instanceof LSF_Exception_ControllerNotFound)) {
			self::sendErrorEmail($e);
		}
		
		exit(1);
	}
	
	/**
	 * Deal with any unhandeled errors
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 */
	public static function unhandledError($errno, $errstr, $errfile, $errline)
	{
		if (($errno == E_ERROR || $errno == E_USER_ERROR) && LSF_Config::get('show_debug')) {
			$debug = new LSF_Debug();
			$debug->flush();
		}

		return false;
	}
	
	/**
	 * Send an exception email if we have an unhandeled exception
	 * 
	 * @param Exception $e
	 * @return void
	 */
	public static function sendErrorEmail(Exception $e)
	{
		if (LSF_Config::get('errors_email'))
		{
			if (!empty($_SERVER['HTTP_HOST'])) {
				$host = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ;
			}
			else {
				$host = 'CLI: ' . implode(' ', $GLOBALS['argv']);
			}
			
			$message = 'Exception (' . get_class($e) . '): ' . $e->getMessage() . "\n";
			$message .= "Request: {$host}\n\n";
			$message .= 'Error occored in: ' . $e->getFile() . '(' . $e->getLine() . ")\n\n"; 
			
			$message .= $e->getTraceAsString() . "\n\n";
			
			$app = LSF_Config::get('application_name') ? LSF_Config::get('application_name') : 'LSF';
			
			mail(LSF_Config::get('errors_email'), $app . ": Exception occurred (" . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'cli')  . ")", "From: alerts@labelmedia.co.uk", $message);
		}
	}
	
	public function __destruct()
	{
		if (self::$_profilingEnabled && empty($GLOBALS['argv']))
		{
			$profilerNamespace = LSF_Config::get('application_name') ? LSF_Config::get('application_name') : 'LSF';  // namespace for your application
			$xhprofData = xhprof_disable();
			$xhprofRuns = new XHProfRuns_Default();
			$runId = $xhprofRuns->save_run($xhprofData, $profilerNamespace);
			
			if (LSF_Config::get('xhprof_ui_url'))
			{
				$profilerUrl = LSF_Config::get('xhprof_ui_url') . '/index.php?run=' . $runId . '&amp;source=' . $profilerNamespace;
				echo '<a href="'. $profilerUrl .'" target="_blank">Profiler output</a>';
			}
		}
	}
}
