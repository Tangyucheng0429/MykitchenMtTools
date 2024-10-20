<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details - My Kitchen My Tools</title>
  <link rel="stylesheet" href="details.css">
  <link rel="icon" type="image/x-icon" href="Admin/MyKitchenMyTools Logo.png">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f8f8f8;
      padding: 20px;
      color: #333;
    }
    .product-details-container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .product-details-container img {
      width: 100%;
      max-height: 250px;
      object-fit: contain;
      margin-bottom: 15px;
      border-radius: 10px;
    }
    .product-details {
      margin-bottom: 15px;
    }
    .product-title {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 10px;
      color: #333;
    }
    .product-price {
      font-size: 1.5rem;
      font-weight: bold;
      color: #ff0000;
      margin-bottom: 10px;
    }   
    .product-company, .product-info {
      font-size: 1rem;
      margin-bottom: 8px;
    }
    .product-description {
      font-size: 0.9rem;
      line-height: 1.6;
      margin-bottom: 15px;
    }
    .product-link {
      font-size: 0.9rem;
      margin-bottom: 15px;
      word-wrap: break-word;
    }
    .back-button {
      display: inline-block;
      padding: 8px 15px;
      margin-top: 15px;
      border-radius: 5px;
      background-color: #000000;
      color: #ffffff;
      font-weight: bold;
      text-decoration: none;
      font-size: 0.9rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .back-button:hover {
      background-color: #f4b400;
      transform: scale(1.05);
    }
  </style>
</head>
<body>
  <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "mykitchenmytools_db";

    // Create a new database connection
    $mysqli = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Get product ID from query string
    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Query to get product details
    $query_product = "SELECT * FROM products WHERE product_id = $product_id";
    $result_product = $mysqli->query($query_product);

    if ($result_product && $result_product->num_rows > 0) {
        $product = $result_product->fetch_assoc();
        $product_name = htmlspecialchars($product['product_name']);
        $price = htmlspecialchars($product['price']);
        $company_name = htmlspecialchars($product['company_name']);
        $product_description = htmlspecialchars($product['product_description']);
        $image = htmlspecialchars($product['image']);
        $product_size = htmlspecialchars($product['product_size']);
        $product_height = htmlspecialchars($product['product_height']);
        $product_length = htmlspecialchars($product['product_length']);
        $product_width = htmlspecialchars($product['product_width']);
        $product_weight = htmlspecialchars($product['product_weight']);
        $product_diameter = htmlspecialchars($product['product_diameter']);
        $product_capacity = htmlspecialchars($product['product_capacity']);
        $induction_base_diameter = htmlspecialchars($product['induction_base_diameter']);
        $product_material = htmlspecialchars($product['product_material']);
        $product_link = htmlspecialchars($product['product_link']);

        echo '<div class="product-details-container">';
        echo '<img src="' . $image . '" alt="' . $product_name . '" />';
        echo '<div class="product-details">';
        echo '<h1 class="product-title">' . $product_name . '</h1>';
        echo '<p class="product-price">RM ' . $price . '</p>';
        echo '<p class="product-company">Company: ' . $company_name . '</p>';
        echo '<p class="product-description">' . $product_description . '</p>';

        // Display additional product details if available
        if (!empty($product_size)) {
          echo '<p class="product-info"><strong>Size:</strong> ' . $product_size . '</p>';
        }
        if (!empty($product_height)) {
          echo '<p class="product-info"><strong>Height:</strong> ' . $product_height . '</p>';
        }
        if (!empty($product_length)) {
          echo '<p class="product-info"><strong>Length:</strong> ' . $product_length . '</p>';
        }
        if (!empty($product_width)) {
          echo '<p class="product-info"><strong>Width:</strong> ' . $product_width . '</p>';
        }
        if (!empty($product_weight)) {
          echo '<p class="product-info"><strong>Weight:</strong> ' . $product_weight . '</p>';
        }
        if (!empty($product_diameter)) {
          echo '<p class="product-info"><strong>Diameter:</strong> ' . $product_diameter . '</p>';
        }
        if (!empty($product_capacity)) {
          echo '<p class="product-info"><strong>Capacity:</strong> ' . $product_capacity . '</p>';
        }
        if (!empty($induction_base_diameter)) {
          echo '<p class="product-info"><strong>Induction Base Diameter:</strong> ' . $induction_base_diameter . '</p>';
        }
        if (!empty($product_material)) {
          echo '<p class="product-info"><strong>Material:</strong> ' . $product_material . '</p>';
        }
        if (!empty($product_link)) {
          echo '<p class="product-link"><strong>Product Link:</strong> <a href="' . $product_link . '" target="_blank">' . $product_link . '</a></p>';
        }

        echo '</div>';
        echo '<a href="index.php#products" class="back-button">Back to Products</a>';
        echo '</div>';
    } else {
        echo '<p>Product not found.</p>';
        echo '<a href="index.php#products" class="back-button">Back to Products</a>';
    }

    // Close the connection
    $mysqli->close();
  ?>
</body>
</html>
