<div class="card" style="max-width: 400px; margin: 50px auto; padding: 40px;">
    <h2 style="text-align:center; margin-bottom: 20px;">Welcome Back</h2>
    
    <div id="login-error" style="display:none; background:#ffebeb; color:#d63031; padding:10px; border-radius:8px; margin-bottom:15px; font-size:14px; text-align:center;"></div>

    <form onsubmit="handleLogin(event)">
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:8px; font-size:12px; font-weight:bold; color:#666;">EMAIL ADDRESS</label>
            <input type="email" id="email" required placeholder="name@school.com" 
                   style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px;">
        </div>
        
        <div style="margin-bottom: 30px;">
            <label style="display:block; margin-bottom:8px; font-size:12px; font-weight:bold; color:#666;">PASSWORD</label>
            <input type="password" id="password" required placeholder="••••••••" 
                   style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px;">
        </div>

        <button type="submit" id="btn-login" class="btn" style="width:100%; font-size:16px; padding:12px;">Sign In</button>
    </form>
    
    <div style="text-align:center; margin-top: 25px; border-top:1px solid #eee; padding-top:20px;">
        <span style="color:#888;">New to Compass?</span>
        <br>
        <a href="?page=register" style="color:var(--primary); font-weight:700; text-decoration:none; display:inline-block; margin-top:5px;">Create an Account</a>
    </div>
</div>

<script>
async function handleLogin(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btn-login');
    const err = document.getElementById('login-error');
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // 1. GET THE SECURITY TOKEN
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    
    btn.innerText = "Verifying...";
    btn.disabled = true;
    err.style.display = 'none';

    try {
        const res = await fetch('api.php?action=login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrf // SEND TOKEN IN HEADER
            },
            body: JSON.stringify({ 
                email: email, 
                password: password,
                csrf_token: csrf // SEND TOKEN IN BODY (Backup)
            })
        });

        const json = await res.json();
        
        if (json.status === 'success') {
            btn.innerText = "Success! Redirecting...";
            btn.style.background = "#34C759";
            window.location.href = 'index.php';
        } else {
            throw new Error(json.message || "Login failed");
        }
    } catch (error) {
        btn.innerText = "Sign In";
        btn.disabled = false;
        btn.style.background = "";
        err.innerText = error.message;
        err.style.display = 'block';
    }
}
</script>