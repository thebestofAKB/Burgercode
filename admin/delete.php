<?php
require_once("database.php");

if(!empty($_GET['id']))
{
    $id = checkInput($_GET['id']);
}

if(!empty($_POST))
{
    $id = checkInput($_POST['id']);
    $db = Database::connect();
    $statement = $db->prepare("DELETE FROM items WHERE id = ?");
    $statement->execute([$id]);
    Database::disconnect();
    header("Location: index.php");
}

function checkInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    
    return $data;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
   
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    
    <link rel="stylesheet" href="../libs/css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="../libs/css/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="../libs/css/styles.css" />
    
    <script type="text/javascript" src="../libs/js/jquery.min.js"></script>
    <script type="text/javascript" src="../libs/js/bootstrap.min.js"></script>
    
    <title>BurgerCode</title>
    
</head>
<body>
    <h1 class="text-logo">
        <span class="glyphicon glyphicon-cutlery"></span> Burger Code <span class="glyphicon glyphicon-cutlery"></span>
    </h1>
    <div class="container admin">
        <div class="row">
            <h1>
                <strong>Supprimer un item</strong>
            </h1>
            <br />
            <form class="form" role="form" action="delete.php" method="post">
                <input type="hidden" name="id" value="<?= $id; ?>" />
                <p class="alert alert-danger">Etes vous sur de vouloir supprimer cet item ?</p>
                <div class="form-actions">
                   <button type="submit" class="btn btn-danger">Oui</button>
                    <a href="index.php" class="btn btn-default">Non</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>