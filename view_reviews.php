<style>
/* CSS-Only Scrolling Carousel */
.review-carousel {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding: 20px 0;
    scroll-snap-type: x mandatory;
    scrollbar-width: none; /* Hide scrollbar Firefox */
}
.review-carousel::-webkit-scrollbar { display: none; } /* Hide scrollbar Chrome */

.review-card {
    min-width: 280px;
    background: white;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    scroll-snap-align: center;
    border: 1px solid #eee;
}
</style>

<div style="margin-top: 40px;">
    <h3>What Educators Say</h3>
    <div class="review-carousel">
        <div class="review-card">
            <div style="color:#FFD60A; margin-bottom:10px;">★★★★★</div>
            <p style="font-size:14px; line-height:1.5;">"The tiered pricing helped our school save 20% on the Odyssey series. Highly recommended!"</p>
            <div style="margin-top:15px; font-weight:700; font-size:12px;">- Principal Sarah, Jakarta</div>
        </div>
        
        <div class="review-card">
            <div style="color:#FFD60A; margin-bottom:10px;">★★★★★</div>
            <p style="font-size:14px; line-height:1.5;">"Very easy pre-order process. The books arrived exactly when promised for the new term."</p>
            <div style="margin-top:15px; font-weight:700; font-size:12px;">- Mr. Budi, Bandung</div>
        </div>

        <div class="review-card">
            <div style="color:#FFD60A; margin-bottom:10px;">★★★★☆</div>
            <p style="font-size:14px; line-height:1.5;">"Customer support was very responsive when we needed to adjust our quantity."</p>
            <div style="margin-top:15px; font-weight:700; font-size:12px;">- Ms. Yulia, Surabaya</div>
        </div>
    </div>
</div>