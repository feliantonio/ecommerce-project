<?php

class masterAdmin extends MasterBase
{
    public function __construct()
    {
        $this->SetHeader(Common::$PathInclude . "headerP.php");
        $this->SetFooter(Common::$PathInclude . "footer.php");
    }

    public function GetTitolo(): string
    {
        return "Pannello Admin";
    }
}
