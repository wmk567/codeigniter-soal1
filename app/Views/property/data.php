<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin-top: 20px;
        }
        input, button {
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Property Management</h1>

    <?php if (isset($properties) && !empty($properties)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product ID</th>
                    <th>Property Name</th>
                    <th>Property Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($properties as $property): ?>
                    <tr>
                        <td><?= esc($property['id']) ?></td>
                        <td><?= esc($property['product_id']) ?></td>
                        <td><?= esc($property['property_name']) ?></td>
                        <td><?= esc($property['property_value']) ?></td>
                        <td>
                            <form action="/property/edit" method="get" style="display: inline;">
                                <input type="hidden" name="id" value="<?= esc($property['id']) ?>" required>
                                <button type="submit">Edit Property</button>
                            </form>
                            <form action="api/property/delete" method="post" style="display: inline;">
                                <input type="hidden" name="id" value="<?= esc($property['id']) ?>" required>
                                <input type="hidden" id="product_id" name="product_id" value="<?= esc($_GET['product_id'] ?? null) ?>" required>
                                <button type="submit">Delete Property</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No property available.</p>
    <?php endif; ?>

    <h2>Add New Property</h2>
    <form action="/api/property/create" method="post">
        <input type="hidden" id="product_id" name="product_id" value="<?= esc($_GET['product_id'] ?? null) ?>" required>
                
        <label for="property_name">Property Name:</label>
        <input type="text" id="property_name" name="property_name" required><br>
                
        <label for="property_value">Property Value:</label>
        <input type="text" id="property_value" name="property_value" required><br>
                
        <button type="submit">Add Property</button>
    </form>

    <p><a href="/products">Back to Product List</a></p>

    <br>
    <?php if (session()->getFlashdata('error')): ?>
        <div>
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>
</body>
</html>
