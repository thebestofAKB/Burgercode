<?php
require_once("database.php");

$nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = "";

if(!empty($_POST))
{
    $name                  = checkInput($_POST['name']);
    $description           = checkInput($_POST['description']);
    $price                 = checkInput($_POST['price']);
    $category              = checkInput($_POST['category']);
    $image                 = checkInput($_FILES['image']['name']);
    $imagePath             = '../img/' . basename($image);
    $imageExtension        = pathinfo($imagePath, PATHINFO_EXTENSION);
    $isSuccess             = true;
    $isUploadSuccess       = false;
    
    if(empty($name))
    {
        $nameError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($description))
    {
        $descriptionError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($price))
    {
        $priceError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($category))
    {
        $categoryError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($image))
    {
        $imageError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    else
    {
        $isUploadSuccess = true;
        
        if($imageExtension != 'jpg' && $imageExtension != 'png' && $imageExtension != 'jpeg' && $imageExtension != 'gif')
        {
            $imageError = "Les fichiers autorises sont: .jpg, .png, .jpeg, .gif";
            $isUploadSuccess = false;
        }
        
        if(file_exists($imagePath))
        {
            $imageError = "Cette image existe deja";
            $isUploadSuccess = false;
        }
        
        if($_FILES['image']['size'] > 500000)
        {
            $imageError = "La taille du fichier ne doit pas depasser 500 KB";
            $isUploadSuccess = false;
        }
        
        if($isUploadSuccess)
        {
            if(!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath))
            {
                $imageError = "Une erreur s'est produite lors de l'upload de l'image";
                $isUploadSuccess = false;
            }
        }
    }
    
    if($isSuccess && $isUploadSuccess)
    {
        $db = Database::connect();
        
        $statement = $db->prepare("INSERT INTO items(name, description, price, category, image) VALUES(?, ?, ?, ?, ?)");
        $statement->execute([$name, $description, $price, $category, $image]);
        
        Database::disconnect();
        header("Location:index.php");
    }
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
                <strong>Ajouter un item</strong>
            </h1>
            <br />
            <form class="form" role="form" action="insert.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nom:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Votre nom" value="<?= $name; ?>">
                    <span class="help-inline"><?= $nameError; ?></span>
                </div>
                <div class="form-group">
                   <label for="description">Description:</label>
                    <input type="text" name="description" id="description" class="form-control" placeholder="Votre description" value="<?= $description; ?>">
                    <span class="help-inline"><?= $descriptionError; ?></span>
                </div>
                <div class="form-group">
                   <label for="price">Prix: (en euro)</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Votre prix" value="<?= $price; ?>">
                    <span class="help-inline"><?= $priceError; ?></span>
                </div>
                <div class="form-group">
                    <label for="category">Categories:</label>
                    <select name="category" id="category" class="form-control">
                       <option value="">Menus</option>
                        <?php 
                            
                        $db = Database::connect();
                        foreach($db->query("SELECT * FROM categories") as $row)
                        {
                            echo '<option value="'. $row['id'] .'">' . $row['name'] . '</option>';
                        }
                        Database::disconnect();
                        ?>
                    </select>
                    <span class="help-inline"><?= $categoryError; ?></span>
                </div>
                <div class="form-group">
                    <label for="image">Selectionner une image:</label>
                    <input type="file" name="image" id="image">
                    <span class="help-inline"><?= $imageError; ?></span>
                </div>
                <div class="form-actions">
                   <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Ajouter</button>
                    <a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> retour</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>