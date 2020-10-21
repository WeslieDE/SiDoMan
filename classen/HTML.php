<?php
	class HTML
	{
	
		//Hier wird der HTML Code zum Ausgeben vorbereitet.
		//Dieser kann aus einer fertigen HTML Seite ausgelesen werden, oder aber auch st�ck f�r St�ck
		//Zusammen gebaut werden.
		
		//Die Einzelnen Daten k�nnen nicht direkt von Au�en ver�ndert werden, sondern m�ssen durch die Bereitgestellten Optionen gesetzt werden.
		
		private $HTMLTitle				= " ";			//Wird in den <header> als <title> Geschrieben.
		private $StatusMeldung			= " ";			//Falls Vorhenden eine Statusmeldung vom Script im HTML Text.
		private $DasMenu				= " ";			//Beinhaltet das Fertige Men�
		private $DerInhalt				= " ";			//Beinhaltet den Fertigen Inhalt
		private $HTMLDatei				= " ";			//Der inhalt der eingelesen wurde.
		private $HTMLHeader				= " ";			//Der HTML HEADER der eingelesen wurde.
		private $FertigesHTML			= " ";			//Das Fertige HTML bereit zum Ausgeben.
		private $isBuild				= false;		//Hier wird festgehalten ob $FertigesHTML aktuell ist oder nicht.
		
		//Der <title> wird Generiert.(%%EchoTitle%%)
		//Dieser wird im HTML Code sp�ter als %%HTMLTitle%% aufgerufen.
		
		public function setHTMLTitle($neuerTitle){ 
			//Der Bisherige Title wird komplett �berschrieben und gleichzeitig ein neuer Gesetzt.
			$this->HTMLTitle = $neuerTitle; 
			$this->isBuild = false;
		}
		
		public function addHTMLTitle($Hinzufugen){ 
			//Zu dem Bisherigen Titel wird noch etwas am ende hinzugef�gt.
			$this->HTMLTitle = $this->$HTMLTitle.$Hinzufugen; 
			$this->isBuild = false;
		}
		
		public function RemoveHTMLTitle(){ 
			//Der Titel wird Komplett gel�scht.
			$this->HTMLTitle = " ";
			$this->isBuild = false;
		}
		
		
		//Der HTML HEADER wird Generiert.(%%echoHeader%%)
		//Dieser wird im HTML Code sp�ter als %%echoHeader%% aufgerufen.
		
		public function setHTMLHeader($neuerHeader){ 
			//Der Bisherige Header wird komplett �berschrieben und gleichzeitig ein neuer Gesetzt.
			$this->HTMLHeader = $neuerHeader; 
			$this->isBuild = false;
		}
		
		public function addHTMLHeader($Hinzufugen){ 
			//Zu dem Bisherigen Header wird noch etwas am ende hinzugef�gt.
			$this->HTMLHeader = $this->HTMLHeader.$Hinzufugen; 
			$this->isBuild = false;
		}
		
		public function RemoveHTMLHeader(){ 
			//Der Header wird Komplett gel�scht.
			$this->HTMLHeader = " ";
			$this->isBuild = false;
		}
		
		public function importHTMLHeader($file){ 
			//Der HTML Header wird aus einer Datei eingelesen und der bisherige gel�scht.
			$this->HTMLHeader = file_get_contents($file);
			$this->isBuild = false;
		}
		
		//Der StatusText wird ge�ndert.(%%StatusMeldung%%)
		//Dieser wird im HTML Code sp�ter als %%StatusMeldung%% aufgerufen.
		
		public function setStatusMeldung($neueMeldung){ 
			//Die bisherige Status meldung wird komplett �berschrieben und gleichzeitig ein neuer Gesetzt.
			$this->StatusMeldung = $neueMeldung; 
			$this->isBuild = false;
		}
		
		public function RemoveStatusMeldung(){ 
			//Die Meldung wird Komplett gel�scht.
			$this->StatusMeldung = " ";
			$this->isBuild = false;
		}
		
		//Ab hier wird das Men� Zusammengebaut. (%%EchoMenu%%)
		
		public function importTextMenu($neuesMenu){ 
			//Das Komplette Men� wird direkt importiert und das alte �berschreiben.
			$this->DasMenu = $neuesMenu;
			$this->isBuild = false;
		}
		
		public function importHTMLMenu($file){ 
			//Das Komplette Men� wird aus einer Datei ausgelesen und das alte �berschrieben.
			$this->DasMenu = file_get_contents($file);
			$this->isBuild = false;
		}
		
		public function addToMenu($html){ 
			//Es wird noch etwas ans Men� angehengt.
			$this->DasMenu = $this->$DasMenu.$html;
			$this->isBuild = false;
		}
		
		
		
		//Der Seiten HTML Quelcode wird eingelesen.
		
		public function importHTML($file){ 
			//Der HTML Quelltext wird aus einer Datei eingelesen.
			$this->HTMLDatei = file_get_contents($file);
			$this->isBuild = false;
		}
		
		public function setHTML($htmlCode){ 
			//Der HTML Quelltext wird direkt gesetzt.
			$this->HTMLDatei = $htmlCode;
			$this->isBuild = false;
		}
		
		public function addNachHTML($htmlCode){ 
			//Der HTML Quelltext wird direkt gesetzt.
			$this->HTMLDatei = $this->HTMLDatei.$htmlCode;
			$this->isBuild = false;
		}
		
		public function addVorHTML($htmlCode){ 
			//Der HTML Quelltext wird direkt gesetzt.
			$this->HTMLDatei = $htmlCode.$this->HTMLDatei;
			$this->isBuild = false;
		}
		
		public function DeleteHTML(){ 
			//Der HTML Quelltext wird gel�scht.
			$this->HTMLDatei = " ";
			$this->isBuild = false;
		}

		//Der inhalt der Seite wird zusammen gesetzt (nicht der quelltext) (%%EchoInhalt%%)
		
		public function importSeitenInhalt($file){ 
			//L�d einen fertigen Text aus einer datei.
			$this->DerInhalt = file_get_contents($file);
			$this->isBuild = false;
		}

		public function setSeitenInhalt($html){ 
			//Setz den Seiteninhalt und L�scht den alten Komplett.
			$this->DerInhalt = $html;
			$this->isBuild = false;
		}
		
		public function importAndAddSeitenInhalt($file){ 
			//L�d einen fertigen Text aus einer datei.
			$this->DerInhalt = $this->DerInhalt.file_get_contents($file);
			$this->isBuild = false;
		}
		
		public function addToSeitenInhalt($html){ 
			//Es wird noch weitere Text an den Seiteninhalt angeh�ngt.
			$this->DerInhalt = $this->DerInhalt.$html;
			$this->isBuild = false;
		}	
		
		public function GetSeitenInhalt(){ 
			//Der Seiteninhalt wird zur�ckgegeben.
			return $this->DerInhalt;
		}	

		public function DeleteSeitenInhalt(){ 
			//L�scht den Seiten inhalt.
			$this->DerInhalt = " ";
			$this->isBuild = false;
		}
		
		public function ReplaceSeitenInhalt($tag, $text){ 
			//Ersezt Seiten Inhalt
			$this->DerInhalt = str_replace($tag, $text, $this->DerInhalt);
		}
		
		public function ReplaceLayoutInhalt($tag, $text){ 
			//Ersezt Layout Inhalt
			$this->HTMLDatei = str_replace($tag, $text, $this->HTMLDatei);
		}
		
		public function CompressHTML(){ 
			if($this->isBuild){
				$this->FertigesHTML = str_replace("  ", "", $this->FertigesHTML);
				
				$this->FertigesHTML = str_replace("	", "", $this->FertigesHTML);
			}else{
				die("Es kann nur Fertiger HTML Code kompremiert werden.");
				return false;
			}
		}
		
		//Hier wird der Fertige HTML Code generiert.
		//Und alle 3 Teile, Men� Titel und inhalt zusammengef�gt.
		public function build(){ 
			//Der HTML Code wird zusammen gesetzt.
			
			$this->FertigesHTML = null;				//Der Speicher wird gellert, falls schon einmal Quelltext generiert wurde.
			$this->FertigesHTML = $this->HTMLDatei;	//Und der Unverarbeitete HTML Quelltext eingelesen.
			
			//Das Men� wird in den HTML Quellcode eingef�gt.
			$this->FertigesHTML = str_replace("%%EchoMenu%%", $this->DasMenu, $this->FertigesHTML);
		
			//Der inhalt wird in den HTML Quellcode eingef�gt.
			$this->FertigesHTML = str_replace("%%EchoInhalt%%", $this->DerInhalt, $this->FertigesHTML);
			
			//Die Status Meldung wird in den HTML Quellcode eingef�gt.
			$this->FertigesHTML = str_replace("%%StatusMeldung%%", $this->StatusMeldung, $this->FertigesHTML);	
			
			//Der Titel wird in den HTML Quellcode eingef�gt.
			$this->FertigesHTML = str_replace("%%EchoTitle%%", $this->HTMLTitle, $this->FertigesHTML);	
			
			//Der HTML Header wird in den HTML Quellcode eingef�gt.
			$this->FertigesHTML = str_replace("%%echoHeader%%", $this->HTMLHeader, $this->FertigesHTML);	
			
			//Der Titel wird in den HTML Quellcode eingef�gt.
			$this->FertigesHTML = str_replace("%%datum%%", date("Y-m-dTH:i+2"), $this->FertigesHTML);	
			
			//Der Counter wird in den HTML Quellcode eingef�gt.
			$this->FertigesHTML = str_replace("%%GET_SITE%%", @$_GET['seite'], $this->FertigesHTML);
			
			//Die IP Adresse wird in den HTML Quellcode eingef�gt.
			$this->FertigesHTML = str_replace("%%GET_IP%%", @$_SERVER["REMOTE_ADDR"], $this->FertigesHTML);
			
			$this->isBuild = true;
		}	
		
		//Hier wird der Fertige HTML ausgegeben
		public function ausgabe(){ 
			if($this->isBuild){
				return $this->FertigesHTML;
			}else{
				die("Bitte erst den HTML Code zusammensetzen.");
				return false;
			}
		}	
		
	}
?>