<?php
	class Docker
	{
		private $CurlClient                     =       NULL;

		function __construct(){
			$this->CurlClient = curl_init();
			curl_setopt($this->CurlClient, CURLOPT_UNIX_SOCKET_PATH, "/var/run/docker.sock");
			curl_setopt($this->CurlClient, CURLOPT_BUFFERSIZE, 256);
			curl_setopt($this->CurlClient, CURLOPT_TIMEOUT, 1000000);
		}

		public function getAllContainers(){
			curl_setopt($this->CurlClient, CURLOPT_URL, "http:/v1.24/containers/json");

			$jsonRAWData = curl_exec($this->CurlClient);
			curl_close($this->CurlClient);
			return json_decode($jsonRAWData, TRUE);
		}

		public function getContainerData($container){

		}

		public function getContainerLogs($container){

		}

		public function startContainer($container){

		}

		public function stopContainer($container){

		}

		public function killContainer($container){

		}
	}
?>
