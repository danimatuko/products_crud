<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// init errors
$errors = [];

// initial form values
$title = '';
$description = '';
$price = '';

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

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Products CRUD</title>
</head>

<body class="container-lg py-5">
    <div class="w-50 mx-auto">

        <!-- show errors if exists -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mb-3">
                <?php foreach ($errors as $error): ?>
                    <div>
                        <?php echo $error ?>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <h1 class="text-uppercase mb-4 display-3 fw-bold">Add new product</h1>

        <!-- create product form -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product-title" class="product-title">Product Image</label>
                <input name="image" type="file" class="form-control" id="product-title">
            </div>
            <div class="mb-3">
                <label for="product-title" class="product-title">Product Title</label>
                <input name="title" type="text" class="form-control" id="product-title" value="<?php echo $title ?>">
            </div>
            <div class="mb-3">
                <label for="product-description" class="product-description">Product Description</label>
                <textarea name="description" class="form-control"
                    id="product-description"><?php echo $description ?></textarea>
            </div>
            <div class="mb-3">
                <label for="product-price" class="product-title">Product Price</label>
                <input name="price" type="number" step="0.1" class="form-control" id="product-price"
                    value="<?php echo $price ?>">
            </div>
            <button name='submit ' type="submit" class="btn btn-dark w-100">Submit</button>
        </form>
    </div>

</body>

</html>