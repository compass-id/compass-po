<?php
// view_home.php - MODERN MARKETING PAGE (B2B Distributor Focus + Timer + MOQ Table + Animated Testimonial)
$pdo = getDB();
$s = $pdo->query("SELECT setting_key, setting_value FROM system_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$moq2 = $s['moq_tier_2'] ?? 20; 
$moq3 = $s['moq_tier_3'] ?? 200;
$disc2 = $s['discount_tier_2'] ?? 10; 
$disc3 = $s['discount_tier_3'] ?? 20;
$eb_deadline = $s['early_bird_deadline'] ?? '2026-04-30';
?>

<style>
    /* Custom Styles for Home Page Features */
    .cd-box {
        background: #fff;
        color: #333;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        min-width: 65px;
        font-weight: bold;
        text-align: center;
    }
    .cd-box span { font-size: 28px; color: var(--primary); display: block; line-height: 1.2; }
    .cd-box small { font-size: 11px; text-transform: uppercase; color: #888; }
    
    /* Animated Testimonial Marquee */
    .marquee-container {
        width: 100%;
        overflow: hidden;
        padding: 20px 0;
        position: relative;
        background: #f9f9f9;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    .marquee-track {
        display: flex;
        width: max-content;
        animation: scroll-left 25s linear infinite;
    }
    .marquee-track:hover {
        animation-play-state: paused; /* Pause on hover so users can read */
    }
    @keyframes scroll-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); } /* Shifts exactly half the track to loop perfectly */
    }
    .marquee-track .review-card {
        width: 320px;
        margin: 0 15px;
        white-space: normal;
        flex-shrink: 0;
    }
</style>

<div class="hero-section">
    <div class="container">
        <div style="font-size: 14px; font-weight: 700; opacity: 0.8; letter-spacing: 2px; margin-bottom: 10px;">PRE-ORDER 2026 OPEN NOW</div>
        <h1 class="hero-title">Scale Your Distribution Business with Exclusive Publisher Discounts.</h1>
        <p class="hero-subtitle">
            Our latest collection of educational books is now open for agent and small-distributor orders at special wholesale rates. 
            Allowing you to offer competitive rates to schools, parents, readers communities, and online shop customers in your region while maintaining healthy profits.
            <br><br>
            <strong>Stock your shelves today and drive the future of education in your area.</strong>
        </p>

        <div class="countdown-wrapper" style="margin: 30px auto 0; padding: 20px; background: rgba(255,255,255,0.1); border-radius: 12px; display: inline-block;">
            <div style="font-size:13px; font-weight:800; color: #FFD60A; margin-bottom:10px; letter-spacing: 1px;">
                <ion-icon name="time-outline" style="vertical-align: text-bottom; font-size: 16px;"></ion-icon> EXTRA 5% EARLY BIRD DISCOUNT ENDS IN:
            </div>
            <div id="eb-countdown" style="display:flex; gap:10px; justify-content:center;">
                <div class="cd-box"><span id="cd-days">00</span><small>Days</small></div>
                <div class="cd-box"><span id="cd-hours">00</span><small>Hours</small></div>
                <div class="cd-box"><span id="cd-mins">00</span><small>Mins</small></div>
                <div class="cd-box"><span id="cd-secs">00</span><small>Secs</small></div>
            </div>
        </div>

        <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
            <a href="?page=catalog" class="btn" style="background: white; color: var(--primary);">View Catalog</a>
            <a href="?page=form" class="btn" style="background: white; color: var(--primary);">Fill Interest Form</a>
        </div>
    </div>
</div>

<div class="container" style="margin-top: -40px;">
    
    <div class="tier-grid">
        <div class="card tier-card">
            <h3>Standard Agent</h3>
            <div class="tier-price">Retail Rate</div>
            <p style="color: #666;">For individual agents & small dropshippers.</p>
            <ul class="tier-features">
                <li><ion-icon name="checkmark-circle"></ion-icon> No Minimum Order</li>
                <li><ion-icon name="checkmark-circle"></ion-icon> Standard Shipping</li>
                <li><ion-icon name="checkmark-circle"></ion-icon> Base Profit Margin</li>
            </ul>
        </div>
        
        <div class="card tier-card" style="border: 2px solid var(--success);">
            <div class="tier-badge">POPULAR</div>
            <h3>Distributor</h3>
            <div class="tier-price" style="color: var(--success);"><?php echo $disc2; ?>% OFF</div>
            <p style="color: #666;">Perfect for regional online shops.</p>
            <ul class="tier-features">
                <li><ion-icon name="checkmark-circle"></ion-icon> <strong><?php echo $moq2; ?>+</strong> Copies</li>
                <li><ion-icon name="checkmark-circle"></ion-icon> Priority Processing</li>
                <li><ion-icon name="checkmark-circle"></ion-icon> Increased Profit Margin</li>
            </ul>
        </div>

        <div class="card tier-card" style="background: #1C1C1E; color: white;">
            <div class="tier-badge" style="background: #FFD60A; color: black;">BEST VALUE</div>
            <h3 style="color: white;">Master Partner</h3>
            <div class="tier-price" style="color: #FFD60A;"><?php echo $disc3; ?>% OFF</div>
            <p style="opacity: 0.7;">For major regional distribution.</p>
            <ul class="tier-features">
                <li style="border-color: #333;"><ion-icon name="checkmark-circle"></ion-icon> <strong><?php echo $moq3; ?>+</strong> Copies</li>
                <li style="border-color: #333;"><ion-icon name="checkmark-circle"></ion-icon> Highest Profit Margins</li>
                <li style="border-color: #333;"><ion-icon name="checkmark-circle"></ion-icon> Dedicated Account Manager</li>
            </ul>
        </div>
    </div>

    <h2 style="text-align: center; margin: 60px 0 20px;">Tiered Price List & MOQ Breakdown</h2>
    <p style="text-align: center; color: #666; max-width: 600px; margin: 0 auto 30px;">
        Take advantage of our clear, straightforward bulk pricing to calculate your exact margins. Combine total book quantities to hit your targeted tier.
    </p>
    <div style="overflow-x:auto; margin-bottom: 60px;">
        <table style="width:100%; max-width:900px; margin:0 auto; background:white; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05); border-collapse:collapse; overflow:hidden;">
            <tr style="background:var(--primary); color:white; text-align:left;">
                <th style="padding:15px 20px;">Partner Level</th>
                <th style="padding:15px 20px;">MOQ (Minimum Order)</th>
                <th style="padding:15px 20px;">Base Discount</th>
                <th style="padding:15px 20px;">Early Bird Bonus</th>
                <th style="padding:15px 20px;">Total Potential Margin</th>
            </tr>
            <tr>
                <td style="padding:15px 20px; border-bottom:1px solid #eee;"><strong>Standard Agent</strong></td>
                <td style="padding:15px 20px; border-bottom:1px solid #eee;">1 - <?php echo $moq2 - 1; ?> copies</td>
                <td style="padding:15px 20px; border-bottom:1px solid #eee;">0%</td>
                <td style="padding:15px 20px; border-bottom:1px solid #eee; color:var(--success); font-weight:bold;">+5% OFF</td>
                <td style="padding:15px 20px; border-bottom:1px solid #eee;"><strong>Up to 5%</strong></td>
            </tr>
            <tr>
                <td style="padding:15px 20px; border-bottom:1px solid #eee;"><strong>Distributor</strong></td>
                <td style="padding:15px 20px; border-bottom:1px solid #eee; color:var(--primary); font-weight:bold;"><?php echo $moq2; ?>+ copies</td>
                <td style="padding:15px 20px; border-bottom:1px solid #eee;"><?php echo $disc2; ?>% OFF</td>
                <td style="padding:15px 20px; border-bottom:1px solid #eee; color:var(--success); font-weight:bold;">+5% OFF</td>
                <td style="padding:15px 20px; border-bottom:1px solid #eee;"><strong>Up to <?php echo $disc2 + 5; ?>%</strong></td>
            </tr>
            <tr style="background:#fefbf0;">
                <td style="padding:15px 20px;"><strong>Master Partner</strong></td>
                <td style="padding:15px 20px; color:var(--primary); font-weight:bold;"><?php echo $moq3; ?>+ copies</td>
                <td style="padding:15px 20px;"><?php echo $disc3; ?>% OFF</td>
                <td style="padding:15px 20px; color:var(--success); font-weight:bold;">+5% OFF</td>
                <td style="padding:15px 20px;"><strong>Up to <?php echo $disc3 + 5; ?>%</strong></td>
            </tr>
        </table>
    </div>

    <h2 style="text-align: center; margin: 60px 0 40px;">Agent Benefits</h2>
    <div class="review-grid" style="margin-bottom: 60px;">
        <div class="review-card" style="text-align: center; padding: 30px 20px;">
            <ion-icon name="trending-down-outline" style="font-size: 40px; color: var(--primary);"></ion-icon>
            <h3 style="margin-top: 15px;">Tiered Discount Structure</h3>
            <p style="color: #666; margin-top: 10px;">The more you order, the more you save. Increase your purchasing volume to unlock our highest margin brackets.</p>
        </div>
        <div class="review-card" style="text-align: center; padding: 30px 20px;">
            <ion-icon name="book-outline" style="font-size: 40px; color: var(--primary);"></ion-icon>
            <h3 style="margin-top: 15px;">Curated Content</h3>
            <p style="color: #666; margin-top: 10px;">Books specifically designed to meet the curriculum needs of regional students, ensuring high demand and fast turnover.</p>
        </div>
        <div class="review-card" style="text-align: center; padding: 30px 20px;">
            <ion-icon name="megaphone-outline" style="font-size: 40px; color: var(--primary);"></ion-icon>
            <h3 style="margin-top: 15px;">Market Support</h3>
            <p style="color: #666; margin-top: 10px;">We provide the marketing materials, digital assets, and support you need to sell effectively to your network.</p>
        </div>
    </div>
</div>

<h2 style="text-align: center; margin: 20px 0 30px;">Trusted by Distribution Partners</h2>
<div class="marquee-container">
    <div class="marquee-track">
        <div class="card review-card">
            <div class="review-stars" style="color:#FFD60A; font-size:18px;">★★★★★</div>
            <p style="margin-top:10px; font-style:italic;">"The pre-order wholesale structure gave us a massive competitive edge. We secured the Master Partner tier and scaled our online sales perfectly."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px; color:var(--primary);">- Budi S., Regional Distributor (Surabaya)</div>
        </div>
        <div class="card review-card">
            <div class="review-stars" style="color:#FFD60A; font-size:18px;">★★★★★</div>
            <p style="margin-top:10px; font-style:italic;">"As an independent agent, the flexibility to hit the 20 MOQ tier easily allowed me to offer great deals to local schools while keeping my profits high."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px; color:var(--primary);">- Sarah J., Independent Agent (Jakarta)</div>
        </div>
        <div class="card review-card">
            <div class="review-stars" style="color:#FFD60A; font-size:18px;">★★★★★</div>
            <p style="margin-top:10px; font-style:italic;">"Fast shipping, highly demanded titles, and incredible customer support. They make B2B educational supplying extremely easy."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px; color:var(--primary);">- Anita R., Bookstore Owner (Bandung)</div>
        </div>
        <div class="card review-card">
            <div class="review-stars" style="color:#FFD60A; font-size:18px;">★★★★★</div>
            <p style="margin-top:10px; font-style:italic;">"The pre-order wholesale structure gave us a massive competitive edge. We secured the Master Partner tier and scaled our online sales perfectly."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px; color:var(--primary);">- Budi S., Regional Distributor (Surabaya)</div>
        </div>
        <div class="card review-card">
            <div class="review-stars" style="color:#FFD60A; font-size:18px;">★★★★★</div>
            <p style="margin-top:10px; font-style:italic;">"As an independent agent, the flexibility to hit the 20 MOQ tier easily allowed me to offer great deals to local schools while keeping my profits high."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px; color:var(--primary);">- Sarah J., Independent Agent (Jakarta)</div>
        </div>
        <div class="card review-card">
            <div class="review-stars" style="color:#FFD60A; font-size:18px;">★★★★★</div>
            <p style="margin-top:10px; font-style:italic;">"Fast shipping, highly demanded titles, and incredible customer support. They make B2B educational supplying extremely easy."</p>
            <div style="margin-top: 15px; font-weight: bold; font-size: 14px; color:var(--primary);">- Anita R., Bookstore Owner (Bandung)</div>
        </div>
    </div>
</div>

<div class="container">
    <h2 style="text-align: center; margin: 80px 0 40px;">Frequently Asked Questions</h2>
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">When will I receive my pre-order inventory? <ion-icon name="chevron-down"></ion-icon></div>
            <div class="faq-answer">Estimated delivery starts May 2026. You can track your shipment status directly in your dashboard.</div>
        </div>
        <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">Can I pay in installments? <ion-icon name="chevron-down"></ion-icon></div>
            <div class="faq-answer">Yes! You can pay a deposit (DP) now and settle the rest monthly via our 12-month payment schedule. Upload payment proofs in your profile.</div>
        </div>
        <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">How do I get the Distributor or Master Partner Discount? <ion-icon name="chevron-down"></ion-icon></div>
            <div class="faq-answer">Simply add the required MOQ (e.g., <?php echo $moq3; ?> copies) to your cart. The wholesale discount applies automatically at checkout across all mixed titles.</div>
        </div>
    </div>
</div>

<div class="contact-section" style="margin-top: 80px;">
    <div class="container">
        <h2>Ready to scale your business?</h2>
        <p style="opacity: 0.7;">Contact our team for a personalized distribution consultation.</p>
        
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set deadline based on database setting
        const deadlineStr = "<?php echo $eb_deadline; ?>T23:59:59";
        const deadline = new Date(deadlineStr).getTime();
        
        const timerInterval = setInterval(function() {
            const now = new Date().getTime();
            const d = deadline - now;
            
            if (d < 0) {
                clearInterval(timerInterval);
                document.getElementById("eb-countdown").innerHTML = "<div style='font-size:24px; font-weight:bold; color:red; text-align:center;'>EARLY BIRD EXPIRED</div>";
                return;
            }
            
            const days = Math.floor(d / (1000 * 60 * 60 * 24));
            const hours = Math.floor((d % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((d % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((d % (1000 * 60)) / 1000);
            
            document.getElementById("cd-days").innerText = days.toString().padStart(2, '0');
            document.getElementById("cd-hours").innerText = hours.toString().padStart(2, '0');
            document.getElementById("cd-mins").innerText = mins.toString().padStart(2, '0');
            document.getElementById("cd-secs").innerText = secs.toString().padStart(2, '0');
        }, 1000);
    });
</script>