<?php

declare(strict_types=1);
	class NYWetterForecast extends IPSModule
	{
		public function Create()
		{
			//Never delete this line!
			parent::Create();

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
		};	
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
			// Anlegen einer neuen Kategorie "NY-Wetterdaten"
			$id = IPS_GetParent($_IPS['SELF']);
			$childid = @IPS_GetCategoryIDByName("NY-Wetterdaten", $id);
    		if ($childid === false){                                                    // Prüfen, ob Kategorie existiert
    			$CatID = IPS_CreateCategory();                                          // Kategorie anlegen
    	IPS_SetName($CatID, "NY-Wetterdaten");                                  // Kategorie benennen
    	IPS_SetParent($CatID, $id);                                             // Kategorie einsortieren unter dem Objekt mit der ID "12345"
	}
}