<?php
require_once("database.php");

if(!empty($_GET['id']))
{
    $id = checkInput($_GET['id']);
}

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
        $isImageUpdated = false;
    }
    else
    {
        $isImageUpdated = true;
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
    
    if(($isSuccess && $isImageUpdated && $isUploadSuccess) || ($isSuccess && !$isImageUpdated))
    {
        $db = Database::connect();
        
        if($isImageUpdated)
        {
            $statement = $db->prepare("UPDATE items SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?");
            $statement->execute([$name, $description, $price, $category, $image, $id]);
        }
        else
        {
            $statement = $db->prepare("UPDATE items SET name = ?, description = ?, price = ?, category = ? WHERE id = ?");
            $statement->execute([$name, $description, $price, $category, $id]);
        }
        
        Database::disconnect();
        header("Location:index.php");
    }
    else if($isImageUpdated && !$isUploadSuccess)
    {
        $db = Database::connect();
        $statement = $db->prepare("SELECT image FROM items WHERE id = ?");
        $statement->execute([$id]);
        $item = $statement->fetch(PDO::FETCH_OBJ);
        $image = $item->image;
        Database::disconnect();
    }
}
else
{
    $db = Database::connect();
    
    $statement = $db->prepare("SELECT * FROM items WHERE id = ?");
    $statement->execute([$id]);
    $item = $statement->fetch(PDO::FETCH_OBJ);
    
    $name        = $item->name;
    $description = $item->description;
    $price       = $item->price;
    $category    = $item->category;
    $image       = $item->image;
    
    Database::disconnect();
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
                <strong>Modifier un item</strong>
            </h1>
            <br />
            
            <div class="col-sm-6">
                <form class="form" role="form" action="<?= 'update.php?id=' . $id; ?>" method="post" enctype="multipart/form-data">
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
                    <input type="number" name="price" id="price" class="form-control" placeholder="Votre prix" value="<?= $price; ?>">
                    <span class="help-inline"><?= $priceError; ?></span>
                </div>
                <div class="form-group">
                    <label for="category">Categories:</label>
                    <select name="category" id="category" class="form-control">
                        <?php 
                            $db = Database::connect();
                        foreach($db->query("SELECT * FROM categories") as $row)
                        {
                            if($row['id'] == $category)
                                echo '<option selected="selected" value="'. $row['id'] .'">' . $row['name'] . '</option>';
                            else
                                echo '<option value="'. $row['id'] .'">' . $row['name'] . '</option>';
                        }
                        Database::disconnect();
                        ?>
                    </select>
                    <span class="help-inline"><?= $categoryError; ?></span>
                </div>
                <div class="form-group">
                   <label for="img">Image:</label>
                    <p><?= $image; ?></p>
                </div>
                <div class="form-group">
                    <label for="image">Selectionner une image:</label>
                    <input type="file" name="image" id="image" class="form-control">
                    <span class="help-inline"><?= $imageError; ?></span>
                </div>
                <div class="form-actions">
                   <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                    <a href="index.php" class="btn btn-warning"><span class="glyphicon glyphicon-arrow-left"></span> retour</a>
                </div>
            </form>
            </div>
            <div class="col-sm-6 site">
                <div class="thumbnail">
                    <img src="<?= '../img/' . $image; ?>" class="image" alt="...">
                    <div class="price"><?= ' '.number_format((float)$price,2, '.','') . ' €'; ?></div>
                    <div class="caption">
                        <p><?= ' '.$description; ?></p>
                        <a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span> Commander </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>