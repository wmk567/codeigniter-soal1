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
            } else {
                fileField.style.display = 'none';
                quantityField.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <h1>Create a New Product</h1>
    <form action="/api/physical-products/create" method="post" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required><br><br>

        <div id="quantityField">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity"><br><br>
        </div>

        <input type="hidden" id="physical" name="type" value="physical">

        <button type="submit">Save Product</button>
    </form>

    <p><a href="/physical-products">Back to Product List</a></p>
</body>
</html>
