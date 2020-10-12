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

	protected function setEnvParams()
	{
		$this->env = $this->parseEnvInfo();
	}	
	
	protected function parseEnvInfo()
	{
		$envRawData = file( $this->envFile );
		$parsed = [];
		foreach ($envRawData as $k => $v) {
			$chunk = explode('=', $v);
			$parsed[$chunk[0]] = trim($chunk[1]);
		}
		return $parsed;
	}	

	
	public function install()
	{
		try {
			$getInstall = $this->setWpDbParams()
						   	   ->setWpAdminParams();
			$this->wpResponse = $getInstall;
			return $this;
		} catch (\ErrorException $e) {
			echo $e->getMessage() . 'Db connection error. Please, check params in your _env file!';
			die;
		}
	}

	
}
