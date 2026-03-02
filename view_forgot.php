<div class="card" style="max-width: 400px; margin: 50px auto; padding: 30px; text-align: center;">
    <h2>Recovery</h2>
    <p style="color:gray; margin-bottom:20px;">Enter your email to reset your password.</p>
    
    <form onsubmit="event.preventDefault(); alert('Reset link sent to your email (Simulation).'); window.location='?page=login';">
        <input type="email" placeholder="Email Address" required 
               style="width:100%; padding:14px; margin-bottom:20px; border:1px solid #ddd; border-radius:12px;">
        <button type="submit" class="btn">Send Reset Link</button>
    </form>
    <div style="margin-top:20px;">
        <a href="?page=login" style="color:var(--primary); text-decoration:none;">Back to Login</a>
    </div>
</div>