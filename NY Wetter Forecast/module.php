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
        }

        public function ReceiveData($JSONString)
        {
            // create curl resource
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL, "https://api.met.no/weatherapi/locationforecast/2.0/complete?lat=13.5070&lon=52.4127");

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

            // $output contains the output string
            $output = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch);
            $json_decoded = json_decode($output);
        }

        public function CreateKategorie()
        {
            // Anlegen einer neuen Kategorie "NY-Wetterdaten"
            $id = IPS_GetParent($_IPS['SELF']);
            $childid = @IPS_GetCategoryIDByName("NY-Wetterdaten", $id);
            if ($childid === false) {                                                    // Prüfen, ob Kategorie existiert
                $CatID = IPS_CreateCategory();                                          // Kategorie anlegen
            IPS_SetName($CatID, "NY-Wetterdaten");                                  // Kategorie benennen
            IPS_SetParent($CatID, $id);                                             // Kategorie einsortieren unter dem Objekt mit der ID "12345"
            }
        }

        public function CreateVariable()
        {
            // Erstellen der Variablen

            $id = IPS_GetParent($_IPS['SELF']);
            $kategorie = @IPS_GetCategoryIDByName("NY-Wetterdaten", $id);                        // Prüfen der richtigen Kategorie

            $Var = array(  	"NY_Luftdruck" => 2,                                        // Array mit Variablen-Name und Variablentyp
                              "NY_Lufttemperatur"=> 2,
                            "NY_Temperatur_Max"=> 2,
                            "NY_Temperatur_Min" => 2,
                            "NY_Bewölkungsdichte" => 1,
                            "NY_Bewölkunsdichte_Max" => 1,
                            "NY_Bewölkunsdichte_Min" => 1,
                            "NY_Bewölkungsdichte_Mittel" => 1,
                            "NY_Taupunkt" => 2,
                            "NY_Nebelflächenanteil" => 1,
                            "NY_Niederschlag" => 2,
                            "NY_Luftfeuchtigkeit_Rel" => 1,
                            "NY_UV-Index" => 2,
                            "NY_Windrichtung" => 2,
                            "NY_Windgeschwindigkeit" => 2);
    
            foreach ($Var as $Variable=>$Typ) {
                $Setname = $Variable;
                $KategorieID = IPS_GetCategoryIDByName("NY-Wetterdaten", $id);          // Kategorie abrufen
                $childid = @IPS_GetVariableIDByName($Setname, $kategorie);
                if ($childid === false) {                                                // Prüfen, ob Variablen existieren -> Ja => überspringen, nein => anlegen
                        $Variable = IPS_CreateVariable($Typ);                               // Variablen erstellen und Typ vergeben
                           IPS_SetName($Variable, $Setname);                                   // Variable benennen
                        IPS_SetParent($Variable, $KategorieID);                             // Variable einsortieren unter dem Objekt mit der ID "$KategorieID (Normwerte)"
                }
            }
        }
        public function CreateProfile()
        {
            // Erstellen fehlendes Profil "UVI"

            if (IPS_VariableProfileExists("NYW.UFI") ===false) {               // Prüfen, ob Profil existent
            IPS_CreateVariableProfile("NYW.UFI", 2);                        // Profil erstellen
            IPS_SetVariableProfileText("NYW.UFI", "", " UVI");           // Profil Messeinheit definieren
            IPS_SetVariableProfileValues("NYW.UFI", 0, 50, 1);         // Profil Messgrenzen und Schritte festlegen
            }
        }

        public function SetProfile()
        {
            // Zuteilen der Profile

            $id = IPS_GetParent($_IPS['SELF']);
            $kategorie = @IPS_GetCategoryIDByName("NY-Wetterdaten", $id);                        // Prüfen der richtigen Kategorie

            $Prof = array(  "NY_Luftdruck" => "~AirPressure.F",                                        // Array mit Variablenname und Profil-Typ
                        "NY_Lufttemperatur"=> "~Temperature",
                        "NY_Temperatur_Max"=> "~Temperature",
                        "NY_Temperatur_Min" => "~Temperature",
                        "NY_Bewölkungsdichte" => "~Intensitiy.100",
                        "NY_Bewölkunsdichte_Max" => "~Intensity.100",
                        "NY_Bewölkunsdichte_Min" => "~Intensity.100",
                        "NY_Bewölkungsdichte_Mittel" => "~Intensity.100",
                        "NY_Taupunkt" => "~Temperature",
                        "NY_Nebelflächenanteil" => "~Intensity.100",
                        "NY_Niederschlag" => "~Rainfall",
                        "NY_Luftfeuchtigkeit_Rel" => "~Intensity.100",
                        "NY_UV-Index" => "NYW.UFI",
                        "NY_Windrichtung" => "~WindDirection.Text",
                        "NY_Windgeschwindigkeit" => "~WindSpeed.kmh");
   
            foreach ($Prof as $Var_Prof=>$Profil) {
                $VariableID = IPS_GetVariableIDByName($Var_Prof, $kategorie);            // Variablen-IDs abrufen
                IPS_SetVariableCustomProfile($VariableID, $Profil);                      // Variablen Profil zuweisen
            }
        }
    }