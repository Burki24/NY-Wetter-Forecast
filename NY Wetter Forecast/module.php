<?php

declare(strict_types=1);
    class NYWetterForecast extends IPSModule
    {
            public function Create()
            {
            //Never delete this line!
            parent::Create();
            $this->RegisterPropertyString("Location", '{"latitude":0,"longitude":0}');
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

       
    
    
