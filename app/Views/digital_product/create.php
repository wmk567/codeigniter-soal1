<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
</head>
<body>
    <h1>Create a New Product</h1>
    <form action="/api/digital-products/create" method="post" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required><br><br>

        <input type="hidden" id="digital" name="type" value="digital">

        <div id="fileField" style="display: block;">
            <label for="file">Upload File (for Digital Products):</label>
            <input type="file" id="filename" name="filename"><br><br>
        </div>

        <button type="submit">Save Product</button>
    </form>

    <p><a href="/digital-products">Back to Product List</a></p>
</body>
</html>
