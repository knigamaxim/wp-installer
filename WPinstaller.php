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

	protected function setWpDbParams()
	{


		$context = stream_context_create([
			'http' => [
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query([
					'language' => 'ru_RU'
				]),
			]
		]);
		file_get_contents('http://wp.local/wp-admin/setup-config.php?step=0', null, $context);


		$context = stream_context_create([
			'http' => [
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query([
					'language' => 'ru_RU'
				]),
			]
		]);
		file_get_contents('http://wp.local/wp-admin/setup-config.php?step=1&language=ru_RU', null, $context);
		

		$context = stream_context_create([
			'http' => [
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query([
					'dbname' => $this->env['dbname'],
					'uname' => $this->env['dbusername'],
					'pwd' => $this->env['dbpassword'],
					'dbhost' => $this->env['dbhost'],
					'prefix' => $this->env['tablesprefix'],
					'language' => 'ru_RU',
				]),
			]
		]);
		file_get_contents($this->env['domain'] . '/wp-admin/setup-config.php?step=2', null, $context);
		return $this;
	}

	public function parseWpResponse()
	{
		$str = $this->wpResponse;
		preg_match('~<h1>.*</h1>~', $str, $found);
		return $found[0];
	}
	
	public function getResults()
	{
		return $this->parseWpResponse();
	}
	
}
