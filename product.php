<?php
include "conn.php"; // ✅ Make sure this connects to your database

$search_query = '';
if(isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM products WHERE product_name LIKE '%$search_query%' OR description LIKE '%$search_query%' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu | The Fry Project</title>
  <style>
    /* Global box-sizing */
    *, *::before, *::after {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    /* Navigation */
    .top-nav {
      background-color: #F4C430;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 12px 0;
      position: relative;
      flex-wrap: wrap;
      gap: 10px;
    }
    .top-nav .left, .top-nav .right {
      position: absolute;
      top: 12px;
    }
    .top-nav .left {
      left: 20px;
    }
    .top-nav .right {
      right: 20px;
    }
    .top-nav a {
      text-decoration: none;
      background-color: white;
      color: #000;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-block;
      font-size: 14px;
    }
    .top-nav a:hover, .top-nav a:focus {
      background-color: #ff5a5f;
      color: white;
      outline: none;
    }
    .top-nav .center-links {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
      flex: 1 1 100%;
      margin-top: 12px;
    }
    .top-nav .center-links a.active {
      background-color: #ff5a5f;
      color: white;
    }
    /* Responsive Search Bar */
    .search-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      margin: 20px 10px 0;
      gap: 10px;
    }
    form.search-form {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      width: 100%;
      max-width: 480px;
      justify-content: center;
      align-items: center;
    }
    form.search-form input[type="text"] {
      flex: 1 1 250px;
      max-width: 400px;
      min-width: 150px;
      padding: 10px 15px;
      border: 2px solid #F4C430;
      border-radius: 6px;
      font-size: 14px;
      outline: none;
    }
    form.search-form button {
      flex: 0 0 auto;
      background-color: #F4C430;
      color: black;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 14px;
    }
    form.search-form button:hover, form.search-form button:focus {
      background-color: #ff5a5f;
      color: white;
      outline: none;
    }
    /* Header */
    .menu-header {
      text-align: center;
      margin-top: 40px;
      padding: 0 10px;
    }
    .menu-header h1 {
      color: #ff5a5f;
      margin-bottom: 8px;
    }
    .menu-header p {
      color: #6c757d;
      margin-top: 0;
      font-size: 14px;
    }
    /* Product grid */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 15px;
      padding: 30px 10px;
      justify-items: center;
    }
    .product-card {
      background-color: white;
      width: 100%;
      max-width: 230px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      overflow: hidden;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .product-card a {
      text-decoration: none;
      color: inherit;
    }
    .product-image {
      height: 160px;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }
    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      max-width: 100%;
      display: block;
    }
    .product-info {
      padding: 12px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .product-info h3 {
      margin: 0 0 6px;
      font-size: 16px;
      color: #333;
      line-height: 1.2;
    }
    .product-info p.description {
      font-size: 13px;
      color: #666;
      min-height: 40px;
      margin: 0 0 10px;
    }
    .product-info p.price {
      font-weight: 600;
      color: #ff5a5f;
      margin: 0 0 10px;
    }
    form.add-to-cart {
      margin-top: auto;
    }
    form.add-to-cart button {
      background-color: #F4C430;
      border: none;
      color: black;
      padding: 10px 16px;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: 0.3s;
      width: 100%;
      font-size: 14px;
    }
    form.add-to-cart button:hover, form.add-to-cart button:focus {
      background-color: #ff5a5f;
      color: white;
      outline: none;
    }
    /* No products message */
    .no-products {
      color: #6c757d;
      text-align: center;
      padding: 20px 10px;
      font-size: 16px;
    }
  </style>
</head>
<body>

 <!-- 🔹 Top Navigation -->
<div class="top-nav" role="navigation" aria-label="Main Navigation">
    <div class="left">
        <a href="profile.php">Profile</a>
    </div>
    <div class="right">
        <a href="add_to_cart.php">Cart</a>
    </div>
    <div class="center-links">
        <a href="index.php">Home</a>
        <a href="product.php">Products</a>
        <a href="track_order.php">Track Order</a> <!-- Added button -->
        <a href="aboutus.php">About Us</a>
        <a href="gallery.php">Gallery</a>
        <a href="contact.php">Contact</a>
    </div>
</div>

<!-- 🔍 Responsive Search Bar -->
<div class="search-container">
    <form method="GET" class="search-form" role="search" aria-label="Product Search">
        <input type="text" name="search" placeholder="Search your favorite fries..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
               aria-label="Search products"
               />
        <button type="submit">Search</button>
    </form>
</div>


  <!-- 🍟 Menu Header -->
  <div class="menu-header">
    <h1>The Locals Production</h1>
    <?php if(!empty($search_query)): ?>
      <p>Showing results for: "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
    <?php else: ?>
      <p></p>
    <?php endif; ?>
  </div>

  <!-- 🧊 Product Grid -->
  <div class="product-grid">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">
          <a href="product_details.php?id=<?php echo $row['product_id']; ?>" aria-label="View details for <?php echo htmlspecialchars($row['product_name']); ?>">
            <div class="product-image">
              <?php if (!empty($row['image'])): ?>
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" />
              <?php else: ?>
                <span style="color:#aaa; line-height:160px;">No Image</span>
              <?php endif; ?>
            </div>
          </a>
          <div class="product-info">
            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
            <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
            <p class="price">₱<?php echo number_format($row['price'], 2); ?></p>
            <form action="add_to_cart.php" method="POST" class="add-to-cart">
              <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
              <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>">
              <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
              <button type="submit">Add to Cart</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="no-products">No products found<?php echo (!empty($search_query)) ? " for \"".htmlspecialchars($search_query)."\"" : ""; ?>. Please check back later!</p>
    <?php endif; ?>
  </div>

</body>
</html>
