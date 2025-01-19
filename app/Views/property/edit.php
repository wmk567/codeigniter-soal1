<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
</head>
<body>
    <?php if (isset($property[0])): ?>
        <h1>Edit Property</h1>
        <form action="/api/property/update" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="id" name="id" value="<?= esc($property[0]['id']) ?>" required>
            <input type="hidden" id="product_id" name="product_id" value="<?= esc($property[0]['product_id']) ?>" required>

            <label for="property_name">Property Name:</label>
            <input type="text" id="property_name" name="property_name" value="<?= esc($property[0]['property_name']) ?>" required><br><br>

            <label for="property_value">Property Value:</label>
            <textarea id="property_value" name="property_value" required><?= esc($property[0]['property_value']) ?></textarea><br><br>

            <button type="submit">Save Property</button>
        </form>

    <?php else: ?>
        <p>No property available.</p>
    <?php endif; ?>
</body>
</html>