<?php
	class Docker
	{
		private $CurlClient			=	null;

		function __construct() {
			$this->$CurlClient = curl_init();
			curl_setopt($this->$CurlClient, CURLOPT_UNIX_SOCKET_PATH, "/var/run/docker.sock");
		}

		public function getAllContainers(){
			curl_setopt($this->$CurlClient, CURLOPT_URL, "http:/v1.24/containers/json");
			$jsonRAWData = curl_exec($this->$CurlClient);
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