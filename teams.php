<?php session_start();  // Start the session to store variable between pages  ?>
<?php include("php/file-upload.php"); ?>  <!-- Tutorial PHP 8 Upload & Store File/Image in MySQL  https://www.positronx.io/php-upload-store-file-image-in-mysql-database/ -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/teams.css">
    <link rel="stylesheet" href="css/snackbar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>

    <div id="snackbar">Snackbar text message</div> 

    <div id="divmenu">
        <!-- TEXTES: NOM, ASTUCE ET FERMETURE [X] -->
        <div id="btCloseMenu"><span id="closeTourne" onclick="fermer()">╳</span></div>
        <button id="btModifNom" onclick="changeName_MODIFTEAMDB(this)"><?php echo htmlspecialchars ($_SESSION['team']['name']); ?></button>
        <span id="labelModifTeam">💡 Vous pouvez modifier le nom<br>et le logo de votre équipe</span>
        <!-- FORMULAIRE LOGO -->
        <form id = "formSettingsChangeLogo" action="" method="post" enctype="multipart/form-data" class="mb-3" accept="image/png, image/gif, image/jpeg">
            <!-- IMAGE -->
            <div class="user-image mb-3 text-center">
                <div style="width: 160px; height: 160px; overflow: hidden; background: #cccccc; margin: 0 auto; border-radius: 10px;">
                    <img id="imgPlaceholder" style="max-width:100%; height:auto; border: 1px dashed gray; " src="<?php echo htmlspecialchars( $_SESSION['team']['logo'] ); ?>" class="figure-img img-fluid rounded" alt="">
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

        <!-- CREATION / SUPPRESSION -->
        <button onclick="btDelTeam()" class='btn btn-danger'>🛑 Supprimer l'équipe</button>
        <button onclick="btAddTeam()" class='btn btn-warning'>➕ Créer une équipe</button>
        <!-- <form action="teams.php" method="post" id='formSettings' >
            <input type="hidden" name="nbcarTeam" value= <!?= $_SESSION['nbcarTeam'] ?> />
            <input type="hidden" name="team" value= <!?= $_SESSION['team']['id'] ?> />
            <input type="submit" name="submit" value="⚙️" id="btSettings" style="width:100%;height:100%;font-size: 3rem;">
        </form> -->

    </div>

    <script src="scripts/appels_server.js"></script>
    <script> 

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                document.getElementById("imgPlaceholder").setAttribute('src', e.target.result)
                // document.getElementById("logoEquipeModif").setAttribute('src', e.target.result)
                // $('#imgPlaceholder').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }
        
        document.getElementById("chooseFile").onchange = function () {
                readURL(this);
                document.getElementById("btn_ImportLogo").classList.remove('d-none')    // Afficher le bouton de validation
        }

        function btAddTeam(){
            var name = prompt("Nom de la nouvelle équipe ?")
            if (name == "" || name == null) { return }
            if (name.length > <?= $_SESSION['nbcarTeam'] ?> ) {
                snackbar('ℹ️ Le nom ne doit pas dépasser <?= $_SESSION['nbcarTeam'] ?> caractères', 'orange')
                return;
            }
            DB_createTeam(name);
        }

        function btDelTeam(){
            if (confirm("DEBUG : SUPPR EQUIPE 99 UNIQUEMENT !!\nConfirmer la suppression de l'équipe entière ?\nCette action est irréversible !")) {
                DB_deleteTeam('99',"DEBUG=eq99");
            }
        }

        // $("#chooseFile").change(function () {
        //     readURL(this);
        // });
  
        function fermer() {
            document.getElementById("btCloseMenu").classList.add("animer")
            window.location.href = 'index.php';
        }


        function changeName_MODIFTEAMDB(e){
            console.log(e);
            newname = prompt('Nouveau nom ?',e.innerText);
            if (!newname) { return }
            if (newname.length > <?= $_SESSION['nbcarTeam'] ?> ) {
                snackbar('ℹ️ Le nom ne doit pas dépasser <?= $_SESSION['nbcarTeam'] ?> caractères', 'orange');
                return;
            }

            e.innerText = newname;
            // id = document.getElementById('team').getAttribute('name');
            id = <?php echo htmlspecialchars ($_SESSION['team']['id']); ?>;
            DB_changeTeamNAME(id, newname);
            // document.getElementById('team').innerText = newname;

            e.parentNode.parentNode.name = newname; // Nom affiché dans le menu
            console.log(e.parentNode.parentNode.children[1]);
            e.parentNode.parentNode.children[1].innerText = newname; // Nom affiché dans l'écran principale    
        }

        function snackbar_DB (response) {
            var text = response.result;
            var color = response.success ? 'rgb(0,255,0)' : 'orange';
            snackbar(text, color)
        }
        function snackbar (text, color='white', size = 1) {
            var x = document.getElementById("snackbar"); // Get the snackbar DIV
                x.className = "show"; // Add the "show" class to DIV
                x.innerHTML =text;
                x.style.fontSize = size+"em"
                x.style.color = color;
                setTimeout(function(){  x.className = x.className.replace("show", ""); 	}, 3000); // After 3 seconds, remove the show class from DIV
        }

    </script>
    
</body>
</html>