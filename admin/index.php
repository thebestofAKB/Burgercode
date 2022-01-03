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
                <strong>Liste des items</strong>
                <a href="insert.php" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
            </h1>
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr class="bg bg-primary">
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Categorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                    require("database.php");
                   $db = Database::connect();
                    $statement = $db->query("SELECT i.id, i.name, i.description, i.price, i.category, c.name AS category
                                             FROM items i LEFT JOIN categories c ON i.category = c.id
                                             ORDER BY id DESC");
                    while($item = $statement->fetch(PDO::FETCH_OBJ)):
                    ?>
                    <tr>
                        <td><?= $item->name; ?></td>
                        <td><?= $item->description; ?></td>
                        <td><?= number_format((float)$item->price,2, '.','') . ' €'; ?></td>
                        <td><?= $item->category; ?></td>
                        <td style="width:250px;">
                            <a href="view.php?id=<?= $item->id; ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-eye-open"> </span> Voir</a>
                            <a href="update.php?id=<?= $item->id; ?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-pencil"> </span> Modifier</a>
                            <a href="delete.php?id=<?= $item->id; ?>" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Supprimer</a>
                        </td>
                    </tr>
                    <?php endwhile; 
                     Database::disconnect();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>