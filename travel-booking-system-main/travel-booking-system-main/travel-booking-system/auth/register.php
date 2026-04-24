<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/controllers/AuthController.php';
if (isLoggedIn()) redirect(APP_URL . '/index.php');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl = new AuthController($pdo);
    $result = $ctrl->register(
        sanitize($_POST['name'] ?? ''), sanitize($_POST['email'] ?? ''),
        $_POST['password'] ?? '', $_POST['confirm_password'] ?? '',
        sanitize($_POST['phone'] ?? '')
    );
    if (isset($result['success'])) redirect(APP_URL . '/index.php');
    else $errors = $result['errors'] ?? [$result['error']];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account — TravelLux</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;font-family:'Inter',sans-serif}
body{min-height:100vh;display:flex;align-items:stretch;background:#080818;overflow-x:hidden;}
.auth-bg{position:fixed;inset:0;z-index:0;
    background:radial-gradient(ellipse 80% 70% at 90% 10%,rgba(236,72,153,0.4) 0%,transparent 60%),
    radial-gradient(ellipse 60% 60% at 10% 90%,rgba(124,58,237,0.35) 0%,transparent 60%),
    radial-gradient(ellipse 50% 50% at 50% 50%,rgba(6,182,212,0.12) 0%,transparent 70%),#080818;
    animation:bgPulse 12s ease-in-out infinite alternate;}
@keyframes bgPulse{0%{opacity:1}100%{opacity:0.85}}
.orb{position:fixed;border-radius:50%;filter:blur(80px);pointer-events:none;z-index:0;animation:orbF 9s ease-in-out infinite alternate;}
.orb1{width:500px;height:500px;top:-100px;right:-100px;background:rgba(236,72,153,0.3);animation-delay:0s;}
.orb2{width:400px;height:400px;bottom:-100px;left:-100px;background:rgba(124,58,237,0.3);animation-delay:4s;}
.orb3{width:250px;height:250px;top:50%;right:30%;background:rgba(6,182,212,0.18);animation-delay:7s;}
@keyframes orbF{0%{transform:translate(0,0) scale(1)}100%{transform:translate(-30px,40px) scale(1.12)}}
.bg-grid{position:fixed;inset:0;z-index:0;
    background-image:linear-gradient(rgba(255,255,255,0.025) 1px,transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,0.025) 1px,transparent 1px);
    background-size:60px 60px;pointer-events:none;}
.auth-layout{position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;width:100%;min-height:100vh;}
.auth-visual{position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:flex-end;padding:60px;}
.auth-visual-img{position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=900&q=85') center/cover;}
.auth-visual-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(8,8,24,0.55) 0%,rgba(236,72,153,0.25) 50%,rgba(124,58,237,0.2) 100%);}
.auth-visual-content{position:relative;z-index:1;}
.auth-visual-logo{font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:900;background:linear-gradient(135deg,#A78BFA,#F9A8D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;display:block;position:absolute;top:40px;left:60px;text-decoration:none;}
.auth-visual-title{font-family:'Poppins',sans-serif;font-size:2.8rem;font-weight:900;color:#fff;line-height:1.15;margin-bottom:16px;letter-spacing:-1px;}
.auth-visual-sub{color:rgba(255,255,255,0.7);font-size:1rem;line-height:1.7;margin-bottom:28px;}
.auth-visual-badges{display:flex;gap:10px;flex-wrap:wrap;}
.auth-badge{background:rgba(255,255,255,0.12);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.2);color:#fff;padding:7px 16px;border-radius:50px;font-size:0.78rem;font-weight:600;}
/* Steps indicator */
.auth-steps{display:flex;gap:8px;margin-top:28px;}
.auth-step{display:flex;align-items:center;gap:8px;color:rgba(255,255,255,0.6);font-size:0.8rem;}
.auth-step-num{width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;color:#fff;}
.auth-step.done .auth-step-num{background:linear-gradient(135deg,#7C3AED,#EC4899);border-color:transparent;}
.auth-step-arrow{color:rgba(255,255,255,0.3);}
/* Form panel */
.auth-form-panel{display:flex;flex-direction:column;justify-content:center;padding:50px 60px;background:rgba(8,8,24,0.7);backdrop-filter:blur(24px);border-left:1px solid rgba(255,255,255,0.06);overflow-y:auto;}
.auth-form-logo{font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:900;background:linear-gradient(135deg,#A78BFA,#F9A8D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;display:block;margin-bottom:36px;text-decoration:none;}
.auth-form-title{font-family:'Poppins',sans-serif;font-size:1.9rem;font-weight:800;color:#fff;margin-bottom:8px;letter-spacing:-0.5px;}
.auth-form-sub{color:rgba(255,255,255,0.5);font-size:0.9rem;margin-bottom:32px;line-height:1.6;}
.social-btns{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px;}
.social-btn{display:flex;align-items:center;justify-content:center;gap:10px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.8);padding:11px 16px;border-radius:12px;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.3s;font-family:'Inter',sans-serif;}
.social-btn:hover{background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.2);color:#fff;transform:translateY(-2px);}
.social-btn svg{width:17px;height:17px;flex-shrink:0;}
.auth-divider{display:flex;align-items:center;gap:16px;margin-bottom:24px;color:rgba(255,255,255,0.25);font-size:0.78rem;}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:rgba(255,255,255,0.1);}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.field-group{margin-bottom:16px;}
.field-label{display:block;font-size:0.8rem;font-weight:700;color:rgba(255,255,255,0.65);margin-bottom:7px;letter-spacing:0.3px;}
.field-wrap{position:relative;transition:transform 0.2s;}
.field-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.3);font-size:0.95rem;pointer-events:none;}
.field-input{width:100%;padding:13px 14px 13px 42px;background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);border-radius:12px;color:#fff;font-size:0.92rem;font-family:'Inter',sans-serif;outline:none;transition:all 0.3s;}
.field-input::placeholder{color:rgba(255,255,255,0.22);}
.field-input:focus{border-color:rgba(236,72,153,0.7);background:rgba(236,72,153,0.07);box-shadow:0 0 0 4px rgba(236,72,153,0.12);}
.field-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;font-size:0.95rem;padding:4px;transition:color 0.2s;}
.field-toggle:hover{color:rgba(255,255,255,0.7);}
/* Password strength */
.pwd-strength{margin-top:8px;}
.pwd-bars{display:flex;gap:4px;margin-bottom:4px;}
.pwd-bar{flex:1;height:3px;border-radius:2px;background:rgba(255,255,255,0.1);transition:background 0.3s;}
.pwd-bar.weak{background:#EF4444;}
.pwd-bar.fair{background:#F59E0B;}
.pwd-bar.good{background:#10B981;}
.pwd-bar.strong{background:#06B6D4;}
.pwd-text{font-size:0.72rem;color:rgba(255,255,255,0.4);}
/* Terms */
.terms-row{display:flex;align-items:flex-start;gap:10px;margin-bottom:20px;}
.terms-row input{width:16px;height:16px;accent-color:#EC4899;cursor:pointer;margin-top:2px;flex-shrink:0;}
.terms-row span{font-size:0.8rem;color:rgba(255,255,255,0.45);line-height:1.5;}
.terms-row a{color:#A78BFA;text-decoration:none;}
.terms-row a:hover{color:#F9A8D4;}
.submit-btn{width:100%;padding:15px;border-radius:12px;border:none;cursor:pointer;background:linear-gradient(135deg,#EC4899,#7C3AED);color:#fff;font-size:1rem;font-weight:700;font-family:'Inter',sans-serif;transition:all 0.3s;box-shadow:0 8px 32px rgba(236,72,153,0.4);display:flex;align-items:center;justify-content:center;gap:10px;}
.submit-btn:hover{transform:translateY(-2px);box-shadow:0 14px 40px rgba(236,72,153,0.55);}
.auth-switch{text-align:center;margin-top:24px;color:rgba(255,255,255,0.4);font-size:0.88rem;}
.auth-switch a{color:#A78BFA;font-weight:700;text-decoration:none;}
.auth-switch a:hover{color:#F9A8D4;}
.auth-alert{background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#FCA5A5;padding:12px 16px;border-radius:10px;font-size:0.85rem;margin-bottom:18px;}
.auth-alert p{margin-bottom:4px;}
.auth-alert p:last-child{margin-bottom:0;}
@media(max-width:900px){.auth-layout{grid-template-columns:1fr}.auth-visual{display:none}.auth-form-panel{padding:40px 28px}}
@media(max-width:480px){.form-row{grid-template-columns:1fr}.social-btns{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="auth-bg"></div>
<div class="orb orb1"></div>
<div class="orb orb2"></div>
<div class="orb orb3"></div>
<div class="bg-grid"></div>

<div class="auth-layout">
    <!-- LEFT VISUAL -->
    <div class="auth-visual">
        <div class="auth-visual-img"></div>
        <div class="auth-visual-overlay"></div>
        <a href="<?= APP_URL ?>/index.php" class="auth-visual-logo">✈ TravelLux</a>
        <div class="auth-visual-content">
            <h2 class="auth-visual-title">Start Your<br>Journey Today 🌍</h2>
            <p class="auth-visual-sub">Join 50,000+ travelers discovering the world with TravelLux. Free to join, exclusive deals await.</p>
            <div class="auth-visual-badges">
                <span class="auth-badge">🆓 Free to Join</span>
                <span class="auth-badge">💎 Exclusive Deals</span>
                <span class="auth-badge">🔒 100% Secure</span>
                <span class="auth-badge">✈ Instant Booking</span>
            </div>
            <div class="auth-steps">
                <div class="auth-step done">
                    <div class="auth-step-num">1</div>
                    <span>Create Account</span>
                </div>
                <span class="auth-step-arrow">→</span>
                <div class="auth-step">
                    <div class="auth-step-num">2</div>
                    <span>Find Hotels</span>
                </div>
                <span class="auth-step-arrow">→</span>
                <div class="auth-step">
                    <div class="auth-step-num">3</div>
                    <span>Book & Travel</span>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM -->
    <div class="auth-form-panel">
        <a href="<?= APP_URL ?>/index.php" class="auth-form-logo">✈ TravelLux</a>
        <h1 class="auth-form-title">Create Account</h1>
        <p class="auth-form-sub">Join TravelLux and start exploring the world today. It's free!</p>

        <?php if (!empty($errors)): ?>
        <div class="auth-alert">
            <?php foreach($errors as $e): ?><p>⚠️ <?= htmlspecialchars($e) ?></p><?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="social-btns">
            <button class="social-btn" onclick="alert('OAuth coming soon!')">
                <svg viewBox="0 0 24 24" fill="none"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Sign up with Google
            </button>
            <button class="social-btn" onclick="alert('OAuth coming soon!')">
                <svg viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Sign up with Facebook
            </button>
        </div>

        <div class="auth-divider">or register with email</div>

        <form method="POST" id="regForm">
            <div class="form-row">
                <div class="field-group">
                    <label class="field-label">First Name</label>
                    <div class="field-wrap">
                        <span class="field-icon">👤</span>
                        <input type="text" name="name" class="field-input" placeholder="John" required
                               value="<?= htmlspecialchars(explode(' ', $_POST['name'] ?? '')[0]) ?>">
                    </div>
                </div>
                <div class="field-group">
                    <label class="field-label">Last Name</label>
                    <div class="field-wrap">
                        <span class="field-icon">👤</span>
                        <input type="text" name="last_name" class="field-input" placeholder="Doe"
                               value="<?= htmlspecialchars(explode(' ', $_POST['name'] ?? '', 2)[1] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="field-group">
                <label class="field-label">Email Address</label>
                <div class="field-wrap">
                    <span class="field-icon">📧</span>
                    <input type="email" name="email" class="field-input" placeholder="you@example.com" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
            </div>

            <div class="field-group">
                <label class="field-label">Phone Number <span style="color:rgba(255,255,255,0.3)">(optional)</span></label>
                <div class="field-wrap">
                    <span class="field-icon">📱</span>
                    <input type="tel" name="phone" class="field-input" placeholder="+1 234 567 8900"
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="field-group">
                    <label class="field-label">Password</label>
                    <div class="field-wrap">
                        <span class="field-icon">🔒</span>
                        <input type="password" name="password" id="pwdField" class="field-input"
                               placeholder="Min. 8 characters" required oninput="checkStrength(this.value)">
                        <button type="button" class="field-toggle" onclick="togglePwd('pwdField',this)">👁</button>
                    </div>
                    <div class="pwd-strength">
                        <div class="pwd-bars">
                            <div class="pwd-bar" id="bar1"></div>
                            <div class="pwd-bar" id="bar2"></div>
                            <div class="pwd-bar" id="bar3"></div>
                            <div class="pwd-bar" id="bar4"></div>
                        </div>
                        <div class="pwd-text" id="pwdText">Enter a password</div>
                    </div>
                </div>
                <div class="field-group">
                    <label class="field-label">Confirm Password</label>
                    <div class="field-wrap">
                        <span class="field-icon">🔒</span>
                        <input type="password" name="confirm_password" id="confirmField" class="field-input"
                               placeholder="Repeat password" required>
                        <button type="button" class="field-toggle" onclick="togglePwd('confirmField',this)">👁</button>
                    </div>
                </div>
            </div>

            <div class="terms-row">
                <input type="checkbox" id="terms" required>
                <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>. I consent to receiving travel deals and updates.</span>
            </div>

            <button type="submit" class="submit-btn">
                <span>Create My Account</span>
                <span>🚀</span>
            </button>
        </form>

        <p class="auth-switch">
            Already have an account? <a href="<?= APP_URL ?>/auth/login.php">Sign in</a>
        </p>
    </div>
</div>

<script>
function togglePwd(id, btn) {
    const f = document.getElementById(id);
    f.type = f.type === 'password' ? 'text' : 'password';
    btn.textContent = f.type === 'password' ? '👁' : '🙈';
}

function checkStrength(val) {
    const bars = [document.getElementById('bar1'),document.getElementById('bar2'),document.getElementById('bar3'),document.getElementById('bar4')];
    const txt  = document.getElementById('pwdText');
    bars.forEach(b => { b.className = 'pwd-bar'; });
    if (!val) { txt.textContent = 'Enter a password'; return; }
    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = ['weak','fair','good','strong'];
    const labels = ['Weak','Fair','Good','Strong 💪'];
    for (let i = 0; i < score; i++) bars[i].classList.add(levels[score-1]);
    txt.textContent = labels[score-1] || 'Too short';
}

// Confirm password match indicator
document.getElementById('confirmField').addEventListener('input', function() {
    const pwd = document.getElementById('pwdField').value;
    this.style.borderColor = this.value === pwd && this.value
        ? 'rgba(16,185,129,0.7)' : 'rgba(255,255,255,0.1)';
});

// Focus scale
document.querySelectorAll('.field-input').forEach(inp => {
    inp.addEventListener('focus', () => inp.closest('.field-wrap').style.transform = 'scale(1.01)');
    inp.addEventListener('blur',  () => inp.closest('.field-wrap').style.transform = '');
});

// Combine first + last name into name field on submit
document.getElementById('regForm').addEventListener('submit', function() {
    const first = this.querySelector('[name="name"]').value.trim();
    const last  = this.querySelector('[name="last_name"]').value.trim();
    this.querySelector('[name="name"]').value = (first + ' ' + last).trim();
});
</script>
</body>
</html>
