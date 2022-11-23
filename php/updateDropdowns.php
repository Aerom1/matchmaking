<?php 

// function displayTeams1($all_teams){
//     $HTML = '';
//     foreach( $all_teams as $team) {
//             $HTML .= "<div onclick='changerEquipe(this)' class='listeLogoTeam' teamid=" .$team["id"]. " teamname=" .$team["name"]. " > <div class='imgContainerListeLogoTeam'> <img src='" .$team["logo"]. "' class='imgListeLogoTeam' alt = '👨🏾‍🤝‍👨🏻' > </div> " .$team["name"]. " </div>";
//     }
//     return $HTML;
// }

function displayTeams2($all_teams){
    $HTML = '';
    foreach( $all_teams as $team) {

        if ( $team["fav"] ) {
            $HTML .= "<li><a href='index.php?teamid=" .$team["id"]. "'> <div class='imgContainerListeLogoTeam'> <img src='" .$team["logo"]. "' class='imgListeLogoTeam' alt = '👨🏾‍🤝‍👨🏻' > </div> " .$team["name"]. "</a></li>";
        } else {
            // $HTML .= "⭐
            $HTML .= "<li><a href='index.php?teamid=" .$team["id"]. "'> <div class='imgContainerListeLogoTeam'> <img src='" .$team["logo"]. "' class='imgListeLogoTeam' alt = '👨🏾‍🤝‍👨🏻' > </div> " .$team["name"]. "  ⭐</a></li>";
        }
    }
    return $HTML;
}

function updateDropdownHTMLfav($all_teams){
    $dropdownHTMLfav = '<li style="font-size:2em;text-align:center;">🏠</li><li><hr class="dropdown-divider"></li>';
    foreach( $all_teams as $team) {
        if ( $team["fav"] ) {
            $dropdownHTMLfav .= "<li><button type='button' class='dropdown-item' style='color:white;background-color:green;text-align:center;'>✓  ".$team["name"]."</button></li>";
        } else {
            $dropdownHTMLfav .=  "<li><button onclick='selectFavorite(this)' teamid=".$team["id"]." type='button' class='dropdown-item' style='text-align:center;' >".$team["name"]."</button></li>";
        }
    }
    return $dropdownHTMLfav;
}

// ☑✓✔✅√☒☐✕❎💯✗✘✖❌    

function updateDropdownHTMLdel($all_teams, $name){
    $dropdownHTMLdel = '<li style="font-size:2em;text-align:center;">❌</li><li><hr class="dropdown-divider"></li>';

    foreach( $all_teams as $team) {    
        if ( $team["name"] == $name ) {
            $dropdownHTMLdel .= "<li><button onclick='btDelTeam(this, 1)' teamid=" .$team["id"]. " teamname=" .$team["name"]. " type='button' class='dropdown-item' style='text-align:center;' >".$team["name"]."</button></li>";
        } else {
            $dropdownHTMLdel .= "<li><button onclick='btDelTeam(this, 0)' teamid=" .$team["id"]. " teamname=" .$team["name"]. " type='button' class='dropdown-item' style='text-align:center;' >".$team["name"]."</button></li>";
        }
    }
    return $dropdownHTMLdel;
}

?>