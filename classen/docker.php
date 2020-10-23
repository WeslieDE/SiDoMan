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
			curl_setopt($this->CurlClient, CURLOPT_URL, "http:/v1.24/containers/json");

			$jsonRAWData = curl_exec($this->CurlClient);
			return json_decode($jsonRAWData, TRUE);
		}

		public function getContainerLogs($container){
			curl_setopt($this->CurlClient, CURLOPT_URL, "http:/v1.24/containers/".$container."/logs?stdout=1&tail=150");

			$rawOutput = curl_exec($this->CurlClient);
			return $rawOutput;
		}

		public function startContainer($container){

		}

		public function stopContainer($container){

		}

		public function killContainer($container){

		}
	}
?>
