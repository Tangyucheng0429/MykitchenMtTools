<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Kitchen My Tools - Home & Products</title>
  <link rel="stylesheet" href="index.css">
  <link rel="icon" type="image/x-icon" href="Admin/MyKitchenMyTools Logo.png">
  <style>
    .category-item { cursor: pointer; }
    .category-bar-wrapper {
      overflow-x: auto;
      white-space: nowrap;
    }
    .category-bar {
      display: inline-flex;
    }
    .category-item {
      display: inline-block;
      margin: 5px;
    }
  </style>
</head>
<body>

<!-- Home Section -->
<div id="home-section" class="home-section">
    <img src="Admin/Background Photo.jpg" alt="Background Photo" class="background-image">
    <div class="overlay"></div>
    <div class="home-content">
      <div class="logo-title-container">
        <img src="uploads/MyKitchenMyTools Logo.png" alt="Logo" class="logo-home">
        <h1 class="home-title">My Kitchen My Tools</h1>
      </div>
      <div class="button-container">
        <a href="#products" class="home-btn">Find Product</a>
      </div>
    </div>
</div>

<!-- Header Section for Products -->
<header id="products">
    <div class="header-container">
        <img src="uploads/MyKitchenMyTools Logo.png" alt="Logo" class="logo">
        <nav class="nav-bar">
            <a href="#home-section">Home</a>
            <a href="about.php">About Me</a>
            <a href="#products">Product</a>
            <a href="contact.php">Contact</a>
        </nav>
        <div class="findme">
            <a href="findme">Find Me</a>
        </div>
    </div>
</header>

<!-- Category Bar -->
<div class="category-bar-wrapper">
    <div class="category-bar" id="categoryBar">
      <button class="category-item" data-category="all">All</button>
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

      // Query to get categories from the database
      $query_categories = "SELECT name FROM categories";
      $result_categories = $mysqli->query($query_categories);

      if ($result_categories->num_rows > 0) {
          // Output data of each category row
          while ($row = $result_categories->fetch_assoc()) {
              $category_name = htmlspecialchars($row['name']);
              echo '<button class="category-item" data-category="' . strtolower($category_name) . '">' . $category_name . '</button>';
          }
      } else {
          echo "No categories found";
      }
      ?>
    </div>
</div>

<!-- Products Section -->
<div class="products-section" id="productsSection">
    <?php
    // Query to get all products
    $query_products = "SELECT product_id, product_name, price, company_name, image, hotseller, new, category_name FROM products";
    $result_products = $mysqli->query($query_products);

    if ($result_products->num_rows > 0) {
        // Output data of each product row
        while ($row = $result_products->fetch_assoc()) {
            $product_id = intval($row['product_id']);
            $product_name = htmlspecialchars($row['product_name']);
            $price = htmlspecialchars($row['price']);
            $company_name = htmlspecialchars($row['company_name']);
            $image = htmlspecialchars($row['image']);
            $hotseller = $row['hotseller'] ? "TOP SELLER" : "";
            $new = $row['new'] ? "NEW" : "";
            $category_name = strtolower(htmlspecialchars($row['category_name']));

            echo '<div class="product-card" data-category="' . $category_name . '" style="display: block;">';
            // Display "TOP SELLER" label above the product image
            if (!empty($hotseller)) {
                echo '<div class="product-label-top">' . $hotseller . '</div>';
            }
            echo '<img src="' . $image . '" alt="' . $product_name . '">';
            echo '<div class="product-info">';
            // Display "NEW" label above the product name
            if (!empty($new)) {
                echo '<span class="product-label-new">' . $new . '</span>';
            }
            echo '<h4 class="product-name">' . $product_name . '</h4>';
            echo '<p class="product-company">Product Company: ' . $company_name . '</p>';
            echo '<p class="product-price">RM' . $price . '</p>';
            echo '<a href="product_details.php?id=' . $product_id . '" class="load-more-button">Load More</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "No products found";
    }
    // Close the connection
    $mysqli->close();
    ?>
</div>

<footer>
    <div class="footer-content">
      <div class="footer-logo">
        <img src="uploads/MyKitchenMyTools Logo.png" alt="My Kitchen My Tools Logo">
      </div>
      <div class="footer-section">
        <h4>My Kitchen My Tools</h4>
        <p>
          <strong>Address:</strong> No. 5, 7 & 9, Jalan Lapangan Siber 5, Bandar Cyber, 31350 Ipoh, Perak<br>
          <strong>Telephone:</strong> 05-318 4436<br>
          <strong>Email:</strong> example@example.com<br>
          <strong>Working Time:</strong> Mon-Sat 9:30am-6pm
        </p>
      </div>
      <div class="footer-section">
        <h4>Company</h4>
        <ul>
          <li><a href="#home-section">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="#products">Product</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h4>Product</h4>
        <ul>
          <?php
          // Load product categories from the database for footer section
          $mysqli = new mysqli($servername, $username, $password, $database);
          $query_footer_categories = "SELECT name FROM categories";
          $result_footer_categories = $mysqli->query($query_footer_categories);

          if ($result_footer_categories->num_rows > 0) {
              while ($row = $result_footer_categories->fetch_assoc()) {
                  $category_name = htmlspecialchars($row['name']);
                  echo '<li><a href="#' . strtolower(str_replace(' ', '-', $category_name)) . '">' . $category_name . '</a></li>';
              }
          } else {
              echo "<li>No product categories available</li>";
          }

          // Close the connection
          $mysqli->close();
          ?>
        </ul>
      </div>
      <div class="footer-section">
        <h4>Social</h4>
        <ul>
          <li><a href="#facebook">Facebook</a></li>
          <li><a href="#shopee">Shopee</a></li>
          <li><a href="#lazada">Lazada</a></li>
          <li><a href="#whatsapp">Whatsapp</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; My Kitchen My Tools. All Rights Reserved 2024</p>
    </div>
</footer>

<script>
  // JavaScript to filter products by category
  document.querySelectorAll('.category-item').forEach(button => {
    button.addEventListener('click', function() {
      const category = this.getAttribute('data-category');
      const products = document.querySelectorAll('.product-card');

      products.forEach(product => {
        if (category === 'all' || product.getAttribute('data-category') === category) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    });
  });
</script>

</body>
</html>
