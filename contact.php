<?php
require_once 'includes/header.php';
?>

<section class="hero" style="height: 40vh; background: linear-gradient(rgba(0, 21, 41, 0.8), rgba(0, 21, 41, 0.8)), url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80') no-repeat center center/cover;">
    <div class="container" style="display: flex; flex-direction: column; justify-content: center; height: 100%; text-align: center;">
        <h1 style="font-size: 3.5rem; margin-bottom: 1rem; color: #fff;">Visit Joshi Trading</h1>
        <p style="font-size: 1.2rem; color: var(--light-slate); max-width: 600px; margin: 0 auto;">Your trusted partner for premium quality electrical solutions.</p>
    </div>
</section>

<section class="section-padding container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start; margin-top: 2rem;">
        
        <!-- Shop Details -->
        <div class="glass-panel" style="padding: 2.5rem;">
            <h3 style="color: var(--electric-gold); font-size: 1.8rem; margin-bottom: 1.5rem;">Store Location</h3>
            
            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: var(--navy-blue); margin-bottom: 0.5rem; font-size: 1.2rem;">Address</h4>
                <p style="color: var(--text-light); font-size: 1.1rem; line-height: 1.6;">
                    <strong>Joshi Trading</strong><br>
                    Main Road, Rajbiraj-7<br>
                    Saptari, Nepal
                </p>
                <p style="color: var(--light-slate); font-size: 0.95rem; margin-top: 0.5rem; font-style: italic;">
                    (Located in front of Krishi Bikas Bank, Rajbiraj)
                </p>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: var(--navy-blue); margin-bottom: 0.5rem; font-size: 1.2rem;">Contact Details</h4>
                <p style="color: var(--text-light); font-size: 1.1rem; line-height: 1.6;">
                    📞 +977 9841954134<br>
                    📞 +977 9841815784<br>
                    ✉️ bidhyajoshi21@gmail.com
                </p>
            </div>
            
            <div>
                <h4 style="color: var(--navy-blue); margin-bottom: 0.5rem; font-size: 1.2rem;">Business Hours</h4>
                <p style="color: var(--text-light); font-size: 1.1rem; line-height: 1.6;">
                    Sunday - Friday: 9:00 AM - 7:00 PM<br>
                    Saturday: Closed
                </p>
            </div>
        </div>

        <!-- Route Finder -->
        <div class="glass-panel" style="padding: 2.5rem; background: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h3 style="color: var(--navy-blue); font-size: 1.8rem; margin-bottom: 1.5rem;">Find the Route</h3>
            <p style="color: #444444 !important; margin-bottom: 1.5rem;">Need directions to our shop? Enter your current location below and we will map the route for you.</p>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="userLocation" style="color: var(--navy-light); font-weight: 500;">Apni location dalein (Enter your location)</label>
                <input type="text" id="userLocation" class="form-control" placeholder="e.g. Kathmandu, Nepal or specific landmark" style="margin-top: 0.5rem;">
            </div>
            
            <button onclick="getDirections()" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; display: flex; justify-content: center; align-items: center; gap: 10px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Get Directions
            </button>
        </div>
        
    </div>
</section>

<script>
function getDirections() {
    const userLocation = document.getElementById('userLocation').value.trim();
    if (!userLocation) {
        alert('Please enter your location first.');
        return;
    }
    
    // Construct Google Maps URL
    const destination = 'Joshi Trading Rajbiraj';
    const encodedOrigin = encodeURIComponent(userLocation);
    const encodedDestination = encodeURIComponent(destination);
    
    const mapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${encodedOrigin}&destination=${encodedDestination}`;
    
    // Open in new tab
    window.open(mapsUrl, '_blank');
}
</script>

<?php require_once 'includes/footer.php'; ?>
