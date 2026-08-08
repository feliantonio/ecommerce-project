<?php

class Common{

    static public string $PathModels = "..".DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR;
    static public string $PathViews = "..".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR;
    static public string $PathDataDb = "..". DIRECTORY_SEPARATOR . "dataDb" . DIRECTORY_SEPARATOR;
    static public string $PathClassiMaster = ".." . DIRECTORY_SEPARATOR . "classiMaster" . DIRECTORY_SEPARATOR;
    static public string $PathInclude = ".." . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR;
    static public string $PathTemplates = ".." . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR;
    static public string $PathImages = ".." . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR;

    
    static public function SetSession(){
        session_start();
        if(isset($_SESSION["UserType"])){return;}
        Common::ReadFileConfig();
        $_SESSION["UserType"]="G";  // U, A
    }

    static public function Logout(){
        if(session_status() != PHP_SESSION_ACTIVE){session_start();}
        $_SESSION = array();
        session_unset();
        session_destroy();
        Common::SetSession();
    }

    static public function ReadFileConfig(){

        $configPath = "..".DIRECTORY_SEPARATOR."config.ini";
        $fileConfig = @parse_ini_file($configPath);

        $required = ["DbType", "DbHost", "DbName", "DbUserName", "DbUserPassword"];
        if ($fileConfig === false || array_diff($required, array_keys($fileConfig)) !== []) {
            die("Configurazione mancante o non valida: crea '$configPath' partendo da 'config.ini.example'.");
        }

        //configurazione db
        $_SESSION["DbType"]         = strtolower($fileConfig["DbType"]);
        $_SESSION["DbHost"]         = $fileConfig["DbHost"];
        $_SESSION["DbName"]         = $fileConfig["DbName"];
        $_SESSION["DbUserName"]     = $fileConfig["DbUserName"];
        $_SESSION["DbUserPassword"] = $fileConfig["DbUserPassword"];

    }

    // variabili $_GET
    public static function SetUserId(int $value):void{$_SESSION["UserId"]=$value;}
    public static function SetUserName(string $value):void{$_SESSION["UserName"]=$value;}
    public static function SetUserType(string $value):void{$_SESSION["UserType"]=$value;}
    public static function SetUserMail(string $value):void{$_SESSION["UserMail"]=$value;}

    public static function GetUserId():int{return $_SESSION["UserId"];}
    public static function GetUserName():string{return $_SESSION["UserName"];}
    public static function GetUserType():string{return $_SESSION["UserType"];}
    public static function GetUserMail():string{return $_SESSION["UserMail"];}

    // Guardia di pagina per le controller .admin/*.php: da chiamare come prima
    // riga dopo require_once common.php. Se l'utente non e' admin, mostra una
    // pagina di accesso negato ed esce subito - nessun $mst/template esiste
    // ancora a questo punto della richiesta.
    public static function RequireAdmin(): void
    {
        if (self::GetUserType() != "A") {
            require(self::$PathInclude . "accessoNegato.php");
            exit;
        }
    }

    // Unisce i parametri della querystring corrente con $overrides (i valori
    // di $overrides vincono in caso di conflitto) e restituisce una stringa
    // "?chiave=valore&..." pronta per un href. Usata per non perdere
    // ricerca/ordinamento/filtri quando si cambia pagina o filtro.
    public static function BuildQuery(array $overrides): string
    {
        $params = array_merge($_GET, $overrides);
        return "?" . http_build_query($params);
    }
}

Common::SetSession();
require_once(Common::$PathDataDb . "dbManager.php");
require_once(Common::$PathDataDb . "dbRepository.php");
require_once(Common::$PathClassiMaster . "masterBase.php");