<?php

class masterHome extends MasterBase
{
    public function __construct()
    {
        $this -> SetHeader(Common::$PathInclude."header.php");
        $this -> SetAsideLeft(Common::$PathInclude."asideLeft.php");
        $this -> SetFooter(Common::$PathInclude . "footer.php");
    }

    public function GetTitolo(): string
    {
        return "Home";
    }

}
