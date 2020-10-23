<?php
	class Docker
	{
		private $CurlClient                     =       NULL;

		function __construct(){
			$this->CurlClient = curl_init();
			curl_setopt($this->CurlClient, CURLOPT_UNIX_SOCKET_PATH, "/var/run/docker.sock");
			curl_setopt($this->CurlClient, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($this->CurlClient, CURLOPT_BINARYTRANSFER, TRUE);
		}

		public function getAllContainers(){
			curl_setopt($this->CurlClient, CURLOPT_POST, FALSE);
			curl_setopt($this->CurlClient, CURLOPT_URL, "http:/v1.24/containers/json?all=1");

			$jsonRAWData = curl_exec($this->CurlClient);
			return json_decode($jsonRAWData, TRUE);
		}

		public function getContainerLogs($container){
			curl_setopt($this->CurlClient, CURLOPT_POST, FALSE);
			curl_setopt($this->CurlClient, CURLOPT_URL, "http:/v1.24/containers/".$container."/logs?stdout=1&tail=350");

			$rawOutput = curl_exec($this->CurlClient);
			return $rawOutput;
		}

		public function startContainer($container){
			curl_setopt($this->CurlClient, CURLOPT_POST, TRUE);
			curl_setopt($this->CurlClient, CURLOPT_URL, "http:/v1.24/containers/".$container."/start");

			$rawOutput = curl_exec($this->CurlClient);
		}

		public function stopContainer($container){
			curl_setopt($this->CurlClient, CURLOPT_POST, TRUE);
			curl_setopt($this->CurlClient, CURLOPT_URL, "http:/v1.24/containers/".$container."/stop");

			$rawOutput = curl_exec($this->CurlClient);
		}

		public function killContainer($container){
			curl_setopt($this->CurlClient, CURLOPT_POST, TRUE);
			curl_setopt($this->CurlClient, CURLOPT_URL, "http:/v1.24/containers/".$container."/kill");

			$rawOutput = curl_exec($this->CurlClient);
		}

		public function getError($container){
			return curl_strerror(curl_errno($this->CurlClient));
		}
	}
?>
