<?php
require 'inclureClasse.php';
require_once 'config.php';
$message1 = "";
// Vérifier si le formulaire a été soumis
if(isset($_POST['inscription'])) {
    // Créer une instance de la classe User
    $user = new User();
    
    // Validation du nom
    if (!empty($_POST['nom']) && preg_match('/^[a-zA-Z]+$/', $_POST['nom'])) {
        $user->setName($_POST['nom']);
    } else {
        $user->setErr(User::NOM_INVALIDE);
    }
    
    // Validation du prénom
    if (!empty($_POST['prenom']) && preg_match('/^[a-zA-Z]+$/', $_POST['prenom'])) {
        $user->setFirst_name($_POST['prenom']);
    } else {
        $user->setErr(User::PRENOM_INVALIDE);
    }
    
    // Validation de l'email
    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $user->setEmail($_POST['email']);
    } else {
        $user->setErr(User::EMAIL_INVALIDE);
    }
    
    // Validation du numéro de téléphone
    if (!empty($_POST['tel']) && preg_match('/^\d{8,12}$/', $_POST['tel'])) {
        $user->setTel($_POST['tel']);
    } else {
        $user->setErr(User::TELEPHONE_INVALIDE);
    }
    
    // Validation du mot de passe
    if (!empty($_POST['mots_de_passe']) && $_POST['mots_de_passe'] === $_POST['mots_de_passe2']) {
        $user->setPassword($_POST['mots_de_passe']);
    } else {
        $user->setErr(User::MOT_DE_PASSE_INVALIDE);
    }
    
    // Vérifier si l'utilisateur est valide en utilisant la méthode isUserValide()
    if($user->isUserValide()){
      header("location: login.php");
    } else {
        // Récupérer les erreurs de validation
        $erreurs = $user->getErr();
        
        // Afficher les erreurs
        foreach($erreurs as $erreur) {
            switch($erreur) {
                case User::NOM_INVALIDE:
                    echo "Le nom est invalide.";
                    break;
                case User::PRENOM_INVALIDE:
                    echo "Le prénom est invalide.";
                    break;
                case User::EMAIL_INVALIDE:
                    echo "L'adresse email est invalide.";
                    break;
                case User::MOT_DE_PASSE_INVALIDE:
                    echo "Les mots de passe ne correspondent pas ou sont invalides.";
                    break;
                case User::TELEPHONE_INVALIDE:
                    echo "Le numéro de téléphone est invalide.";
                    break;
                // Ajouter d'autres cas pour d'autres types d'erreurs si nécessaire
                default:
                    echo "Une erreur inconnue s'est produite.";
            }
        }
    }

    //une function qui permet de cripter le mots de passe 
    $password = password_hash($_POST['mots_de_pase'], PASSWORD_DEFAULT);
    //requêtte ppour enregistrer l'utilisateur dans la base donnée 

    $req = $connexion->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $req->bindValue(':email', $_POST['email']);
    $req->execute();
    $result = $req->fetch();
    if($result)
        {
            $message1 = "un compt existe déja avec l'adresse email que vous avez saisie";
        }
        else{
            $sql = $connexion->prepare("INSERT INTO utilisateur(nom, prenom, email, telephone, mot_de_passe) VALUES (:nom, :prenom, :email, :telephone, :mot_de_passe)");
            $sql->bindValue(':nom', $_POST['nom']);
            $sql->bindValue(':prenom', $_POST['prenom']);
            $sql->bindValue(':email', $_POST['email']);
            $sql->bindValue(':telephone', $_POST['tel']);
            $sql->bindValue(':mot_de_passe',$_POST['mots_de_passe']);
    //executer la requette

    $sql->execute();
    $message = "felicitation inscription marche";
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles/inscription.css">
</head>
<body>
<div class="container register">
    <h1><?php echo $message1; ?></h1>
    <div class="row">
        <div class="col-md-3 register-left">
            <img src="images/alpha.svg" alt="error"/>
            <h3>Welcome.</h3>
            <a href="login.php"><input type="submit" name="" value="Login"/></a><br/>
        </div>
        <div class="col-md-9 register-right">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <h3 class="register-heading">S'enregistrer</h3>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" role="form">
                        <div class="row register-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control" placeholder="Nom *" value="" name="nom"/>
                                </div>
                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" class="form-control" placeholder="Prénom *" value="" name="prenom"/>
                                </div>
                                <div class="form-group">
                                    <label for="mots_de_pase">Mot de passe</label>
                                    <input type="password" class="form-control" placeholder="Mot de passe *" value="" name="mots_de_passe"/>
                                </div>
                                <div class="form-group">
                                    <label for="mots_de_passe2">Confirmer mot de passe</label>
                                    <input type="password" class="form-control"  placeholder="Confirmer mot de passe *" value="" name="mots_de_passe2"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" placeholder="Email *" value="" name="email"/>
                                </div>
                                <div class="form-group">
                                    <label for="tel">Téléphone</label>
                                    <input type="text" class="form-control" minlength="8" maxlength="12" placeholder="Numéro de téléphone *" value="" name="tel"/>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btnRegister"  value="S'inscrire" name="inscription"/>
                                </div>
                            </div>
                        </div>
                    </form>
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
