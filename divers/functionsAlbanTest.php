<?
/*
* $db = new MysqlCnx($_dbHost, $_dbUser, $_dbPass, $_dbBase);
* --------------
* requete : $req
* ressource : $res
* --------------
* [ QueryOne ]
* string $db->QueryOne( $req )
* --------------
* [ QueryRow ]
* array $db->QueryRow( $req )
* --------------
* [ Query ]
* ressource $db->Query( $req );
* --------------
* [ FetchRow ]
* array(assoc) $res->FetchRow()
* --------------
* [ FetchArray ]
* array(num) $res->FetchArray()
* --------------
* [ NbRows ]
* int $res->NbRows()
* --------------
* $db->Disconnect();
*/

require("fonctionsAlban.php");
$_dbHost = "localhost"; // 192.168.1.40 localhost
$_dbUser = "root";
$_dbPass = "";
$_dbBase = "dbmatch2";
$port = "3306";

$db = new MysqlCnx($_dbHost, $_dbUser, $_dbPass, $_dbBase);
$db->set_charset("utf8");

function getTeams($req){
    return $db->QueryRow( $req );
}
function getPlayers($req){
    return $db->QueryRow( $req );
}





?>

