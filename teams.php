<?php session_start();  // Start the session to store variable between pages  ?>
<?php include("php/file-upload.php"); ?>  <!-- Tutorial PHP 8 Upload & Store File/Image in MySQL  https://www.positronx.io/php-upload-store-file-image-in-mysql-database/ -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/settings.css">
    <link rel="stylesheet" href="css/snackbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<!--------------------------->

<body>
    <div id="snackbar">Snackbar text message</div> 

    <div id="divmenu">
        <!-- TEXTES: NOM, ASTUCE ET FERMETURE [X] -->
        <div id="btCloseMenu">
            <button id="closeTourne" type="button" class="btn-close btn-close-white" aria-label="Close" onclick="fermer()"></button>
        </div>




        <button id="btModifNom" onclick="changeName_MODIFTEAMDB(this, <?php echo htmlspecialchars ($_SESSION['nbcarTeam']); ?>, <?php echo htmlspecialchars ($_SESSION['team']['id']); ?>)"><?php echo htmlspecialchars( $_SESSION['team']['name'] ); ?></button>
        <!-- FORMULAIRE LOGO -->
        <div id='formContainer'>
            <form id = "formSettingsChangeLogo" action="" method="post" enctype="multipart/form-data" class="mb-1" accept="image/png, image/gif, image/jpeg">
                <!-- IMAGE -->
                <div class="user-image mb-3 text-center">
                    <div style="width: 160px; height: 160px; overflow: hidden; background: #cccccc; margin: 0 auto; border-radius: 10px;">
                        <img id="imgPlaceholder" src="<?php echo htmlspecialchars( $_SESSION['team']['logo'] ); ?>" class="figure-img img-fluid rounded" alt="">
                    </div>
                </div>
                <!-- <div id="logoEquipeModif_div">
                    <img id="logoEquipeModif" src="<!?php echo htmlspecialchars ($_SESSION['team']['logo']); ?>" alt="🖼️" onerror="this.onerror=null; this.src='img/logo/LogoHockey7.png'">
                </div> -->

                <!-- CHOISIR FICHIER -->
                <div class="custom-file">
                    <input type="hidden" name="teamId" value="<?php echo htmlspecialchars ($_SESSION['team']['id']); ?>" >
                    <input type="file" name="fileUpload" class="form-control" id="chooseFile">   <!-- custom-file-input -->
                </div>
                <!-- BOUTON IMPORT -->
                <button id="btn_ImportLogo" type="submit" name="submit" class="btn btn-primary d-none btn-block mt-4">  Charger le logo  </button>
            </form>

            <!-- Display response messages -->
            <?php if(!empty($resMessage)) {?>
                <div class="alert <?php echo $resMessage['status']?>">
                    <?php echo $resMessage['message']?>
                </div>
            <?php }?>
        </div>

        <span id="labelModifTeam">💡 Modifier le nom et le logo de l'équipe</span>

        <!-- 💥❌🚫❗⚠️☢️🛑➕ -->
        
        <!-- Split dropup button -->
        <div class="btn-group dropup">
        <button onclick="btDelTeam(this)" class='btn btn-danger' teamid=<?PHP echo htmlspecialchars ($_SESSION['team']['id']) ?> teamname=<?PHP echo htmlspecialchars ($_SESSION['team']['name']) ?>>
            Supprimer l'équipe
        <!-- <button type="button" class="btn btn-secondary"> -->
        </button>
        <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul id="teamlistdel" class="dropdown-menu">
            <!-- Dropdown menu links -->
            <?php 
                foreach( $all_teams as $team) {
                    echo "<li><center><button onclick='btDelTeam(this)' teamid=" .$team["id"]. " teamname=" .$team["name"]. " type='button' class='dropdown-item'>".$team["name"]."</button></li>";
                }
            ?>
        </ul>
        </div>


        <!-- CREATION / SUPPRESSION -->
        <button onclick="btAddTeam(<?PHP echo htmlspecialchars ($_SESSION['nbcarTeam']) ?>)" class='btn btn-secondary'>Créer une équipe</button>

        <div class="btn-group dropup-center dropup">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Choisir l'équipe favorite
            </button>
            <ul id="teamlistfav" class="dropdown-menu dropdown-menu-end">
                <li style="font-size:2em;"><center>🏠</li>  
                <li><hr class="dropdown-divider"></li>
                <?php 
                    // ☑✓✔✅√☒☐✕❎💯✗✘✖❌    
                    foreach( $all_teams as $team) {
                        if($team["fav"]) {
                            echo "<li><center><button type='button' class='dropdown-item' style='color:white;background-color:green;'>✓  ".$team["name"]."</button></li>";
                        } else {
                            echo "<li><center><button onclick='selectFavorite(this)' teamid=".$team["id"]." type='button' class='dropdown-item'>".$team["name"]."</button></li>";
                        }
                    }
                ?>
            </ul>
        </div>

        <!-- <form action="teams.php" method="post" id='formSettings' >
            <input type="hidden" name="nbcarTeam" value= <!?= $_SESSION['nbcarTeam'] ?> />
            <input type="hidden" name="team" value= <!?= $_SESSION['team']['id'] ?> />
            <input type="submit" name="submit" value="⚙️" id="btSettings" style="width:100%;height:100%;font-size: 3rem;">
        </form> -->

    </div>


    <script src="scripts/appels_server.js"></script>
    <script src="scripts/settings.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

        
    <script>
        function selectFavorite(btn) {
            id =    btn.getAttribute("teamid");
            name =  btn.innerText;
            DB_CHANGE_team_favorite(id, name);
        }
    </script>

</body>
</html>