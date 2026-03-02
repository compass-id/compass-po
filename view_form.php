<?php
// view_form.php - UPDATED WITH SEPARATE CONTACTS
$pdo = getDB();

// Fetch Settings
$s = $pdo->query("SELECT setting_key, setting_value FROM system_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$moq2 = $s['moq_tier_2'] ?? 20;
$moq3 = $s['moq_tier_3'] ?? 200;
$disc2 = $s['discount_tier_2'] ?? 10;
$disc3 = $s['discount_tier_3'] ?? 20;
$deadline = date('d F Y', strtotime($s['early_bird_deadline'] ?? '2026-04-30'));
$programs = json_decode($s['programs_list'] ?? '[]', true);
$benefits = json_decode($s['benefits_list'] ?? '[]', true);

// Auto-fill if logged in
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    
    <div style="text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
        <h2 style="color: var(--primary);">FORMULIR KETERTARIKAN PROGRAM</h2>
        <h3 style="margin-top: 5px;">COMPASS PUBLISHING</h3>
        <p style="font-size: 14px; color: #666; max-width: 600px; margin: 10px auto;">
            Terima kasih telah hadir di Compass Educators Gathering. Kami berkomitmen untuk membantu sekolah Anda meningkatkan kualitas pembelajaran bahasa Inggris dengan program berstandar global.
        </p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div style="background: #e8f5e9; color: green; padding: 15px; border-radius: 8px; text-align: center; margin-bottom: 20px;">
            <strong>Terima Kasih!</strong> Formulir Anda telah berhasil dikirim. Tim kami akan segera menghubungi Anda.
        </div>
    <?php else: ?>

    <form action="api.php?action=submit_form" method="POST">
        
        <h4 style="background: #f5f5f7; padding: 10px; border-radius: 6px;">I. Data Sekolah & Peserta</h4>
        <div style="display: grid; gap: 15px;">
            <div>
                <label>Nama Sekolah</label>
                <input type="text" name="school_name" class="docx-input" value="<?php echo htmlspecialchars($user['institution'] ?? ''); ?>" required>
            </div>
            <div>
                <label>Nama Peserta</label>
                <input type="text" name="participant_name" class="docx-input" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label>Email Address</label>
                    <input type="email" name="email" class="docx-input" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div>
                    <label>WhatsApp / Telepon</label>
                    <input type="text" name="phone" class="docx-input" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label>Kota / Domisili</label>
                    <input type="text" name="city" class="docx-input" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                </div>
                <div>
                    <label>Jabatan</label>
                    <input type="text" name="position" class="docx-input" value="<?php echo htmlspecialchars($user['position'] ?? ''); ?>" placeholder="Guru / Kepsek / Yayasan">
                </div>
            </div>
            
            <div>
                <label>Jumlah Siswa di Sekolah</label>
                <input type="number" name="student_count" class="docx-input" required>
            </div>
        </div>

        <h4 style="background: #f5f5f7; padding: 10px; border-radius: 6px; margin-top: 25px;">II. Kunjungan & Minat</h4>
        
        <div class="docx-q">
            <p>1. Apakah Anda bersedia tim dari Compass Publishing mengunjungi sekolah Anda untuk mempresentasikan produk & layanan?</p>
            <label><input type="radio" name="visit_consent" value="Ya" required> Ya, Bersedia</label>
            <label><input type="radio" name="visit_consent" value="Tidak"> Tidak</label>
        </div>

        <div class="docx-q">
            <p>2. Apakah Anda tertarik untuk mengetahui lebih dalam program Compass Publishing?</p>
            <label><input type="radio" name="interest_level" value="Tertarik" required> Ya, saya tertarik.</label>
            <label><input type="radio" name="interest_level" value="Ingin Tahu"> Belum, tapi saya ingin tahu lebih banyak.</label>
        </div>

        <div class="docx-q">
            <p>3. Program dari Compass Publishing yang membuat Anda tertarik? *(Boleh pilih lebih dari satu)</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <?php foreach($programs as $prog): ?>
                    <label class="check-box">
                        <input type="checkbox" name="programs[]" value="<?php echo htmlspecialchars($prog); ?>">
                        <?php echo htmlspecialchars($prog); ?>
                    </label>
                <?php endforeach; ?>
                <label class="check-box">
                    <input type="checkbox" name="programs[]" value="Lainnya"> Lain-lain
                </label>
            </div>
        </div>

        <h4 style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 6px; margin-top: 25px;">
            <ion-icon name="star"></ion-icon> Benefit Eksklusif (Pre-Order sebelum <?php echo $deadline; ?>)
        </h4>
        <div style="padding: 15px; border: 1px solid #ffeeba; border-radius: 8px; background: #fffdf5;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach($benefits as $ben): 
                    $ben_text = str_replace('{moq3}', $moq3, $ben);
                ?>
                <li style="margin-bottom: 8px;"><?php echo htmlspecialchars($ben_text); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <p style="font-size: 12px; color: #666; text-align: justify;">
                <strong>Pernyataan:</strong> Formulir ini hanya untuk menjelaskan ketertarikan Anda terhadap produk Compass Publishing dan mengamankan benefit untuk sekolah Anda.
            </p>
            <label style="display: flex; align-items: center; gap: 10px; margin-top: 15px; font-weight: bold;">
                <input type="checkbox" required>
                Saya menyatakan informasi di atas benar dan bersedia dihubungi oleh Compass Publishing.
            </label>
        </div>

        <button type="submit" class="btn" style="width: 100%; margin-top: 20px; font-size: 16px;">Kirim Formulir</button>
    </form>
    <?php endif; ?>
</div>

<style>
.docx-input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-top: 5px; font-size: 14px; }
.docx-q { margin-bottom: 20px; }
.docx-q p { font-weight: 600; margin-bottom: 10px; }
.docx-q label { margin-right: 15px; cursor: pointer; }
.check-box { display: flex; align-items: center; gap: 8px; font-weight: normal !important; }
</style>