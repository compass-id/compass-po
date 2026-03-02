<?php
// view_admin_settings.php - MASTER CONFIGURATION
if (($_SESSION['role'] ?? '') !== 'admin') die("Access Denied");
$pdo = getDB();
$s = $pdo->query("SELECT setting_key, setting_value FROM system_settings")->fetchAll(PDO::FETCH_KEY_PAIR);

// Decode JSON lists for display
$programs = json_decode($s['programs_list'] ?? '[]', true);
$benefits = json_decode($s['benefits_list'] ?? '[]', true);

// Convert arrays to newline-separated strings for textarea editing
$prog_text = implode("\n", $programs);
$ben_text = implode("\n", $benefits);
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
        <h2 style="margin: 0;">System Configuration</h2>
        <p style="color: #666; margin: 5px 0 0 0;">Manage pricing tiers, form options, and pre-order benefits.</p>
    </div>
    
    <form onsubmit="event.preventDefault(); saveSettings(this);">
        
        <h4 style="background: #f0f9ff; color: #0284c7; padding: 10px; border-radius: 6px;">
            <ion-icon name="pricetags" style="vertical-align: middle;"></ion-icon> Tiered Price List & MOQ
        </h4>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <div style="border: 1px solid #bae6fd; padding: 15px; border-radius: 8px; background: #f0f9ff;">
                <strong style="display:block; margin-bottom: 10px; color: #0284c7;">Tier 2: Class Set</strong>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Minimum Order Qty (MOQ)</label>
                    <input type="number" name="moq_tier_2" value="<?php echo $s['moq_tier_2'] ?? 20; ?>" class="form-input">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: bold;">Discount (%)</label>
                    <input type="number" name="discount_tier_2" value="<?php echo $s['discount_tier_2'] ?? 10; ?>" class="form-input">
                </div>
            </div>

            <div style="border: 1px solid #e9d5ff; padding: 15px; border-radius: 8px; background: #faf5ff;">
                <strong style="display:block; margin-bottom: 10px; color: #9333ea;">Tier 3: Partner</strong>
                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: bold;">Minimum Order Qty (MOQ)</label>
                    <input type="number" name="moq_tier_3" value="<?php echo $s['moq_tier_3'] ?? 200; ?>" class="form-input">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: bold;">Discount (%)</label>
                    <input type="number" name="discount_tier_3" value="<?php echo $s['discount_tier_3'] ?? 20; ?>" class="form-input">
                </div>
            </div>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="font-weight: bold;">Early Bird Deadline</label>
            <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Orders before this date get +5% extra discount.</div>
            <input type="date" name="early_bird_deadline" value="<?php echo $s['early_bird_deadline'] ?? '2026-04-30'; ?>" class="form-input" style="max-width: 200px;">
        </div>

        <h4 style="background: #fff7ed; color: #c2410c; padding: 10px; border-radius: 6px;">
            <ion-icon name="list" style="vertical-align: middle;"></ion-icon> Form Content Manager
        </h4>

        <div style="margin-bottom: 20px;">
            <label style="font-weight: bold;">Program Options (Checkboxes)</label>
            <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Enter one program per line. These appear on the Interest Form.</div>
            <textarea id="programs_text" rows="5" class="form-input" style="font-family: monospace;"><?php echo htmlspecialchars($prog_text); ?></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="font-weight: bold;">Benefit List (Bullet Points)</label>
            <div style="font-size: 12px; color: #666; margin-bottom: 5px;">
                Enter one benefit per line. Use <strong>{moq3}</strong> as a placeholder for the Partner MOQ number.
            </div>
            <textarea id="benefits_text" rows="5" class="form-input" style="font-family: monospace;"><?php echo htmlspecialchars($ben_text); ?></textarea>
        </div>

        <button type="submit" class="btn" style="width: 100%; padding: 12px; font-size: 16px;">Save Configuration</button>
    </form>
</div>

<style>
.form-input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
.form-input:focus { border-color: var(--primary); outline: none; }
</style>

<script>
async function saveSettings(form) {
    // 1. Capture Basic Fields
    const data = {
        moq_tier_2: form.moq_tier_2.value,
        discount_tier_2: form.discount_tier_2.value,
        moq_tier_3: form.moq_tier_3.value,
        discount_tier_3: form.discount_tier_3.value,
        early_bird_deadline: form.early_bird_deadline.value
    };

    // 2. Process Textareas into JSON Arrays
    // We split by newline, trim whitespace, and remove empty lines
    const progRaw = document.getElementById('programs_text').value;
    const benRaw = document.getElementById('benefits_text').value;

    data.programs_list = JSON.stringify(progRaw.split('\n').map(s => s.trim()).filter(s => s));
    data.benefits_list = JSON.stringify(benRaw.split('\n').map(s => s.trim()).filter(s => s));

    // 3. Send to API
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    
    try {
        // We append the CSRF token to the data object
        data.csrf_token = csrf;
        
        const res = await fetch('api.php?action=update_settings', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });

        const json = await res.json();
        if (json.status === 'success') {
            alert('Settings Saved Successfully!');
            location.reload();
        } else {
            alert('Error: ' + json.message);
        }
    } catch (e) {
        alert('System Error: ' + e.message);
    }
}
</script>