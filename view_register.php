<div class="card" style="max-width: 500px; margin: 50px auto; padding: 40px;">
    <h2 style="text-align:center; margin-bottom: 10px;">Create Account</h2>
    <p style="text-align:center; color:#888; margin-bottom: 25px; font-size:14px;">Join to unlock education pricing tiers.</p>
    
    <div id="reg-error" style="display:none; background:#ffebeb; color:#d63031; padding:10px; border-radius:8px; margin-bottom:15px; font-size:14px;"></div>

    <form onsubmit="handleRegister(event)">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:15px;">
            <div>
                <label style="font-size:11px; font-weight:bold; color:#666;">FULL NAME</label>
                <input type="text" id="reg-name" required class="form-input">
            </div>
            <div>
                <label style="font-size:11px; font-weight:bold; color:#666;">CITY</label>
                <input type="text" id="reg-city" required class="form-input">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:15px;">
            <div>
                <label style="font-size:11px; font-weight:bold; color:#666;">INSTITUTION / SCHOOL</label>
                <input type="text" id="reg-institution" required class="form-input">
            </div>
            <div>
                <label style="font-size:11px; font-weight:bold; color:#666;">POSITION (Jabatan)</label>
                <input type="text" id="reg-position" required placeholder="e.g. Principal" class="form-input">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:15px;">
             <div>
                <label style="font-size:11px; font-weight:bold; color:#666;">EMAIL ADDRESS</label>
                <input type="email" id="reg-email" required class="form-input">
            </div>
             <div>
                <label style="font-size:11px; font-weight:bold; color:#666;">PHONE NUMBER</label>
                <input type="text" id="reg-phone" required placeholder="e.g. 0812..." class="form-input">
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:25px;">
            <div>
                <label style="font-size:11px; font-weight:bold; color:#666;">CREATE PASSWORD</label>
                <input type="password" id="reg-pass" required placeholder="Min 6 chars" minlength="6" class="form-input">
            </div>
            <div>
                <label style="font-size:11px; font-weight:bold; color:#666;">CONFIRM PASSWORD</label>
                <input type="password" id="reg-confirm-pass" required placeholder="Repeat Password" minlength="6" class="form-input">
            </div>
        </div>

        <button type="submit" id="btn-reg" class="btn" style="width:100%; padding:12px;">Create Account</button>
    </form>
    
    <div style="text-align:center; margin-top: 25px;">
        <span style="color:#888;">Already have an account?</span> 
        <a href="?page=login" style="color:var(--primary); font-weight:700; text-decoration:none;">Sign In</a>
    </div>
</div>

<style>
.form-input { width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; font-size:14px; margin-top:5px; }
</style>

<script>
async function handleRegister(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-reg');
    const err = document.getElementById('reg-error');
    
    const pass = document.getElementById('reg-pass').value;
    const confirmPass = document.getElementById('reg-confirm-pass').value;
    
    // Check if passwords match before making the request
    if (pass !== confirmPass) {
        err.innerText = "Passwords do not match.";
        err.style.display = 'block';
        return;
    }

    const data = {
        name: document.getElementById('reg-name').value,
        city: document.getElementById('reg-city').value,
        institution: document.getElementById('reg-institution').value,
        position: document.getElementById('reg-position').value,
        email: document.getElementById('reg-email').value,
        phone: document.getElementById('reg-phone').value,
        password: pass
    };

    btn.innerText = "Creating..."; btn.disabled = true; err.style.display = 'none';

    try {
        const res = await fetch('api.php?action=register', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const json = await res.json();
        
        if (json.status === 'success') {
            alert("Account Created! Please wait for Admin approval before signing in.");
            window.location.href = 'index.php?page=login';
        } else {
            throw new Error(json.message || "Registration failed");
        }
    } catch (error) {
        btn.innerText = "Create Account"; btn.disabled = false;
        err.innerText = error.message; err.style.display = 'block';
    }
}
</script>