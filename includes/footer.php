</main>
<footer>
    <div class="container">
        <style>
            .footer-logo {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                margin-bottom: 1rem;
                padding: 10px 20px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 12px;
                transition: all 0.3s ease;
            }
            .footer-logo:hover {
                background: rgba(255, 255, 255, 0.1);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            }
        </style>
        
        <div style="text-align: left; margin-bottom: 0.5rem;">
            <a href="index.php" class="footer-logo">
                <i class="fas fa-bolt" style="color: #FFD700; margin-right: 10px; font-size: 1.8rem; text-shadow: 0 0 15px rgba(255, 215, 0, 0.8);"></i>
                <span style="font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 1.6rem; letter-spacing: 1px; color: #ffffff;">JOSHI TRADING</span>
            </a>
        </div>
        
        <p style="color: #E0E0E0; margin-bottom: 1.5rem; font-size: 1.1rem; text-align: center; letter-spacing: 0.5px;">Premium Quality Electrical Solutions & Automation</p>
        <style>
            .footer-contact { transition: all 0.3s ease; cursor: pointer; display: inline-block; }
            .footer-contact:hover { color: #ffffff !important; text-shadow: 0 0 10px rgba(255, 255, 255, 0.8); transform: translateX(5px); }
        </style>
        <div style="color: #E0E0E0; font-size: 1.1rem; margin-bottom: 2.5rem; line-height: 2;">
            <p style="margin: 0.8rem 0;">
                <span style="color: #FFC107; margin-right: 10px; font-size: 1.2rem;">📍</span> 
                In front of Krishi Bikas Bank, Main Road, Rajbiraj-7, Saptari, Nepal
            </p>
            <p style="margin: 0.8rem 0;" class="footer-contact">
                <span style="color: #FFC107; margin-right: 10px; font-size: 1.2rem;">📞</span> 
                +977 9841954134 | +977 9841815784
            </p>
            <br>
            <p style="margin: 0.8rem 0;" class="footer-contact">
                <span style="color: #FFC107; margin-right: 10px; font-size: 1.2rem;">✉️</span> 
                bidhyajoshi21@gmail.com
            </p>
        </div>
        <p style="color: var(--slate-gray); font-size: 0.9rem;">&copy; <?= date('Y') ?> Joshi Trading. All rights reserved.</p>
    </div>
</footer>

<style id="floating-buttons-styles">
    /* WhatsApp Floating Button */
    .whatsapp-float {
        position: fixed !important;
        width: 65px !important;
        height: 65px !important;
        bottom: 30px !important;
        left: 30px !important;
        background-color: #25D366 !important; /* WhatsApp Green */
        color: #fff !important;
        border-radius: 50% !important;
        text-align: center !important;
        font-size: 35px !important;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.3) !important;
        z-index: 10000 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    .whatsapp-float:hover {
        transform: scale(1.1) !important;
        box-shadow: 0px 6px 20px rgba(37, 211, 102, 0.5) !important;
        color: #fff !important;
    }

    /* AI Chatbot Styles */
    .chatbot-toggler {
        position: fixed !important;
        bottom: 30px !important;
        right: 30px !important;
        width: 75px !important;
        height: 75px !important;
        background: linear-gradient(135deg, #FFD700 0%, #001529 100%) !important; /* Gold/Blue gradient */
        color: #ffffff !important;
        border-radius: 50% !important;
        border: 2px solid #FFD700 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 35px !important;
        cursor: pointer !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.4) !important;
        z-index: 10000 !important;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    .chatbot-toggler:hover {
        transform: scale(1.1) translateY(-5px) !important;
    }

    .chatbot-window {
        position: fixed !important;
        bottom: 120px !important;
        right: 30px !important;
        width: 350px !important;
        background: rgba(0, 21, 41, 0.95) !important;
        backdrop-filter: blur(25px) !important;
        -webkit-backdrop-filter: blur(25px) !important;
        border: 1px solid rgba(255, 215, 0, 0.3) !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important;
        display: none;
        flex-direction: column !important;
        overflow: hidden !important;
        z-index: 10000 !important;
        opacity: 0;
        transform: translateY(40px) scale(0.95);
        transition: opacity 0.4s ease, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .chatbot-window.show {
        display: flex !important;
        opacity: 1 !important;
        transform: translateY(0) scale(1) !important;
    }

    /* Mobile view */
    @media (max-width: 768px) {
        .whatsapp-float, .chatbot-toggler {
            width: 55px !important;
            height: 55px !important;
            font-size: 28px !important;
            bottom: 20px !important;
        }
        .whatsapp-float {
            left: 20px !important;
        }
        .chatbot-toggler {
            right: 20px !important;
        }
        .chatbot-window {
            width: 90% !important;
            bottom: 85px !important;
            right: 5% !important;
        }
    }
</style>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/9779824728137" target="_blank" class="whatsapp-float">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- AI Chatbot Widget -->
<button class="chatbot-toggler" id="chatbotToggler">
    <i class="fas fa-robot"></i>
</button>

<div class="chatbot-window" id="chatbotWindow">
    <div class="chat-header">
        <h3><span class="online-dot"></span> Joshi Trading Support</h3>
        <button class="close-btn" id="closeChat"><i class="fas fa-times"></i></button>
    </div>
    <div class="chat-body">
        <div class="chat-message">
            Hello! Welcome to Joshi Trading. How can I assist you today?
        </div>
        <div class="chat-buttons">
            <a href="cart.php" class="action-chip">Track My Order</a>
            <a href="contact.php" class="action-chip">Visit Our Store</a>
            <a href="contact.php" class="action-chip">Talk to Expert</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatbotToggler = document.getElementById('chatbotToggler');
        const chatbotWindow = document.getElementById('chatbotWindow');
        const closeChat = document.getElementById('closeChat');

        if (chatbotToggler && chatbotWindow && closeChat) {
            chatbotToggler.addEventListener('click', () => {
                chatbotWindow.classList.toggle('show');
            });
            closeChat.addEventListener('click', () => {
                chatbotWindow.classList.remove('show');
            });
        }
    });
</script>

<!-- AOS Animation Library JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize Scroll Reveal Animations
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                offset: 50
            });
        }
    });

    // Typewriter Effect Logic
    document.addEventListener('DOMContentLoaded', () => {
        const typewriters = document.querySelectorAll('.typewriter');
        typewriters.forEach(span => {
            const words = JSON.parse(span.getAttribute('data-words'));
            let wordIndex = 0;
            let charIndex = 0;
            let isDeleting = false;

            function type() {
                const currentWord = words[wordIndex];
                
                if (isDeleting) {
                    span.textContent = currentWord.substring(0, charIndex - 1);
                    charIndex--;
                } else {
                    span.textContent = currentWord.substring(0, charIndex + 1);
                    charIndex++;
                }

                let typeSpeed = 100;
                if (isDeleting) typeSpeed /= 2;

                if (!isDeleting && charIndex === currentWord.length) {
                    typeSpeed = 2000; // Pause at end of word
                    isDeleting = true;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                    typeSpeed = 500; // Pause before typing new word
                }

                setTimeout(type, typeSpeed);
            }
            
            if (words && words.length > 0) {
                setTimeout(type, 1000); // Initial start delay
            }
        });
    });
</script>

<script src="assets/js/main.js"></script>
</body>
</html>
