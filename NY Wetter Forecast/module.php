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
            }
   }        

       
    
    
