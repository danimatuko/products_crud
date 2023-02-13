<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;



if (!$id) {
    header('Location: index.php');
    exit;
}

// prepare query
$statement = $pdo->prepare('SELECT * FROM products WHERE id=:id');
$statement->bindValue(':id', $id);
$statement->execute();
// get product from db

$product = $statement->fetch(PDO::FETCH_ASSOC);

// init errors
$errors = [];

// initial form values
$title = $product['title'];
$description = $product['description'];
$price = $product['price'];

// on form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get form values
    $title = $_POST['title'];
    $description = $_POST["description"];
    $price = $_POST["price"];

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
        $image_path = $product['image'];
        $directory = "images/";


        if ($image && $image['tmp_name']) {
            // remove file if exist
            unlink($product['image']);
            move_uploaded_file($image['tmp_name'], $image_path);
        }




        // prepare query
        $statement = $pdo->prepare("UPDATE  products SET title=:title,image=:image,description=:description,price=:price WHERE id=:id");
        // bind values
        $statement->bindValue(':id', $id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $image_path);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        // add product to db
        $res = $statement->execute();
        // redirect after successful upload
        header('Location: index.php');

    }
}

function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }

    return $str;
}

?>

<?php include('views/partials/header.php'); ?>

<h1 class="text-uppercase mb-4 display-3 fw-bold">Edit
    <?php echo $product['title'] ?>
</h1>
<?php include('views/products/form.php'); ?>

<?php include('views/partials/footer.php'); ?>