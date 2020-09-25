<?php



class WpInstaller
{
	protected $env;
	protected $envFile;
	protected $wpResponse;

	function __construct()
	{
		$this->setErrorHandler();
		$this->envFile = dirname(__DIR__) . '/_env';
		$this->setEnvParams();
	}

}
