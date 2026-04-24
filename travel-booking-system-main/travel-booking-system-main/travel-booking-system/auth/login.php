<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/controllers/AuthController.php';
if (isLoggedIn()) redirect(APP_URL . '/index.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl = new AuthController($pdo);
    $result = $ctrl->login(sanitize($_POST['email'] ?? ''), $_POST['password'] ?? '');
    if (isset($result['success'])) {
        redirect($_GET['redirect'] ?? APP_URL . ($result['role'] === 'admin' ? '/pages/admin/dashboard.php' : '/index.php'));
    } else { $error = $result['error']; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — TravelLux</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;font-family:'Inter',sans-serif}
body{
    min-height:100vh;display:flex;align-items:stretch;
    background:#080818;overflow:hidden;
}

/* ── Animated background ── */
.auth-bg{
    position:fixed;inset:0;z-index:0;
    background:
        radial-gradient(ellipse 80% 70% at 10% 20%, rgba(124,58,237,0.45) 0%, transparent 60%),
        radial-gradient(ellipse 60% 60% at 90% 80%, rgba(236,72,153,0.35) 0%, transparent 60%),
        radial-gradient(ellipse 70% 50% at 50% 50%, rgba(6,182,212,0.15) 0%, transparent 70%),
        #080818;
    animation: bgPulse 10s ease-in-out infinite alternate;
}
@keyframes bgPulse{
    0%{background-position:0% 0%,100% 100%,50% 50%}
    100%{background-position:30% 20%,70% 80%,60% 40%}
}

/* ── Floating orbs ── */
.orb{position:fixed;border-radius:50%;filter:blur(80px);pointer-events:none;z-index:0;animation:orbFloat 8s ease-in-out infinite alternate;}
.orb1{width:500px;height:500px;top:-150px;left:-150px;background:rgba(124,58,237,0.35);animation-delay:0s;}
.orb2{width:400px;height:400px;bottom:-100px;right:-100px;background:rgba(236,72,153,0.3);animation-delay:3s;}
.orb3{width:300px;height:300px;top:40%;left:40%;background:rgba(6,182,212,0.2);animation-delay:6s;}
@keyframes orbFloat{0%{transform:translate(0,0) scale(1)}100%{transform:translate(40px,-30px) scale(1.1)}}

/* ── Grid overlay ── */
.bg-grid{position:fixed;inset:0;z-index:0;
    background-image:linear-gradient(rgba(255,255,255,0.03) 1px,transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,0.03) 1px,transparent 1px);
    background-size:60px 60px;pointer-events:none;}

/* ── Layout ── */
.auth-layout{
    position:relative;z-index:1;display:grid;
    grid-template-columns:1fr 1fr;width:100%;min-height:100vh;
}

/* ── Left visual panel ── */
.auth-visual{
    position:relative;overflow:hidden;display:flex;flex-direction:column;
    justify-content:flex-end;padding:60px;
}
.auth-visual-img{
    position:absolute;inset:0;
    background:url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=900&q=85') center/cover;
}
.auth-visual-overlay{
    position:absolute;inset:0;
    background:linear-gradient(135deg,rgba(8,8,24,0.6) 0%,rgba(124,58,237,0.3) 50%,rgba(236,72,153,0.2) 100%);
}
.auth-visual-content{position:relative;z-index:1;}
.auth-visual-logo{
    font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:900;
    background:linear-gradient(135deg,#A78BFA,#F9A8D4);
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    display:block;margin-bottom:auto;position:absolute;top:40px;left:60px;
}
.auth-visual-title{
    font-family:'Poppins',sans-serif;font-size:2.8rem;font-weight:900;
    color:#fff;line-height:1.15;margin-bottom:16px;letter-spacing:-1px;
}
.auth-visual-sub{color:rgba(255,255,255,0.7);font-size:1rem;line-height:1.7;margin-bottom:28px;}
.auth-visual-badges{display:flex;gap:10px;flex-wrap:wrap;}
.auth-badge{
    background:rgba(255,255,255,0.12);backdrop-filter:blur(10px);
    border:1px solid rgba(255,255,255,0.2);color:#fff;
    padding:7px 16px;border-radius:50px;font-size:0.78rem;font-weight:600;
}
.auth-visual-reviews{
    display:flex;align-items:center;gap:12px;margin-top:28px;
    background:rgba(255,255,255,0.08);backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.12);border-radius:16px;padding:16px 20px;
}
.auth-review-avatars{display:flex;}
.auth-review-avatars img{width:36px;height:36px;border-radius:50%;border:2px solid rgba(255,255,255,0.3);margin-left:-10px;}
.auth-review-avatars img:first-child{margin-left:0;}
.auth-review-text{font-size:0.82rem;color:rgba(255,255,255,0.8);}
.auth-review-stars{color:#F59E0B;font-size:0.75rem;margin-top:2px;}

/* ── Right form panel ── */
.auth-form-panel{
    display:flex;flex-direction:column;justify-content:center;
    padding:60px 70px;background:rgba(8,8,24,0.7);
    backdrop-filter:blur(24px);border-left:1px solid rgba(255,255,255,0.06);
    overflow-y:auto;
}
.auth-form-logo{
    font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:900;
    background:linear-gradient(135deg,#A78BFA,#F9A8D4);
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    display:block;margin-bottom:44px;text-decoration:none;
}
.auth-form-title{
    font-family:'Poppins',sans-serif;font-size:2rem;font-weight:800;
    color:#fff;margin-bottom:8px;letter-spacing:-0.5px;
}
.auth-form-sub{color:rgba(255,255,255,0.5);font-size:0.92rem;margin-bottom:36px;line-height:1.6;}

/* ── Social buttons ── */
.social-btns{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:28px;}
.social-btn{
    display:flex;align-items:center;justify-content:center;gap:10px;
    background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);
    color:rgba(255,255,255,0.8);padding:12px 20px;border-radius:12px;
    font-size:0.88rem;font-weight:600;cursor:pointer;transition:all 0.3s;
    font-family:'Inter',sans-serif;
}
.social-btn:hover{background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.2);color:#fff;transform:translateY(-2px);}
.social-btn svg{width:18px;height:18px;flex-shrink:0;}

/* ── Divider ── */
.auth-divider{
    display:flex;align-items:center;gap:16px;margin-bottom:28px;
    color:rgba(255,255,255,0.25);font-size:0.8rem;
}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:rgba(255,255,255,0.1);}

/* ── Form fields ── */
.field-group{margin-bottom:20px;}
.field-label{display:block;font-size:0.82rem;font-weight:700;color:rgba(255,255,255,0.7);margin-bottom:8px;letter-spacing:0.3px;}
.field-wrap{position:relative;}
.field-icon{
    position:absolute;left:16px;top:50%;transform:translateY(-50%);
    color:rgba(255,255,255,0.3);font-size:1rem;pointer-events:none;
}
.field-input{
    width:100%;padding:14px 16px 14px 46px;
    background:rgba(255,255,255,0.06);
    border:1.5px solid rgba(255,255,255,0.1);
    border-radius:12px;color:#fff;font-size:0.95rem;
    font-family:'Inter',sans-serif;outline:none;transition:all 0.3s;
}
.field-input::placeholder{color:rgba(255,255,255,0.25);}
.field-input:focus{
    border-color:rgba(124,58,237,0.7);
    background:rgba(124,58,237,0.08);
    box-shadow:0 0 0 4px rgba(124,58,237,0.15);
}
.field-toggle{
    position:absolute;right:14px;top:50%;transform:translateY(-50%);
    background:none;border:none;color:rgba(255,255,255,0.3);cursor:pointer;
    font-size:1rem;padding:4px;transition:color 0.2s;
}
.field-toggle:hover{color:rgba(255,255,255,0.7);}

/* ── Forgot / remember row ── */
.field-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
.remember{display:flex;align-items:center;gap:8px;cursor:pointer;}
.remember input{width:16px;height:16px;accent-color:#7C3AED;cursor:pointer;}
.remember span{font-size:0.82rem;color:rgba(255,255,255,0.5);}
.forgot-link{font-size:0.82rem;color:#A78BFA;font-weight:600;text-decoration:none;transition:color 0.2s;}
.forgot-link:hover{color:#F9A8D4;}

/* ── Submit button ── */
.submit-btn{
    width:100%;padding:15px;border-radius:12px;border:none;cursor:pointer;
    background:linear-gradient(135deg,#7C3AED,#EC4899);color:#fff;
    font-size:1rem;font-weight:700;font-family:'Inter',sans-serif;
    transition:all 0.3s;position:relative;overflow:hidden;
    box-shadow:0 8px 32px rgba(124,58,237,0.4);
    display:flex;align-items:center;justify-content:center;gap:10px;
}
.submit-btn:hover{transform:translateY(-2px);box-shadow:0 14px 40px rgba(124,58,237,0.55);}
.submit-btn:active{transform:translateY(0);}
.submit-btn::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(135deg,rgba(255,255,255,0.15),transparent);
    opacity:0;transition:opacity 0.3s;
}
.submit-btn:hover::before{opacity:1;}

/* ── Switch link ── */
.auth-switch{text-align:center;margin-top:28px;color:rgba(255,255,255,0.4);font-size:0.88rem;}
.auth-switch a{color:#A78BFA;font-weight:700;text-decoration:none;transition:color 0.2s;}
.auth-switch a:hover{color:#F9A8D4;}

/* ── Alert ── */
.auth-alert{
    background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);
    color:#FCA5A5;padding:12px 16px;border-radius:10px;
    font-size:0.88rem;margin-bottom:20px;display:flex;align-items:center;gap:10px;
}

/* ── Floating label animation ── */
.field-input:focus + .field-label-float,
.field-input:not(:placeholder-shown) + .field-label-float{
    transform:translateY(-24px) scale(0.85);color:#A78BFA;
}

/* ── Responsive ── */
@media(max-width:900px){
    .auth-layout{grid-template-columns:1fr;}
    .auth-visual{display:none;}
    .auth-form-panel{padding:40px 32px;}
}
@media(max-width:480px){
    .auth-form-panel{padding:32px 20px;}
    .social-btns{grid-template-columns:1fr;}
}
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
            <h2 class="auth-visual-title">Welcome Back,<br>Explorer ✈</h2>
            <p class="auth-visual-sub">Your next adventure is just a login away. Discover extraordinary destinations and create unforgettable memories.</p>
            <div class="auth-visual-badges">
                <span class="auth-badge">✈ 500+ Destinations</span>
                <span class="auth-badge">🏆 Best Prices</span>
                <span class="auth-badge">⭐ 5-Star Hotels</span>
                <span class="auth-badge">🔒 Secure Booking</span>
            </div>
            <div class="auth-visual-reviews">
                <div class="auth-review-avatars">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80&q=80" alt="">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&q=80" alt="">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80&q=80" alt="">
                </div>
                <div>
                    <div class="auth-review-text">Trusted by <strong style="color:#fff;">50,000+</strong> happy travelers</div>
                    <div class="auth-review-stars">★★★★★ 4.9/5 average rating</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM -->
    <div class="auth-form-panel">
        <a href="<?= APP_URL ?>/index.php" class="auth-form-logo">✈ TravelLux</a>
        <h1 class="auth-form-title">Sign In</h1>
        <p class="auth-form-sub">Welcome back! Enter your credentials to continue your journey.</p>

        <?php if ($error): ?>
        <div class="auth-alert">
            <span>⚠️</span> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <!-- Social login -->
        <div class="social-btns">
            <button class="social-btn" onclick="alert('OAuth coming soon!')">
                <svg viewBox="0 0 24 24" fill="none"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Continue with Google
            </button>
            <button class="social-btn" onclick="alert('OAuth coming soon!')">
                <svg viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Continue with Facebook
            </button>
        </div>

        <div class="auth-divider">or sign in with email</div>

        <form method="POST" id="loginForm">
            <div class="field-group">
                <label class="field-label">Email Address</label>
                <div class="field-wrap">
                    <span class="field-icon">📧</span>
                    <input type="email" name="email" class="field-input"
                           placeholder="you@example.com" required autocomplete="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
            </div>

            <div class="field-group">
                <label class="field-label">Password</label>
                <div class="field-wrap">
                    <span class="field-icon">🔒</span>
                    <input type="password" name="password" id="pwdField" class="field-input"
                           placeholder="Enter your password" required autocomplete="current-password">
                    <button type="button" class="field-toggle" onclick="togglePwd('pwdField',this)">👁</button>
                </div>
            </div>

            <div class="field-row">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="<?= APP_URL ?>/auth/forgot-password.php" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="submit-btn">
                <span>Sign In</span>
                <span>→</span>
            </button>
        </form>

        <p class="auth-switch">
            Don't have an account? <a href="<?= APP_URL ?>/auth/register.php">Create one free</a>
        </p>
    </div>
</div>

<script>
function togglePwd(id, btn) {
    const f = document.getElementById(id);
    if (f.type === 'password') { f.type = 'text'; btn.textContent = '🙈'; }
    else { f.type = 'password'; btn.textContent = '👁'; }
}
// Input focus glow
document.querySelectorAll('.field-input').forEach(inp => {
    inp.addEventListener('focus', () => inp.closest('.field-wrap').style.transform = 'scale(1.01)');
    inp.addEventListener('blur',  () => inp.closest('.field-wrap').style.transform = '');
});
</script>
</body>
</html>
