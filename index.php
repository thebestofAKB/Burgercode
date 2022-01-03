<!DOCTYPE html>
<html lang="fr">
<head>
   
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    
    <link rel="stylesheet" href="libs/css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="libs/css/styles.css" />
    
    <script type="text/javascript" src="libs/js/jquery.min.js"></script>
    <script type="text/javascript" src="libs/js/bootstrap.min.js"></script>
    
    <title>BurgerCode</title>
    
</head>
<body>
   
    <div class="container site">
        <h1 class="text-logo">
            <span class="glyphicon glyphicon-cutlery"></span> Brioche Dore <span class="glyphicon glyphicon-cutlery"></span>
        </h1>
        
        <?php 
        require_once('admin/database.php');
        
        echo '<nav>
                <ul class="nav nav-pills">';
        
        $db = Database::connect();
        $statement = $db->query("SELECT * FROM categories");
        $categories = $statement->fetchAll(PDO::FETCH_OBJ);
        
        foreach($categories as $category)
        {
            if($category->id == '1')
                echo '<li role="presentation" class="active"><a href="#' .$category->id. '" data-toggle="tab">' .$category->name. '</a></li>';
            else
                echo '<li role="presentation"><a href="#' .$category->id. '" data-toggle="tab">' .$category->name. '</a></li>';
        }
        
        echo        '</ul>
                </nav>';
        
        echo '<div class="tab-content">';
        
        //On fait la boucle foreach pour recuperer chaque categorie(contenant a son tour des items).
        foreach($categories as $category)
        {
            if($category->id == '1')
                echo '<div class="tab-pane active" id="' .$category->id. '">';
            else
                echo '<div class="tab-pane" id="' .$category->id. '">';
            
            echo '<div class="row">';
            
            $statement = $db->prepare("SELECT * FROM items WHERE items.category = ?");
            $statement->execute([$category->id]);
            
            //On fait une autre boucle(while) ds cette boucle(foreach) pour recuperer chaque item d'une categorie($category->id).
            while($item = $statement->fetch(PDO::FETCH_OBJ))
            {
                echo '<div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <img src="img/' .$item->image. '" alt="...">
                            <div class="price">' .number_format((float)$item->price,2, '.',''). ' €</div>
                            <div class="caption">
                                <h4>' .$item->name. '</h4>
                                <p>' .$item->description. '</p>
                                <a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span> Commander </a>
                            </div>
                        </div>
                    </div>';
            }
            
            echo '</div>
                    </div>';
        }
        
        Database::disconnect();
        
        ?>
                
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>