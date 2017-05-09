<?php
session_start();

function VerifierAdresseMail($mail){
$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
if(preg_match($Syntaxe,$mail)){
return true;
}else{
return false;
}
}


/* PetitClean($var,$lg) */
/* $var la varible à traiter */
/* la longueur de sortie */  

function PetitClean($var,$lg){
$var=strip_tags($var);
  /* troncature on va pas me poster un roman (-: */
  if(strlen($var)>$lg){
  $var = substr($var, 0, $lg);
  $last_space = strrpos($var, " ");
  $var = substr($var, 0, $last_space);
  }else{
  $lg=0;
  } 
return $var;
}
    
$error=NULL;

if(isset($_POST['nom']) && !empty($_POST['nom'])){
$nom=$_POST['nom'];$error=NULL;
//filtrage 
$nom=PetitClean($nom,30); /*30 caractères maxi*/
}else{
echo $error='<h3 align="center">Le nom est vide - <a href="javascript:history.back();">Retour au formulaire</a></h3>';exit;
}

if(isset($_POST['mail']) && !empty($_POST['mail'])){
$mail=$_POST['mail'];$error=NULL;$mail=htmlentities($mail);
//filtrage
$mail=PetitClean($mail,60);
}else{
echo $error='<h3 align="center">Le e-mail est vide - <a href="javascript:history.back();">Retour au formulaire</a></h3>';exit;
}

if(isset($_POST['objet']) && !empty($_POST['objet'])){
$objet=$_POST['objet'];$error=NULL;
//filtrage
$objet=PetitClean($objet,100);
}else{
echo $error='<h3 align="center">L\'objet est vide - <a href="javascript:history.back();">Retour au formulaire</a></h3>';exit;
}

if(isset($_POST['message']) && !empty($_POST['message'])){
$message=$_POST['message'];$error=NULL;
//filtrage
$message=PetitClean($message,300);
}else{
echo $error='<h3 align="center">Le message est vide - <a href="javascript:history.back();">Retour au formulaire</a></h3>';exit;
}

if(VerifierAdresseMail($mail)){
//echo 'mail ok';
}else{
echo $error='<h3 align="center">Votre adresse e-mail n\'est pas valide - <a href="javascript:history.back();">Retour au formulaire</a></h3>';exit;
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['code']) && !empty($_POST['code']) && $_POST['code']===$_SESSION['verif']){ 

/*un mail, un enregistrement mysql, une ouverture de fichier ... un traitement */

$destinataire="xxxxxxxxxxxxxxx@free.fr";  /*ICI LE MAIL QUI RECEPTIONNE*/
$subject=$objet;
$body=$message;

/*format du mail*/
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
/*ici on détermine l'expediteur et l'adresse de réponse*/
$headers .= "From: $nom <$mail>\r\nReply-to : $nom <$mail>\nX-Mailer:PHP";
/*tout est ok*/
    
    if (mail($destinataire,$subject,$body,$headers)){
    /*petite secu*/
    $message=NULL;
    $mail=NULL;
    $nom=NULL;
    $objet=NULL;
    $_POST=NULL;
    $_SESSION['verif']=NULL; /*anti double post*/
    $destinataire=NULL;
    echo '<h3 align="center">Votre message est envoyé, merci ! - <a href="javascript:history.back();">Retour au formulaire</a><br /></h3>';exit; 
    /* ou redirection header('Location: http://unsite.fr/merci.html');exit; ... */
    }else{
    /*petite secu*/
    $message=NULL;
    $mail=NULL;
    $nom=NULL;
    $objet=NULL;
    $_POST=NULL;
    $_SESSION['verif']=NULL;  /*anti double post*/
    $destinataire=NULL;
    echo '<h3 align="center">Désolé votre message n\'a pas pu être envoyé ! - <a href="javascript:history.back();">Retour au formulaire</a><br /></h3>';exit;
    /* ou redirection header('Location: http://unsite.fr/erreur.html');exit; ... */
    }

/*petite secu*/
$message=NULL;
$mail=NULL;
$nom=NULL;
$objet=NULL;
$_POST=NULL;
$destinataire=NULL;

} else {
echo $error='<h3 align="center">ERREUR SUR LE CODE DE SECURITE - <a href="javascript:history.back();">Retour au formulaire</a></h3>';exit;
}
?>