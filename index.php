<?php
session_start();
require_once 'includes/db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $name = trim($_POST['name'] ?? '');
    $rating = (int)($_POST['rating'] ?? 0);
    $comments = trim($_POST['comments'] ?? '');
    
    if (empty($name) || $rating < 1 || $rating > 5 || empty($comments)) {
        $error = 'Please fill in all fields and provide a valid rating.';
    } else {
        $stmt = $conn->prepare("INSERT INTO feedbacks (name, rating, comments) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $rating, $comments);
        if ($stmt->execute()) {
            $message = 'Thank you for your valuable feedback!';
        } else {
            $error = 'There was an error saving your feedback. Please try again.';
        }
    }
}

// Fetch recent feedbacks for the slider
$feedbacks = $conn->query("SELECT * FROM feedbacks ORDER BY created_at DESC LIMIT 5");

require_once 'includes/header.php'; 
?>

<div class="slider-container">
    <div class="slide slide-1 active">
        <div class="slide-content reveal-slide">
            <h1 class="glow-text" style="min-height: 80px;">Joshi Trading: Experience <span class="typewriter" data-words='["Safety", "Durability", "Innovation"]'></span></h1>
            <p data-aos="fade-up" data-aos-delay="200">Find the best quality Wires, Switches, and Fans.</p>
            <a href="products.php" class="btn-slider">Shop Our Collection</a>
        </div>
    </div>
    
    <div class="slide slide-2">
        <div class="slide-content reveal-slide">
            <h1 class="glow-text">Stay Cool & Bright this Season!</h1>
            <p>Top-rated Fans and decorative Bulbs. durable & efficient.</p>
            <a href="products.php?category=fans" class="btn-slider">View Fans & Bulbs</a>
        </div>
    </div>
    
    <div class="slide slide-3">
        <div class="slide-content reveal-slide">
            <h1 class="glow-text">Safe, Secure, Reliable Solutions.</h1>
            <p>Premium quality switches and heaters. Trusted by hundreds.</p>
            <a href="contact.php" class="btn-slider">Visit Contact Page</a>
        </div>
    </div>

    <div class="slider-nav">
        <div class="slider-dot active" onclick="goToSlide(0)"></div>
        <div class="slider-dot" onclick="goToSlide(1)"></div>
        <div class="slider-dot" onclick="goToSlide(2)"></div>
    </div>
</div>
<section class="section-padding container reveal" data-aos="fade-up">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h2>Shop by Category</h2>
        <p style="color: var(--light-slate); max-width: 600px; margin: 0 auto;">Browse our wide range of electrical products by category.</p>
    </div>
    
    <div class="product-gallery" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <a href="products.php?category=fans" class="glass-panel" style="text-align: center; text-decoration: none; color: inherit; padding: 2.5rem 1rem;" data-aos="zoom-in" data-aos-delay="100">
            <div style="font-size: 3rem; color: var(--electric-gold); margin-bottom: 1.5rem;"><i class="fas fa-fan"></i></div>
            <h3 style="margin-bottom: 0.5rem;">FANS</h3>
            <p style="color: var(--light-slate); font-size: 0.9rem;">Ceiling, Table & Exhaust</p>
        </a>
        <a href="products.php?category=wires" class="glass-panel" style="text-align: center; text-decoration: none; color: inherit; padding: 2.5rem 1rem;" data-aos="zoom-in" data-aos-delay="200">
            <div style="font-size: 3rem; color: var(--electric-gold); margin-bottom: 1.5rem;"><i class="fas fa-plug"></i></div>
            <h3 style="margin-bottom: 0.5rem;">WIRES</h3>
            <p style="color: var(--light-slate); font-size: 0.9rem;">House Wires & Cables</p>
        </a>
        <a href="products.php?category=lighting" class="glass-panel" style="text-align: center; text-decoration: none; color: inherit; padding: 2.5rem 1rem;" data-aos="zoom-in" data-aos-delay="300">
            <div style="font-size: 3rem; color: var(--electric-gold); margin-bottom: 1.5rem;"><i class="fas fa-lightbulb"></i></div>
            <h3 style="margin-bottom: 0.5rem;">LIGHTING</h3>
            <p style="color: var(--light-slate); font-size: 0.9rem;">LED Bulbs & Panel Lights</p>
        </a>
        <a href="products.php?category=tools" class="glass-panel" style="text-align: center; text-decoration: none; color: inherit; padding: 2.5rem 1rem;" data-aos="zoom-in" data-aos-delay="400">
            <div style="font-size: 3rem; color: var(--electric-gold); margin-bottom: 1.5rem;"><i class="fas fa-tools"></i></div>
            <h3 style="margin-bottom: 0.5rem;">TOOLS</h3>
            <p style="color: var(--light-slate); font-size: 0.9rem;">Switches & Accessories</p>
        </a>
    </div>
</section>

<section class="section-padding container reveal" data-aos="fade-up">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h2>Why Choose Joshi Trading?</h2>
        <p style="color: var(--light-slate); max-width: 600px; margin: 0 auto;">We provide top-tier electrical equipment with a focus on durability, safety, and modern design aesthetics.</p>
    </div>
    
    <div class="product-gallery">
        <div class="glass-panel" style="text-align: center;" data-aos="fade-up" data-aos-delay="100">
            <div style="font-size: 3rem; color: var(--electric-gold); margin-bottom: 1rem;">⚡</div>
            <h3>High Quality</h3>
            <p style="color: var(--light-slate);">Industry certified products meeting global standards.</p>
        </div>
        <div class="glass-panel" style="text-align: center;" data-aos="fade-up" data-aos-delay="200">
            <div style="font-size: 3rem; color: var(--electric-gold); margin-bottom: 1rem;">🛡️</div>
            <h3>Reliable Support</h3>
            <p style="color: var(--light-slate);">24/7 technical assistance for all our enterprise clients.</p>
        </div>
        <div class="glass-panel" style="text-align: center;" data-aos="fade-up" data-aos-delay="300">
            <div style="font-size: 3rem; color: var(--electric-gold); margin-bottom: 1rem;">🚀</div>
            <h3>Fast Delivery</h3>
            <p style="color: var(--light-slate);">Express shipping available nationwide.</p>
        </div>
    </div>
</section>

<section id="feedback-section" class="section-padding" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 4rem 1rem;" data-aos="fade-up">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="color: var(--navy-blue);">What Our Customers Say</h2>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success" style="background: #10b981; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 2rem; text-align: center; max-width: 600px; margin-left: auto; margin-right: auto;"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 2rem; text-align: center; max-width: 600px; margin-left: auto; margin-right: auto;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            <!-- Left Side: Recent Reviews Slider -->
            <div class="glass-panel" style="background: #ffffff; padding: 2.5rem; height: 100%;">
                <h3 style="margin-bottom: 1.5rem; color: var(--navy-blue);">Recent Reviews</h3>
                <div class="feedback-slider">
                    <?php if ($feedbacks && $feedbacks->num_rows > 0): ?>
                        <div class="feedback-track">
                            <?php while($fb = $feedbacks->fetch_assoc()): ?>
                                <div class="feedback-slide">
                                    <div style="color: var(--electric-gold); font-size: 1.5rem; margin-bottom: 0.5rem;"><?= str_repeat('★', $fb['rating']) . str_repeat('☆', 5 - $fb['rating']) ?></div>
                                    <p style="font-style: italic; color: #475569; margin-bottom: 1rem;">"<?= htmlspecialchars($fb['comments']) ?>"</p>
                                    <strong style="color: var(--navy-blue);">- <?= htmlspecialchars($fb['name']) ?></strong>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--light-slate);">No reviews yet. Be the first to leave one!</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Side: Leave a Feedback -->
            <div class="glass-panel" style="background: #ffffff; padding: 2.5rem;">
                <h3 style="margin-bottom: 1.5rem; color: var(--navy-blue);">Leave a Quick Feedback</h3>
                <form action="index.php" method="POST">
                    <input type="hidden" name="submit_feedback" value="1">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label style="color: var(--navy-light); font-weight: 500;">Your Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label style="color: var(--navy-light); font-weight: 500;">Rating</label>
                        <div style="display: flex; gap: 0.5rem; margin-top: 0.2rem; flex-direction: row-reverse; justify-content: flex-end;" class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" required>
                            <label for="star5" title="5 stars" style="font-size: 1.5rem; cursor: pointer; color: #cbd5e1;">★</label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4" title="4 stars" style="font-size: 1.5rem; cursor: pointer; color: #cbd5e1;">★</label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3" title="3 stars" style="font-size: 1.5rem; cursor: pointer; color: #cbd5e1;">★</label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2" title="2 stars" style="font-size: 1.5rem; cursor: pointer; color: #cbd5e1;">★</label>
                            <input type="radio" id="star1" name="rating" value="1">
                            <label for="star1" title="1 star" style="font-size: 1.5rem; cursor: pointer; color: #cbd5e1;">★</label>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label style="color: var(--navy-light); font-weight: 500;">Comments</label>
                        <textarea name="comments" class="form-control" rows="3" placeholder="Share your experience..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; font-size: 1rem;">Submit Feedback</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.star-rating input[type="radio"] { display: none; }
.star-rating label:hover, .star-rating label:hover ~ label, .star-rating input[type="radio"]:checked ~ label { color: var(--electric-gold) !important; }
.feedback-slider { overflow: hidden; position: relative; width: 100%; }
.feedback-track { display: flex; transition: transform 0.5s ease-in-out; }
.feedback-slide { min-width: 100%; padding-right: 1rem; box-sizing: border-box; }
@media (max-width: 768px) {
    .section-padding .container > div:last-child { grid-template-columns: 1fr !important; }
}
</style>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const slideCount = slides.length;
    let slideInterval;

    function goToSlide(index) {
        slides[currentSlide].classList.remove('active');
        dots[currentSlide].classList.remove('active');
        
        currentSlide = index;
        
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
        resetInterval();
    }

    function nextSlide() {
        let next = (currentSlide + 1) % slideCount;
        goToSlide(next);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    }

    resetInterval();

    // Feedback Slider Script
    const fbTrack = document.querySelector('.feedback-track');
    if (fbTrack) {
        const fbSlides = document.querySelectorAll('.feedback-slide');
        let currentFbSlide = 0;
        if (fbSlides.length > 1) {
            setInterval(() => {
                currentFbSlide = (currentFbSlide + 1) % fbSlides.length;
                fbTrack.style.transform = `translateX(-${currentFbSlide * 100}%)`;
            }, 4000);
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>
