<?php
include "conn.php"; // Database connection

// Fetch all products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$productsArr = [];
while($row = mysqli_fetch_assoc($result)) {
    $productsArr[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gugma Lokal</title>
<style>
/* Reset */
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Poppins', sans-serif; background-color: #e0e0e0; }

/* Navbar */
.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #F4C430;
    padding: 12px 10px;
    flex-wrap: wrap;
    position: relative;
}

.navbar a {
    text-decoration: none;
    background-color: white;
    color: #000;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    margin: 2px;
}

.navbar a:hover { background-color: #ff5a5f; color: white; }

/* Hamburger */
.hamburger {
    display: none;
    background-color: #ff5a5f;
    color: white;
    border: none;
    font-size: 24px;
    padding: 5px 12px;
    border-radius: 6px;
    cursor: pointer;
}

/* Search Section */
.search-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
    gap: 10px;
    width: 100%;
    padding: 0 10px;
}

.search-box {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    width: 100%;
    max-width: 450px;
}

.search-box input {
    flex: 1 1 200px;
    min-width: 120px;
    padding: 10px 15px;
    border: 2px solid #F4C430;
    border-radius: 6px;
    font-size: 14px;
    outline: none;
}

.search-box button {
    flex: 0 0 auto;
    background-color: #F4C430;
    color: black;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-box button:hover { background-color: #ff5a5f; color: white; }

/* Exit Search Button */
#exit-search {
    display: none;
    background-color: #ff5a5f;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}
#exit-search:hover { background-color: #F4C430; color: black; }

/* Products Grid */
#products-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    padding: 20px;
    justify-items: center;
}

/* Product Card */
.product-card {
    background-color: white;
    width: 100%;
    max-width: 230px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    overflow: hidden;
    text-align: center;
}
.product-card img {
    width: 100%;
    height: 160px;
    object-fit: cover;
}
.product-card h3 { margin: 0; font-size: 16px; color: #333; }
.product-card p { font-size: 13px; color: #666; min-height: 40px; }
.product-card .price { font-weight: 600; color: #ff5a5f; }

/* Frames Section */
.frames-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 40px;
    gap: 20px;
    width: 100%;
    padding: 0 10px;
}
.frame {
    background-color: #ff5a5f;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    border-radius: 10px;
    padding: 10px;
    width: 100%;
    max-width: 700px;
}
.frame .image {
    flex: 1 1 200px;
    min-height: 150px;
    background-color: white;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #aaa;
    font-size: 14px;
}
.frame .desc {
    flex: 1 1 200px;
    color: white;
    text-align: left;
    min-width: 150px;
}

/* Mobile Styles */
@media screen and (max-width: 768px) {
    .nav-left, .nav-center, .nav-right a:not(.hamburger) { display: none; }
    .hamburger { display: inline-block; }
    .navbar.active .nav-left,
    .navbar.active .nav-center,
    .navbar.active .nav-right a:not(.hamburger) {
        display: flex;
        flex-direction: column;
        width: 100%;
        margin-top: 10px;
    }
    .navbar.active .nav-left a,
    .navbar.active .nav-center a,
    .navbar.active .nav-right a {
        width: 100%;
        text-align: center;
        margin: 5px 0;
    }

    .frame { flex-direction: column; align-items: center; }
    .frame .image, .frame .desc { flex: 1 1 100%; min-width: auto; }
}
</style>
</head>
<body>

<!-- 🔹 Top Navigation (matches product.php design) -->
<div class="navbar">
    <div class="nav-left">
        <a href="profile.php">Profile</a>
    </div>
    
    <div class="nav-center">
        <a href="index.php">Home</a>
        <a href="product.php">Products</a>
        <a href="track_order.php">Track Order</a> <!-- Track Order -->
        <a href="aboutus.php">About Us</a>
        <a href="gallery.php">Gallery</a>
        <a href="contact.php">Contact</a>
    </div>
    
    <div class="nav-right">
        <a href="add_to_cart.php">Cart</a>
        <button class="hamburger" onclick="toggleMenu()">☰</button>
    </div>
</div>

<!-- 🔍 Search Section -->
<div class="search-section">
    <div class="search-box">
        <input id="search-input" type="text" placeholder="Search your favorite fries..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button id="search-button">Search</button>
    </div>
    <button id="exit-search" onclick="exitSearch()">Exit Search</button>
</div>

<!-- Products Container -->
<div id="search-results">
  <div id="products-container"></div>
</div>

<!-- 🔸 Three Frames Section -->
<div class="frames-section">
  <div class="frame">
    <div class="image">Insert Image Here</div>
    <div class="desc">Insert description here</div>
  </div>
  <div class="frame">
    <div class="image">Insert Image Here</div>
    <div class="desc">Insert description here</div>
  </div>
  <div class="frame">
    <div class="image">Insert Image Here</div>
    <div class="desc">Insert description here</div>
  </div>
</div>

<script>
// Hamburger toggle
function toggleMenu() {
    document.querySelector('.navbar').classList.toggle('active');
}

// Search functionality
const allProducts = <?php echo json_encode($productsArr); ?>;
const searchInput = document.getElementById('search-input');
const searchButton = document.getElementById('search-button');
const searchResults = document.getElementById('search-results');
const productsContainer = document.getElementById('products-container');
const exitButton = document.getElementById('exit-search');

function performSearch() {
    const query = searchInput.value.toLowerCase().trim();
    productsContainer.innerHTML = '';
    const filtered = allProducts.filter(p => p.product_name.toLowerCase().includes(query));

    if(filtered.length === 0){
        productsContainer.innerHTML = '<p style="color:#6c757d; text-align:center; grid-column:1/-1;">No products found.</p>';
    } else {
        filtered.forEach(p => {
            productsContainer.innerHTML += `
                <div class="product-card">
                    <a href="product_details.php?id=${p.product_id}" style="text-decoration:none; color:inherit;">
                        <div>${p.image ? `<img src="${p.image}">` : '<span style="color:#aaa; line-height:160px;">No Image</span>'}</div>
                    </a>
                    <div style="padding:12px;">
                        <h3>${p.product_name}</h3>
                        <p>${p.description}</p>
                        <p class="price">₱${parseFloat(p.price).toFixed(2)}</p>
                    </div>
                </div>
            `;
        });
    }
    searchResults.style.display = 'block';
    exitButton.style.display = 'inline-block';
}

searchButton.addEventListener('click', performSearch);
searchInput.addEventListener('keydown', e => { if(e.key === 'Enter') performSearch(); });

function exitSearch() {
    searchInput.value = '';
    productsContainer.innerHTML = '';
    searchResults.style.display = 'none';
    exitButton.style.display = 'none';
}
</script>

</body>
</html>
