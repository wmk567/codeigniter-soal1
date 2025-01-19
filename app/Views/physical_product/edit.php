<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <script>
        function toggleProductType() {
            const productType = document.querySelector('input[name="type"]:checked').value;
            const fileField = document.getElementById('fileField');
            if (productType === 'digital') {
                fileField.style.display = 'block';
                quantityField.style.display = 'none';
                quantity.value = null;
            } else {
                fileField.style.display = 'none';
                quantityField.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <?php if (isset($products[0])): ?>
        <h1>Create a New Product</h1>
        <form action="/api/physical-products/update" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="id" name="id" value="<?= esc($products[0]['id']) ?>" required><br><br>

            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?= esc($products[0]['name']) ?>" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= esc($products[0]['description']) ?></textarea><br><br>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" min="0" value="<?= esc($products[0]['price']) ?>" required><br><br>

            <div id="quantityField">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?= esc($products[0]['quantity']) ?>" required><br><br>
            </div>

            <input type="hidden" id="physical" name="type" value="physical">

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

    <p><a href="/physical-products">Back to Product List</a></p>
</body>
</html>