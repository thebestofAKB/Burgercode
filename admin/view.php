<?php
require_once("database.php");

if(!empty($_GET['id']))
{
    checkInput($_GET['id']);
}
    $db = Database::connect();
    
    $statement = $db->prepare("SELECT i.id, i.name, i.description, i.price, i.image, c.name AS category
                                FROM items i LEFT JOIN categories c ON i.category = c.id
                                WHERE i.id = ?");

 $statement->execute([$_GET['id']]);
 $item = $statement->fetch(PDO::FETCH_OBJ);
 Database::disconnect();

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
    <div class="container admin-view">
        <div class="row">
            <div class="col-sm-offset-1 col-sm-5">
               
                <h1>
                    <strong>Voir un item</strong>
                </h1>
                <br />
                <form>
                    <div class="form-group">
                        <label>Nom:</label><?= ' '.$item->name; ?>
                    </div>
                    <div class="form-group">
                        <label>Description:</label><?= ' '.$item->description; ?>
                    </div>
                    <div class="form-group">
                        <label>Prix:</label><?= ' '.number_format((float)$item->price,2, '.','') . ' €'; ?>
                    </div>
                    <div class="form-group">
                        <label>Categorie:</label><?= ' '.$item->category; ?>
                    </div>
                    <div class="form-group">
                        <label>Image:</label><?= ' '.$item->image; ?>
                    </div>
                </form>
                <div class="form-actions">
                    <a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> retour</a>
                </div>
            </div>
            
            <div class="col-sm-6 site">
                <div class="thumbnail">
                    <img src="<?= '../img/' . $item->image; ?>" class="image" alt="...">
                    <div class="price"><?= ' '.number_format((float)$item->price,2, '.','') . ' €'; ?></div>
                    <div class="caption">
                        <h4><?= ' '.$item->category; ?></h4>
                        <p><?= ' '.$item->description; ?></p>
                        <a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span> Commander </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>