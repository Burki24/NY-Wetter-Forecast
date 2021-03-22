<?php

declare(strict_types=1);
	class NYWetterForecast extends IPSModule
	{
		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RequireParent('{BBD65439-F443-47B3-66A5-C2DF81C7934D}');


			$this->RegisterPropertyString('Longitude', '');
			$this->RegisterPropertyString('Latitude', '');
			$this->RegisterPropertyString('Name', '');
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

			$lot = $this->ReadPropertyString('Longitude');
			$lat = $this->ReadPropertyString('Latitude');
			$nam = $this->ReadPropertyString('Name');
		
					}

		public function Send(string $RequestMethod, string $RequestURL, string $RequestData, int $Timeout)
		{
		}

		public function ReceiveData($JSONString)
		{
				// create curl resource
			$ch = curl_init();

		// url setzen
			curl_setopt($ch, CURLOPT_URL, "https://api.met.no/weatherapi/locationforecast/2.0/complete?lat=$lat&lon=$lot");

		// daten als String setzen und Browser vorgeben
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

			$JSONString = curl_exec($ch); // $JSONString beinhaltet den String

			curl_close($ch); // curl schliessen zwecks Speicher-Entlastung
			$data = json_decode($JSONString);  // String in array wandeln
			
		}
	}