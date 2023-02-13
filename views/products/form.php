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
<a href="index.php">Back to products</a>


<!-- create product form -->
<form action="" method="POST" enctype="multipart/form-data">
    <?php if ($product['image']): ?>
    <img src="<?php echo $product['image'] ?>" alt="product" width=150px>
    <?php endif ?>
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
        <textarea name="description" class="form-control" id="product-description"><?php echo $description ?></textarea>
    </div>
    <div class="mb-3">
        <label for="product-price" class="product-title">Product Price</label>
        <input name="price" type="number" step="0.1" class="form-control" id="product-price"
            value="<?php echo $price ?>">
    </div>
    <button name='submit ' type="submit" class="btn btn-dark w-100">Submit</button>
</form>
</div>