<?php

    class MasterBase{
        private string $asideLeft = "";
        private string $asideRight = "";
        private string $header = "";
        private string $footer = "";
        private string $contenuto = "";
        private string $nav = "";
        private string $template = "";

        // SET
        public function SetAsideLeft(string $value){
            $this -> asideLeft = $value;
        }
        public function SetAsideRight(string $value){
            $this -> asideRight = $value;
        }
        public function SetHeader(string $value){
            $this -> header = $value;
        }
        public function SetFooter(string $value){
            $this -> footer = $value;
        }
        public function SetContenuto(string $value){
            $this -> contenuto = $value;
        }
        public function SetNav(string $value)
        {
            $this->nav = $value;
        }
        public function SetTemplate(string $value)
        {
            $this->template = $value;
        }

        // GET
        public function GetAsideLeft() : string{
            if (isset($this -> asideLeft)) {
                return $this -> asideLeft;
            }
            return "";
        }
        public function GetAsideRight() : string{
            if (isset($this -> asideRight)) {
                return $this -> asideRight;
            }
            return "";
        }
        public function GetHeader() : string{
            if (isset($this -> header)) {
                return $this -> header;
            }
            return "";
        }
        public function GetFooter() : string{
            if (isset($this -> footer)) {
                return $this -> footer;
            }
            return "";
        }
        public function GetContenuto() : string{
            if (isset($this -> contenuto)) {
                return $this ->  contenuto;
            }
            return "";
        }
        public function GetNav(): string
        {
            if (isset($this->nav)) {
                return $this->nav;
            }
            return "";
        }
        public function GetTemplate(): string
        {
            if (isset($this->template)) {
                return $this->template;
            }
            return "";
        }

        public function GetTitolo() : string{
            return "";
        }

    }
