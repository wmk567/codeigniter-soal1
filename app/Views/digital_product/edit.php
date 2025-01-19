<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
</head>
<body>
    <?php if (isset($products[0])): ?>
        <h1>Create a New Product</h1>
        <form action="/api/digital-products/update" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="id" name="id" value="<?= esc($products[0]['id']) ?>" required><br><br>

            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?= esc($products[0]['name']) ?>" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= esc($products[0]['description']) ?></textarea><br><br>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?= esc($products[0]['price']) ?>" step="0.01" min="0" required><br><br>

            <input type="hidden" id="digital" name="type" value="digital">

            <div id="fileField" style="display: block;">
                <label for="file">Upload File (for Digital Products):</label>
                <input type="file" id="filename" name="filename" value="<?= esc($products[0]['filename']) ?>"><br><br>
            </div>

            <button type="submit">Save Product</button>
        </form>

    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>

    <br>
    <?php if (session()->getFlashdata('error')): ?>
        <div>
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>
    
    <p><a href="/digital-products">Back to Product List</a></p>
</body>
</html>