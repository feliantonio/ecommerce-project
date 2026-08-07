<?php

class masterCarrrello extends MasterBase
{
    public function __construct()
    {
        $this->SetHeader(Common::$PathInclude . "headerP.php");
        // $this->SetAsideLeft(Common::$PathInclude . "asideLeft.php");
        // $this->SetAsideRight(Common::$PathInclude . "asideRight.php");
        // $this->SetNav(Common::$PathInclude . "nav.php");
        $this->SetFooter(Common::$PathInclude . "footer.php");
        $this->SetTemplate(Common::$PathTemplates . "templateCarrello.php");
    }

    public function GetTitolo(): string
    {
        return "Carrello";
    }
}
