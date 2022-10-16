
<?php
    function testBDD($conn) {

        // $mysqli = new mysqli('127.0.0.1', $user, $password, $database, $port);
        //     if ($mysqli->connect_error) {
        //         die('Connect Error (' . $mysqli->connect_errno . ') '
        //                 . $mysqli->connect_error);
        //     }
        //     echo '<p>Connection OK '. $mysqli->host_info.'</p>';
        //     echo '<p>Server '.$mysqli->server_info.'</p>';
        //     echo '<p>Initial charset: '.$mysqli->character_set_name().'</p>';
        //     $mysqli->close();
        
        $msg ="";
        $query = "SELECT * FROM tbplayer LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // debug_to_console($row);
            // debug_to_console($row["xp"]);
            $msg = "CONNEXION OK ! Name of first player: " . $row["name"];
        } else {
            debug_to_console("PAS DE RESULTAT");
            debug_to_console($result);
            $msg = "CONNEXION INCORRECTE OU TABLE VIDE";
        }
        debug_to_console($msg);
    }

    function connectToDB() {
        $servername = "localhost"; // 192.168.1.40 localhost
        $username = "root";
        $password = "";
        $conn = "dbmatch";
        $port = "3306";
        $conn = new mysqli($servername, $username, $password, $conn) or die("Connect failed: %s\n". $conn -> error);
        $conn->set_charset("utf8");
        debug_to_console("Connexion bdd OK");
        return $conn;
    }
    
    function CloseCon($conn) {
        $conn -> close(); 
    }

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);	
        echo "<script>console.log('==============================PHP Debug: " . $output . "' );</script>";
    }

    class classTeam {
        // property declaration
        public $_id;
        public $_name;
        public $_logo;
        public $_fav;
        
        // method declaration
        function __construct($id, $name, $logo, $fav){
            $this->_id = $id;
            $this->_name = $name;
            $this->_logo = $logo;
            $this->_fav = $fav;
            // debug_to_console("Team has been constructed: ".$name); 
        }
        function getName(){
            return $this->_name;
        }
        // public function displayVar() {
        //     echo $this->var;
        // }
    }

    class classPlayer {
        // property declaration
        public $_id;
        public $_name;
        public $_xp;
        public $_absent;
        
        // method declaration
        function __construct($id, $name, $xp, $absent){
            $this->_id = $id;
            $this->_name = $name;
            $this->_xp = $xp;
            $this->_absent = $absent;
            // debug_to_console("Player has been constructed: ".$name); 
        }
    }
    
        // class classTeam {
		// 	public _id;
		// 	public _name;
		// 	public _logo;
		// 	public _fav;
		// 	constructor(id, name, logo, fav){
		// 		this._id = id;
		// 		this._name = name;
		// 		this._logo = logo;
		// 		this._fav = fav;
		// 	}
		// };

		// class classPlayer {
		// 	public _id;
		// 	public _name;
		// 	public _xp;
		// 	public _absent;
		// 	constructor(id, name, xp, absent){
		// 		this._id = id;
		// 		this._name = name;
		// 		this._xp = xp;
		// 		this._absent = absent;
		// 	}
		// };

    function getTable($conn, $req){
        $data = $conn->query($req)->fetch_all( MYSQLI_ASSOC );
        return json_encode($data,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
    }

    function getTable_OLD($conn, $table){
        $cpt=0;

        // $res = $conn->query("SELECT * FROM '$table';");
        // while ($row = $res->fetch_assoc()) { // pour éviter une boucle infinie faite au tout début du codage ^^
        //         $cpt++;
        //         $rows[]=$row;
        // }
        // debug_to_console("getTable('$table') : ".$cpt." éléments trouvés");
        // return $res;
        
        //write query
        $res = $conn->query("SELECT * FROM '$table';");
        $t = [];
        $cpt = 0;
        while ($row = $res->fetch_assoc()) {
            array_push($t, $row);
        }
        debug_to_console("getTeams() : ".$cpt." équipes trouvés");
        return $t;

        
        // $rows = $conn->getAll("SELECT * FROM '$table';");
        // $rows = $result->fetch_all(MYSQLI_ASSOC);

        // $sqlriz = "SELECT * FROM '$table';";
        // $Rslt = mysqli_query($conn,$sqlriz);
        // while($r=$Rslt->fetch_assoc())
        // {
        //     $cpt++;
        //     $res[]=$r;
        // }
        

        // // $res =    $conn->query("SELECT * FROM ".$table);
        // for (
        //     $set = array (); 
        //     $row = $result->fetch_assoc();
        //     $set[] = $row
        // );
        // return $set;
    }

    function getTeams($conn){
        //write query
        $res = $conn->query("SELECT * FROM tbteam");
        $teams = [];
        $cpt = 0;
        while ($row = $res->fetch_assoc() and $cpt++<100) { // pour éviter une boucle infinie faite au tout début du codage ^^
            $team = new classTeam(
                $row['id'], 
                $row['name'], 
                $row['logo'], 
                $row['fav']);
            array_push($teams, $team);
        }
        debug_to_console("getTeams() : ".count($teams)." équipes trouvés");
        return $teams;
    }

    function getPlayers($conn){
        //write query
        $res = $conn->query("SELECT * FROM tbplayer");
        $players = [];
        $cpt = 0;
        while ($row = $res->fetch_assoc() and $cpt++<100) { // pour éviter une boucle infinie faite au tout début du codage ^^
            $player = new classPlayer(
                $row['team'], 
                $row['name'], 
                $row['xp'], 
                $row['absent']);
            array_push($players, $player);
        }
        debug_to_console("getPlayers() : ".count($players)." joueurs trouvés");
        return $players;
    }

    function getPlayers_KO($conn){
        // debug_to_console("Enter getPlayers()");
        //write query
        $res = $conn->query("SELECT * FROM tbplayer");
        $players = [];
        $cpt = 0;
        while ($row = $res->fetch_assoc() and $cpt++<60) { // pour éviter une boucle infinie faite au tout début du codage ^^
            $player = new classPlayer(
                $row['team'], 
                $row['name'], 
                $row['xp'], 
                $row['absent']);
            array_push($players, $player);
        }
        debug_to_console("getPlayers() : ".$cpt." joueurs trouvés");
        return $players;
    }
    
    
    function getFavoriteTeam($teams){
        foreach($teams as $t){
            $fav = $t;
            if ($fav->_fav == 1){
                break ;
            }
        }
        debug_to_console('getFavoriteTeam() : fav team: ' . $fav->_name);
        return $fav;
    }


    function getPlayersFromTeam($conn, $teamid){
        // debug_to_console("Enter getPlayers()");
        //write query
        $res = $conn->query("SELECT * FROM tbplayer WHERE team=".$teamid);
        $players = [];
        $cpt = 0;
        while ($row = $res->fetch_assoc() and $cpt<60) {
            if ($cpt++ > 500){ break; } // pour éviter une boucle infinie faite au tout début du codage ^^
            $player_c = new classPlayer($row['team'], 
            $row['name'], 
            $row['xp'], 
            $row['absent']);
            array_push($players, $player_c);
        }
        // debug_to_console("getPlayers() : ".$cpt." joueurs trouvés");
        return $players;
    }
    
    function changeTeam_PHP($teams, $team) {
        echo 'console.log("changeTeam_PHP()");';
        echo 'console.log("Ancienne équipe: ' . $team->_name . '");';

        for ($i = 0; $i < count($teams); $i++) {
            if ($team == $teams[$i]) {
                if ($i == count($teams)-1) {
                    $newTeam = $teams[0];
                } else {
                    $newTeam = $teams[$i+1];
                }
                echo 'console.log("Nouvelle équipe: ' . $newTeam->_name . '");';
                // debug_to_console("Nouvelle équipe: ".$team->_name);
                return $newTeam;
            }
        }
    }


?>
