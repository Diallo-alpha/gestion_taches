<?php
/* Inclure le fichier config */
require_once "config.php";

// Initialiser le message d'erreur
$message = '';

// Vérifier si une erreur est présente dans l'URL
if(isset($_GET['error'])) {
    $error = $_GET['error'];
    switch($error) {
        case 'veuillez_saisir_votre_email_et_mot_de_passe':
            $message = "Veuillez saisir votre email et mot de passe.";
            break;
        case 'mot_de_passe_incorrect':
            $message = "Mot de passe incorrect.";
            break;
        case 'email_incorrect':
            $message = "Email incorrect.";
            break;
        default:
            $message = "";
    }
}

// Vérifier si le formulaire a été soumis
if(isset($_POST['connexion'])) {
    // Récupérer les données du formulaire
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';

    // Vérifier si les champs email et mot_de_passe ne sont pas vides
    if(empty($email) || empty($mot_de_passe)) {
        header('location: login.php?error=veuillez_saisir_votre_email_et_mot_de_passe');
        exit;
    }

    // Préparer et exécuter la requête SQL de vérification
    $sql = "SELECT * FROM utilisateur WHERE email = :email";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultat) {
        // Utilisateur trouvé, vérifier le mot de passe
        if(password_verify($mot_de_passe, $resultat['mot_de_passe'])) {
            // Mot de passe correct, redirection vers une page de succès
            header('Location: login_success.php'); 
            exit;
        } else {
            // Mot de passe incorrect
            header('location: login.php?error=mot_de_passe_incorrect');
            exit;
        }
    } else {
        // Utilisateur non trouvé, rediriger avec un message d'erreur
        header('location: login.php?error=email_incorrect');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles/login.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<section class="login-block">
    <div class="container">
        <div class="row">
            <div class="col-md-4 login-sec">
                <h2 class="text-center">Connexion</h2>
                <form class="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" role="form">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="demba.ndiakhate@exemple.com">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="text-uppercase">Password</label>
                        <input type="password" name="mot_de_passe" class="form-control" placeholder="">
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input">
                            <small>Remember Me</small>
                        </label>
                        <button type="submit" name="connexion" class="btn btn-login float-right">Se connecter</button>
                    </div>
                </form>
                <?php if($message != ''): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-md-8 banner-sec">
                <!-- Votre carousel ici -->
            </div>
        </div>
    </div>
</section>
</body>
</html>
