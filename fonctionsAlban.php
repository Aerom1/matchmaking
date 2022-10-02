<?php
/*
* Utilisation des classes mysql
* --------------
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

class MysqlResult {
var $res;

// CONSTRUCTEUR
function __construct( $pCnx, $pSql ) {
$this->res = @mysqli_query($pCnx, $pSql);
if ( ! $this->res ) {
            $e = date('c')." - ".$_SESSION['s_pharma_lib']."(".$_SESSION['s_pharma_id'].") - "
                 .GetHttp("onglet")."_".GetHttp("page")."\n".mysqli_error($pCnx)."\n".$pSql."\n\n";
@file_put_contents('log_error_sql.txt', $e, FILE_APPEND);
            die ("<b>ERREUR : ".mysqli_error($pCnx)."</b><br/>" . $pSql);
}
}

// METHODES

//-----------------------------------------------------------------------
function FetchRow( $pOffset = 1 ) {
//-----------------------------------------------------------------------
while( $pOffset > 0 ) {
// pOffset = nombre de ligne -1 à ignorer
if ( ! $row = @mysqli_fetch_assoc($this->res) ) {
return false;
}
$pOffset--;
}
return $row; // $row['col'] => value
}

//-----------------------------------------------------------------------
function FetchArray( $pOffset = 1 ) {
//-----------------------------------------------------------------------
while( $pOffset > 0 ) {
// pOffset = nombre de ligne -1 à ignorer
if ( ! $row = @mysqli_fetch_row($this->res) ) {
return false;
}
$pOffset--;
}
return $row; // $row[0] => value
}

//-----------------------------------------------------------------------
function NbRows() {
//-----------------------------------------------------------------------
if ( ! $nb = @mysqli_num_rows($this->res) ) {
return false;
}
return $nb;
}
}


class MysqlCnx
{
var $cnx;

// CONSTRUCTEUR
function __construct( $pHost, $pUser, $pPw, $pDataBase ) {

$this->cnx = @mysqli_connect($pHost, $pUser, $pPw);
   
if ( ! $this->cnx ) {
die ('<table height="100%" width="100%"><tr><td align="center">'
    .'<img src="'.(($GLOBALS["_GRP_ID"])?'../':'').'images/logo_ico.png"/><br/>'
    .'<h4>&nbsp;L\'application est actuellement indisponible</h4>'
    .'<br/><br/><br/><br/></td></tr></table>');
}

if ( ! mysqli_select_db($this->cnx, $pDataBase) ) {
die ('<table height="100%" width="100%"><tr><td align="center">'
    .'<img src="'.(($GLOBALS["_GRP_ID"])?'../':'').'images/logo_ico.png"/><br/>'
    .'<h4>Base de données inaccessible,<br/>veuillez contacter l\'administrateur</h4>'
    .'<br/><br/><br/><br/></td></tr></table>');
}
}
   
// METHODES

//-----------------------------------------------------------------------
function Query( $pSql ) {
//-----------------------------------------------------------------------
if ( $this->cnx == null ) {
return false;
  }
$result = New MysqlResult( $this->cnx, $pSql );
return $result;
}
     
//-----------------------------------------------------------------------
function QueryRow( $pSql ) {
//-----------------------------------------------------------------------
if ( $this->cnx == null ) {
return false;
}
$req = $this->Query( $pSql );
return $req->FetchRow();
}

//-----------------------------------------------------------------------
function QueryOne( $pSql ) {
//-----------------------------------------------------------------------
if ( $this->cnx == null ) {
return false;
}
$req = $this->Query( $pSql );
$res = $req->FetchArray();
if ( is_array($res) ) {
return $res[0];
} else {
return false;
}
}

//-----------------------------------------------------------------------
function Disconnect() {
//-----------------------------------------------------------------------
if ( $this->cnx == null ) {
return false;
} else {
return @mysqli_close($this->cnx);
}
}
}
?>