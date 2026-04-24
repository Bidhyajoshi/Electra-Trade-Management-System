<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joshi Trading Pro</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* Strict Global Override for Card Hover Effects */
        .product-card,
        .product-gallery .glass-panel {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .product-card:hover,
        .product-gallery .glass-panel:hover {
            transform: translateY(-12px) !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), inset 0 0 15px rgba(255, 215, 0, 0.15) !important;
            border-color: rgba(255, 215, 0, 0.8) !important;
        }

        /* Feature Icons Hover Zoom on Home Page */
        .product-gallery .glass-panel div[style*="font-size: 3rem"] {
            display: inline-block;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        }

        .product-gallery .glass-panel:hover div[style*="font-size: 3rem"] {
            transform: scale(1.25) !important;
            text-shadow: 0 0 15px rgba(255, 215, 0, 0.6);
        }

        /* Shimmer Effect for Buttons */
        @keyframes shimmer {
            0% { transform: translateX(-100%) skewX(-15deg); }
            50% { transform: translateX(200%) skewX(-15deg); }
            100% { transform: translateX(200%) skewX(-15deg); }
        }
        
        .btn-slider, .btn-primary, .chatbot-toggler {
            position: relative;
            overflow: hidden;
        }
        
        .btn-slider::before, .btn-primary::before, .chatbot-toggler::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 3s infinite ease-in-out;
            pointer-events: none;
        }

        /* Typewriter Cursor */
        .typewriter {
            border-right: 3px solid var(--electric-gold);
            padding-right: 5px;
            animation: blinkCursor 0.75s step-end infinite;
        }
        @keyframes blinkCursor {
            from, to { border-color: transparent }
            50% { border-color: var(--electric-gold) }
        }
    </style>
</head>
<body>

<header class="main-header">
    <nav class="container header-container" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; max-width: 1400px; margin: 0 auto;">
        
        <!-- Left: Logo & Categories -->
        <div class="header-left" style="display: flex; align-items: center; gap: 40px;">
            <a href="index.php" class="logo" style="text-decoration: none; display: inline-flex; align-items: center;">
                <i class="fas fa-bolt" style="color: #FFD700; margin-right: 8px; font-size: 1.8rem;"></i>
                <span style="font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 1.6rem; letter-spacing: 1px; color: #0a192f;">JOSHI TRADING</span>
            </a>

            <ul class="nav-links categories-menu">
                <li><a href="index.php"><i class="fas fa-home" style="color: var(--electric-gold); margin-right: 4px; font-size: 0.95rem; text-shadow: 0 0 5px rgba(255,215,0,0.3);"></i>HOME</a></li>
                <li><a href="products.php" class="all-products-link">ALL PRODUCTS</a></li>
                <li><a href="products.php?category=fans">FANS</a></li>
                <li><a href="products.php?category=lighting">LIGHTING</a></li>
                <li><a href="products.php?category=wires">WIRES</a></li>
                <li><a href="products.php?category=tools">TOOLS</a></li>
                <li style="position: relative;">
                    <a href="products.php?category=offers">OFFERS</a>
                    <span class="badge-hot">HOT</span>
                </li>
                <li>
                   <a href="index.php#feedback-section">REVIEWS</a>
                </li>
            </ul>
        </div>

        <!-- Center: Search Bar -->
        <div class="header-center" style="margin: 0 30px; flex-grow: 1; max-width: 400px;">
            <form action="products.php" method="GET" class="search-form-premium" id="searchForm" style="width: 100%;">
                <i class="fas fa-search search-icon-left"></i>
                <input type="text" name="search" id="searchInput" placeholder="Search products..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="search-input-premium" autocomplete="off">
                <div id="searchSuggestions" class="suggestions-box"></div>
            </form>
        </div>

        <!-- Right: Top Links & User Icons -->
        <div class="header-right" style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
            <div class="top-links" style="display: flex; gap: 15px;">
                <a href="about.php" style="color: #64748b; font-size: 13px; font-weight: 600; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">About Us</a>
                <a href="contact.php" style="color: #64748b; font-size: 13px; font-weight: 600; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">Contact</a>
            </div>
            
            <div class="user-actions" style="display: flex; align-items: center; gap: 20px;">
                <div class="profile-dropdown">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="profile-icon-wrapper">
                            <i class="far fa-user"></i>
                            <span class="profile-text">Hello, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                        </div>
                        <ul class="dropdown-menu">
                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li><a href="admin/index.php">Admin Panel</a></li>
                            <?php endif; ?>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    <?php else: ?>
                        <a href="login.php" class="profile-icon-wrapper">
                            <i class="far fa-user"></i>
                            <span class="profile-text">Login / Register</span>
                        </a>
                    <?php endif; ?>
                </div>
                <a href="cart.php" class="cart-icon-premium">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-text">Cart</span>
                    <?php if($cart_count > 0): ?>
                        <span class="cart-badge-premium"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>
                <div class="hamburger">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const searchForm = document.getElementById('searchForm');
    
    if (searchInput && searchSuggestions && searchForm) {
        
        // --- Animated Placeholder Logic ---
        const placeholders = [
            'Search for LED Bulbs...',
            'Search for High-quality Wires...',
            'Search for Decorative Lights...',
            'Search for Electrical Tools...'
        ];
        
        let phIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typeSpeed = 100;
        let animationTimeout;
        let isFocused = false;

        function typePlaceholder() {
            if (isFocused) return;
            
            const currentText = placeholders[phIndex];
            
            if (isDeleting) {
                searchInput.setAttribute('placeholder', currentText.substring(0, charIndex - 1));
                charIndex--;
                typeSpeed = 40; // Faster delete
            } else {
                searchInput.setAttribute('placeholder', currentText.substring(0, charIndex + 1));
                charIndex++;
                typeSpeed = 80; // Normal type
            }

            if (!isDeleting && charIndex === currentText.length) {
                isDeleting = true;
                typeSpeed = 2000; // Pause at the end
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phIndex = (phIndex + 1) % placeholders.length;
                typeSpeed = 500; // Pause before new word
            }

            animationTimeout = setTimeout(typePlaceholder, typeSpeed);
        }

        // Start animation immediately if empty
        if (searchInput.value.trim() === '') {
            typePlaceholder();
        }

        searchInput.addEventListener('focus', function() {
            isFocused = true;
            clearTimeout(animationTimeout);
            this.setAttribute('placeholder', 'Search...');
        });

        searchInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                isFocused = false;
                charIndex = 0;
                isDeleting = false;
                typePlaceholder();
            }
        });
        // ----------------------------------
        searchInput.addEventListener('keyup', function() {
            const query = this.value.trim();
            
            if (query.length > 0) {
                fetch('search_suggestions.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        searchSuggestions.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'suggestion-item';
                                div.textContent = item.name;
                                div.addEventListener('click', function() {
                                    searchInput.value = item.name;
                                    searchForm.submit();
                                });
                                searchSuggestions.appendChild(div);
                            });
                            searchSuggestions.style.display = 'block';
                        } else {
                            searchSuggestions.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error fetching suggestions:', error));
            } else {
                searchSuggestions.style.display = 'none';
                searchSuggestions.innerHTML = '';
            }
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchForm.contains(e.target)) {
                searchSuggestions.style.display = 'none';
            }
        });
        
        // Show suggestions again if input is focused and not empty
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0 && searchSuggestions.innerHTML !== '') {
                searchSuggestions.style.display = 'block';
            }
        });
    }
});
</script>

<main>
