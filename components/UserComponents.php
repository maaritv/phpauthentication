<?php

class UserComponents {

     function __construct() {
        
    }



function getUserForm(){
    return "<div>".  
            "<form method='post' action='users.php'>". 
            "<div class='form-group'>". 
            "<label for='name'>Etunimi *</label>". 
            "<input type='text' class='form-control' name='first_name' /></div>".
            "<label for='name'>Sukunimi *</label>". 
            "<input type='text' class='form-control' name='last_name' /></div>".
            "<div class='form-group'>". 
            "<label for='username'>Käyttäjätunnus *</label>". 
            "<input type='text' class='form-control' name='username' /> </div>".
            "<div class='form-group'>". 
            "<label for='price'>Salasana</label>". 
            "<input type='password' class='form-control' name='password' /> </div>".
            "<button type='submit' name='action' value='addNewUser' class='btn btn-primary'>Lisää käyttäjä</button>".
            "</form>".
        "</div>";
}

function getLoginComponent(){
    return "<div>".  
            "<form method='post' action='index.php'>". 
            "<div class='form-group'>". 
            "<label for='name'>Käyttäjätunnus *</label>". 
            "<input type='text' class='form-control' name='username' /></div>".
            "<label for='name'>Salasana *</label>". 
            "<input type='password' class='form-control' name='password' /></div>".
            "<button type='submit' name='login' class='btn btn-primary'>Login</button>".
            "</form>".
        "</div>";
}

function getNewUserButton(){
    return '<a style="margin: 19px;" href="add_user.php" class="btn btn-primary">
        Lisää uusi käyttäjä</a>';
}

function getLogoutButton(){
    return '<a style="margin: 19px;" href="index.php?logout=true" class="btn btn-primary">
        Logout</a>';
}

function getDeleteUserButton($userid){
    return '<form action="users.php" method="post">
            <input type="hidden" name="id" value="'.$userid.'">
            <button class="btn btn-danger" name="action" value="deleteUser" type="submit">Poista</button> 
            </form>';
}

function printUsersComponent($users){
    ##echo print_r($users);
    echo "<table class='table table-striped'>".
            "<thead>".
                "<tr>".
                    "<th>ID</th>".
                    "<th>Etunimi</th>".
                    "<th>Sukunimi</th>".
                    "<th>Käyttäjätunnus</th>".
                    "<th>Salasana</th>".
                    "<th colspan=3 style='vertical-align: center'>Toimenpiteet</th>".
                "</tr>".
            "</thead>".
            "<tbody>";
            foreach($users as $user){  
                ## Jokaisella käyttäjälla on oma painike korjauksen lisäämistä
                ## varten.
                $deleteUserButton = $this->getDeleteUserButton($user->id);

                echo "<tr>".
                    "<td>".$user->id."</td>".
                    "<td>".$user->first_name."</td>".
                    "<td>".$user->last_name."</td>".
                    "<td>".$user->username."</td>".
                    "<td>".$user->password."</td>".
                    "<td>".$deleteUserButton."</td>".
                "</tr>";
            };
                echo "</tbody></table>";
}
}