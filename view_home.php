<?php
// view_home.php - MODERN MARKETING PAGE
$pdo = getDB();
$s = $pdo->query("SELECT setting_key, setting_value FROM system_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$moq2 = $s['moq_tier_2'] ?? 20; $moq3 = $s['moq_tier_3'] ?? 200;
$disc2 = $s['discount_tier_2'] ?? 10; $disc3 = $s['discount_tier_3'] ?? 20;
$books = $pdo->query("SELECT * FROM books LIMIT 4")->fetchAll();
?>

<div class="hero-section">
    <div class="container">
        <div style="font-size: 14px; font-weight: 700; opacity: 0.8; letter-spacing: 2px; margin-bottom: 10px;">PRE-ORDER 2026 OPEN NOW</div>
        <h1 class="hero-title">Equip Your School for the Future.</h1>
        <p class="hero-subtitle">Secure the best educational resources at the lowest prices of the year. Exclusive institutional tiers available until April 30.</p>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="?page=catalog" class="btn" style="background: white; color: var(--primary);">View Catalog</a>
            <a href="?page=form" class="btn" style="background: white; color: var(--primary);">Fill Interest Form</a>
        </div>
        <div class="hero-badges">
            <span><ion-icon name="checkmark-circle"></ion-icon> Official Distributor</span>
            <span><ion-icon name="time"></ion-icon> Early Bird +5% OFF</span>
            <span><ion-icon name="shield-checkmark"></ion-icon> 100% Guaranteed</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top: -40px;">
    
    <div class="tier-grid">
        <div class="card tier-card">
            <h3>Retail</h3>
            <div class="tier-price">Standard</div>
            <p style="color: #666;">For individual learners & small groups.</p>
            <ul class="tier-features">
                <li><ion-icon name="checkmark-circle"></ion-icon> No Minimum Order</li>
                <li><ion-icon name="checkmark-circle"></ion-icon> Standard Shipping</li>
                <li><ion-icon name="checkmark-circle"></ion-icon> Customer Support</li>
            </ul>
        </div>
        
        <div class="card tier-card" style="border: 2px solid var(--success);">
            <div class="tier-badge">POPULAR</div>
            <h3>Class Set</h3>
            <div class="tier-price" style="color: var(--success);"><?php echo $disc2; ?>% OFF</div>
            <p style="color: #666;">Perfect for classrooms.</p>
            <ul class="tier-features">
                <li><ion-icon name="checkmark-circle"></ion-icon> <strong><?php echo $moq2; ?>+</strong> Copies</li>
                <li><ion-icon name="checkmark-circle"></ion-icon> Free Teacher Guides</li>
                <li><ion-icon name="checkmark-circle"></ion-icon> Priority Processing</li>
            </ul>
        </div>

        <div class="card tier-card" style="background: #1C1C1E; color: white;">
            <div class="tier-badge" style="background: #FFD60A; color: black;">BEST VALUE</div>
            <h3 style="color: white;">Partner</h3>
            <div class="tier-price" style="color: #FFD60A;"><?php echo $disc3; ?>% OFF</div>
            <p style="opacity: 0.7;">For school-wide adoption.</p>
            <ul class="tier-features">
                <li style="border-color: #333;"><ion-icon name="checkmark-circle"></ion-icon> <strong><?php echo $moq3; ?>+</strong> Copies</li>
                <li style="border-color: #333;"><ion-icon name="checkmark-circle"></ion-icon> <strong>Free Training</strong> Session</li>
                <li style="border-color: #333;"><ion-icon name="checkmark-circle"></ion-icon> Dedicated Account Manager</li>
            </ul>
        </div>
    </div>

    <h2 style="text-align: center; margin: 60px 0 40px;">Trusted by Educators</h2>
    <div class="review-grid">
        <div class="review-card">
            <div class="review-stars">★★★★★</div>
            <p>"The pre-order process was seamless. We saved 25% on our bulk order for the new academic year."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px;">- Sarah J., Principal (Jakarta)</div>
        </div>
        <div class="review-card">
            <div class="review-stars">★★★★★</div>
            <p>"Compass Publishing's books are top tier. The Partner benefits including the training were invaluable."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px;">- Budi S., English Coord (Surabaya)</div>
        </div>
        <div class="review-card">
            <div class="review-stars">★★★★★</div>
            <p>"Fast shipping and great customer service. Highly recommend the Odyssey series."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px;">- Anita R., Teacher (Bandung)</div>
        </div>
    </div>

    <h2 style="text-align: center; margin: 80px 0 40px;">Frequently Asked Questions</h2>
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">When will I receive my pre-order? <ion-icon name="chevron-down"></ion-icon></div>
            <div class="faq-answer">Estimated delivery starts May 2026. You can track shipment status in your dashboard.</div>
        </div>
        <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">Can I pay in installments? <ion-icon name="chevron-down"></ion-icon></div>
            <div class="faq-answer">Yes! You can pay a deposit now and settle the rest monthly. Upload payment proofs in your profile.</div>
        </div>
        <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">How do I get the Partner Discount? <ion-icon name="chevron-down"></ion-icon></div>
            <div class="faq-answer">Simply add <?php echo $moq3; ?> or more items to your cart. The discount applies automatically at checkout.</div>
        </div>
    </div>

</div>

<div class="contact-section">
    <div class="container">
        <h2>Ready to upgrade your curriculum?</h2>
        <p style="opacity: 0.7;">Contact our team for a personalized consultation.</p>
        
        <div class="contact-grid">
            <div>
                <ion-icon name="location" style="font-size: 24px; color: var(--primary);"></ion-icon>
                <h4>Headquarters</h4>
                <p style="font-size: 14px; opacity: 0.7;">Compass Publishing Indonesia<br>Jakarta Selatan, 12345</p>
            </div>
            <div>
                <ion-icon name="call" style="font-size: 24px; color: var(--primary);"></ion-icon>
                <h4>Phone / WhatsApp</h4>
                <p style="font-size: 14px; opacity: 0.7;">+62 812 3456 7890<br>Mon-Fri, 9am - 5pm</p>
            </div>
            <div>
                <ion-icon name="mail" style="font-size: 24px; color: var(--primary);"></ion-icon>
                <h4>Email Support</h4>
                <p style="font-size: 14px; opacity: 0.7;">support@compass.com<br>sales@compass.com</p>
            </div>
        </div>
    </div>
</div>