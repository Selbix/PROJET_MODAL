<?php
$form_values_valid = false;
$form_values_valid=false;
 

if (isset($_POST["login"])) $login = $_POST["login"];
else $login = "";

if (isset($_POST["email"])) $email = $_POST["email"];
else $email = "";

if (isset($_POST["promotion"])) $promotion = $_POST["promotion"];
else $promotion = "";

if (isset($_POST["nom"])) $nom = $_POST["nom"];
else $nom = "";

if (isset($_POST["prenom"])) $prenom = $_POST["prenom"];
else $prenom = "";

if (isset($_POST["feuille"])) $feuille = $_POST["feuille"];
else $feuille = "";

if (isset($_POST["naissance"])) $naissance = $_POST["naissance"];
else $naissance = "";
if (!$form_values_valid) {
    echo "<form action='index.php?page=content_register' method='post' oninput='up2.setCustomValidity(up2.value != up.value ? \"Les mots de passe diffèrent.\" : \"\")'>";
    echo "<p>";
    echo "<label for='login'>login:</label>";
    echo "<input id='login' type='text' required name='login' value='$login' />";
    echo "</p>";
    echo "<p>";
    echo "<label for = 'email'>email:</label>";
    echo "<input id='email' type='email' required name='email' value='$email' />";
    echo "</p>";
    echo "<p>";
    echo "<label for='password1'>Password:</label>";
    echo "<input id='password1' type='password' required name='up'>";
    echo "</p>";
    echo "<p>";
    echo "<label for='password2'>Confirm password:</label>";
    echo "<input id='password2' type='password' name='up2'>";
    echo "</p>";
    
    
    echo "<p>";
    echo "<label for='promotion'>promotion:</label>";
    echo "<input id='promotion' type='number'  name='promotion' value='$promotion'>";
    echo "</p>";

    echo "<p>";
    echo "<label for='nom'>nom:</label>";
    echo "<input id='nom' type='text' required name='nom' value='$nom'>";
    echo "</p>";

    echo "<p>";
    echo "<label for='prenom'>prenom:</label>";
    echo "<input id='prenom' type='text' required name='prenom' value='$prenom'>";
    echo "</p>";

    echo "<p>";
    echo "<label for='feuille'>feuille de styles (.css):</label>";
    echo "<input id='feuille' type='text' required name='feuille' value='$feuille'>";
    echo "</p>";
    echo "<p>";
    echo "<label for='naissance'>date de naissance:</label>";
    echo "<input id='naissance' type='date' required name='naissance' value='$naissance'>";
    echo "</p>";

    echo "<input type='submit' value='Create account'>";
    echo "</form>";
}
if(isset($_POST["login"]) && $_POST["login"] != "" &&
   isset($_POST["email"]) && $_POST["email"] != "" &&
   isset($_POST["password1"]) && $_POST["password1" != ""]
   && isset($_POST["password2"]) && $_POST["password2" != ""]
   && isset($_POST["promotion"])
   && isset($_POST["nom"]) && $_POST["nom" != ""]
   && isset($_POST["prenom"]) && $_POST["prenom" != ""]
   && isset($_POST["feuille"]) && $_POST["feuille" != ""]
   && isset($_POST["naissance"]) && $_POST["naissance" != ""]
   && isset($_POST["password2"]) && $_POST["password2" != ""]) {  // tous les champs requis cités ici
  // code de traitement, à écrire maintenant
  // si le traitement réussit, on passe $form_value_valid à true
  echo "CAMARCHE";
  if(Utilisateur::getUtilisateur($dbh, $_POST["login"]) == NULL && $_POST["password1"] == $_POST["password2"]){
    Utilisateur::insererUtilisateur($dbh, $_POST["login"],$_POST["password1"],$_POST["nom"],$_POST["prenom"],$_POST["promotion"],$_POST["naissance"],$_POST["email"],$_POST["feuille"]);
    $form_values_valid = true;
  }
  else{
    echo "il est déjà dans la base";
  }
}
?>