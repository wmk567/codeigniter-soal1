<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
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
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Product List</h1>

    <?php if (isset($products) && !empty($products)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= esc($product['id']) ?></td>
                        <td><?= esc($product['name']) ?></td>
                        <td><?= esc($product['description']) ?></td>
                        <td><?= esc($product['price']) ?></td>
                        <td><?= esc($product['type']) ?></td>
                        <td><?= esc($product['quantity']) ?></td>
                        <td><?= esc($product['created_at']) ?></td>
                        <td><?= esc($product['updated_at']) ?></td>
                        <td>
                            <form action="/physical-product/edit" method="get">
                                <input type="hidden" name="id" value="<?= esc($product['id']) ?>" required>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                        <td>
                            <form action="/api/physical-products/delete" method="post">
                                <input type="hidden" name="id" value="<?= esc($product['id']) ?>" required>
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</body>
</html>
