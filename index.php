<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$search = $_GET['search'] ?? '';

if ($search) {
    $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY created_at DESC');
    $statement->bindValue(':title', "%$search%");
} else {
    $statement = $pdo->prepare('SELECT * FROM products ORDER BY created_at DESC');
}
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

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
    <!-- title -->
    <h1 class="my-5">Products CRUD</h1>
    <!-- search -->
    <form method='GET' class="w-25">
        <div class="input-group mb-3">
            <input type="text" name='search' value="<?php echo $search ?>" class="form-control"
                placeholder="Search product" aria-label="search" aria-describedby="button-addon2">
            <button class="btn btn-secondary" type="submit">SEARCH</button>
        </div>
    </form>
    <!-- create product -->
    <div class="mb-4">
        <a href="create.php" class="btn btn-success">Create Product</a>
    </div>
    <!-- data table -->
    <table class="table table-striped">

        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Title</th>
                <th scope="col">Price</th>
                <th scope="col">Created at</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $i => $product): ?>
                <tr>
                    <th scope="row">
                        <?php echo $i + 1 ?>
                    </th>
                    <td> <img src="<?php echo $product['image'] ?>" width="50px" /> </td>
                    <td>
                        <?php echo $product['title'] ?>
                    </td>
                    <td>
                        <?php echo $product['price'] ?>
                    </td>
                    <td>
                        <?php echo $product['created_at'] ?>
                    </td>
                    <td>
                        <a href="update.php?id=<?php echo $product['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <form method='POST' action="delete.php" class="d-inline-block">
                            <input type="hidden" name="id" value=<?php echo $product['id'] ?>>
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>

                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</body>

</html>