<?php require_once 'includes/header.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
<style>
    body {
        /* Soft gradient background to make photos pop-out */
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%) !important;
        color: #1f2937 !important;
    }
    .owners-section {
        padding: 120px 20px 80px;
        text-align: center;
        min-height: calc(100vh - 200px);
    }
    
    /* Elegant Typography */
    .legacy-heading {
        font-family: 'Playfair Display', serif;
        color: var(--navy-blue);
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
    }
    
    .legacy-text {
        font-size: 1.25rem;
        color: var(--slate-gray);
        max-width: 800px;
        margin: 0 auto 4rem auto;
        line-height: 1.8;
    }

    .owners-grid {
        display: flex;
        justify-content: center;
        gap: 3rem;
        flex-wrap: wrap;
    }
    
    .owner-card {
        max-width: 400px; /* Slightly wider for the quotes */
        text-align: center;
        background: rgba(255, 255, 255, 0.5);
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    
    .owner-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .owner-photo {
        width: 100%;
        height: 380px;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        margin-bottom: 1.5rem;
    }
    
    .owner-name {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--navy-blue);
        margin-bottom: 0.8rem;
        font-family: 'Playfair Display', serif;
    }
    
    .owner-quote {
        font-size: 1.1rem;
        color: var(--slate-gray);
        line-height: 1.6;
        font-style: italic;
        margin: 0;
    }

    /* Smooth Fade-in animation */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInAnim 1s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
    }
    
    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.4s; }
    .delay-3 { animation-delay: 0.6s; }

    @keyframes fadeInAnim {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<section class="owners-section container">
    <h1 class="legacy-heading fade-in">A 3-Generation Legacy of Trust</h1>
    <p class="legacy-text fade-in delay-1">
        Joshi Trading is a symbol of excellence built on a foundation of family and trust. Founded by the visionary Mr. Bhawarlal Joshi, the business has grown through decades of hard work and dedication. Today, the legacy continues as he leads the business alongside his sons, Mr. Baijnath Joshi and Mr. Omprakash Joshi. Together, three generations of the Joshi family work hand-in-hand to provide premium electrical solutions, ensuring safety and innovation in every home.
    </p>

    <div class="owners-grid fade-in delay-2">
        <div class="owner-card fade-in delay-2">
            <img src="assets/images/grandfather.jpg" alt="Mr. Bhawarlal Joshi" class="owner-photo" onerror="this.src='https://images.unsplash.com/photo-1544717302-de2939b7ef71?auto=format&fit=crop&w=400&q=80'">
            <h3 class="owner-name">Mr. Bhawarlal Joshi</h3>
            <p class="owner-quote">"The Visionary Founder: With decades of wisdom, he laid the foundation of honesty and expertise that defines us today."</p>
        </div>
        <div class="owner-card fade-in delay-3">
            <img src="assets/images/father.jpg" alt="Mr. Baijnath & Mr. Omprakash Joshi" class="owner-photo" onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80'">
            <h3 class="owner-name">Mr. Baijnath Joshi & Mr. Omprakash Joshi</h3>
            <p class="owner-quote">"The Modern Leaders: Bringing innovation and dedicated customer service to ensure every home stays bright and powered."</p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
