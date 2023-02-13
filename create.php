<?php
require('functions.php');

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// init errors
$errors = [];

// initial form values
$title = '';
$description = '';
$price = '';
$product = [
    'title' => '',
    'image' => ''
];

// on form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get form values
    $title = $_POST['title'];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $date = date('Y-m-d H:i:s'); // 2001-03-10 17:16:18 (the MySQL DATETIME format)

    // check for errors
    if (!$title) {
        $errors[] = 'Product title is required';
    }
    if (!$price) {
        $errors[] = 'Product price is required';
    }


    if (empty($errors)) {
        // handle file upload
        $image = $_FILES['image'] ?? null;
        $image_path = 'images/' . randomString(8) . '-' . $image['name'];
        $directory = "images/";

        if ($image) {
            move_uploaded_file($image['tmp_name'], $image_path);
        }



        // prepare query
        $statement = $pdo->prepare("INSERT INTO products (title,image,description,price,created_at) VALUES (:title,:image,:description,:price,:date)");
        // bind values
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $image_path);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':date', $date);
        // add product to db
        $res = $statement->execute();
        // redirect after successful upload
        header('Location: index.php');

    }
}



?>
<?php include('views/partials/header.php'); ?>
<h1 class="text-uppercase mb-4 display-3 fw-bold">Create new product
</h1>
<?php include('views/products/form.php'); ?>