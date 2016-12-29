<?php
namespace App\Log\Engine;

use Cake\Log\Engine\BaseLog;
use Cake\Network\Exception\SocketException;


class LogstashLog extends BaseLog
{

	public function __construct(array $config = [])
	{
		parent::__construct($config);

		if (!empty($this->_config['timeout'])) {
			$this->_path = $this->_config['timeout'];
		}
		if (!empty($this->_config['host'])) {
			$this->_path = $this->_config['host'];
		}
		if (!empty($this->_config['port'])) {
			$this->_path = $this->_config['port'];
		}

		$this->_open();

	}

	public function __destruct()
	{
		if (!empty($this->_handle)) {
			fflush($this->_handle);
			fclose($this->_handle);
			$this->_handle = null;
		}
	}



	/**
	 * Default config for this class
	 *
	 * - `levels` string or array, levels the engine is interested in
	 * - `scopes` string or array, scopes the engine is interested in
	 * - `host` string, the connection string for the Logstash server
	 * - `port` integer, the port number of the Logstash server
	 * - `timeout` integer, number of seconds timeout - default to 10 seconds
	 * - `tags` array, list of tags to be sent to logstash to identify the application
	 *
	 * @var array
	 */
	protected $_defaultConfig = [
		'levels' => [],
		'scopes' => [],
		'host' => null,
		'port' => null,
		'timeout' => 10,
		'tags' => []
	];

	/**
	 * The resource for connecting to the logstash server
	 *
	 * @var resource
	 */
	protected $_handle;


	/**
	 * Opens a connection to logstash
	 *
	 */
	protected function _open()
	{
		if (!$this->_handle) {
			$this->_handle = pfsockopen($this->_config['host'], $this->_config['port'], $errNo, $errSt, $this->_config['timeout']);
			if($this->_handle===false) {
				throw new SocketException('Could not connect to logstash');
			}
		}
		return $this->_handle;
	}


	protected function _write($message)
	{
		if (!$this->_handle) {
			$this->_open();
		}
		return fwrite($this->_handle, $message);
	}


	/**
	 * Implements writing to log files.
	 *
	 * @param string $level The severity level of the message being written.
	 *    See Cake\Log\Log::$_levels for list of possible levels.
	 * @param string $message The message you want to log.
	 * @param array $context Additional information about the logged message
	 * @return bool success of write.
	 */
	public function log($level, $message, array $context = []){
		$log = array(
			'@timestamp' => gmdate('c'),
			'level' => $level,
			'tags'=>$this->_config['tags'],
			'message' => $message
		);

		if(!empty($context)) {
			$log['context'] = $context;
		}


		$log = json_encode($log);

		// Ensure utf-8 encoding
		if (mb_detect_encoding($log) !== "UTF-8") {
			$log = utf8_encode($log);
		}
		return (bool)$this->_write($log);
	}



}