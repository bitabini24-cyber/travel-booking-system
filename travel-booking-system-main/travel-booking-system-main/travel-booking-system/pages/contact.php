<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
$pageTitle = 'Contact Us — TravelLux';
$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In production: send email via EmailService
    $sent = true;
}
require_once __DIR__ . '/../includes/header.php';
?>
<style>
body{background:#080818;}
.contact-hero{padding:140px 0 80px;text-align:center;position:relative;}
.contact-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(6,182,212,0.25),transparent 70%);}
.contact-hero-content{position:relative;z-index:1;}
.contact-tag{display:inline-flex;align-items:center;gap:8px;background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.3);color:rgba(255,255,255,0.8);padding:8px 20px;border-radius:50px;font-size:0.78rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:20px;}
.contact-hero h1{font-family:'Poppins',sans-serif;font-size:clamp(2.5rem,6vw,5rem);font-weight:900;color:#fff;letter-spacing:-2px;margin-bottom:16px;}
.contact-hero h1 span{background:linear-gradient(135deg,#67E8F9,#A78BFA);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.contact-hero p{color:rgba(255,255,255,0.6);font-size:1.1rem;max-width:520px;margin:0 auto;}
/* Layout */
.contact-layout{display:grid;grid-template-columns:1fr 1.4fr;gap:48px;padding-bottom:100px;}
/* Info cards */
.contact-info{display:flex;flex-direction:column;gap:20px;}
.contact-info-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:24px;display:flex;align-items:flex-start;gap:16px;transition:all 0.3s;}
.contact-info-card:hover{background:rgba(124,58,237,0.08);border-color:rgba(124,58,237,0.25);transform:translateX(6px);}
.contact-info-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;}
.contact-info-title{color:#fff;font-weight:700;font-size:0.95rem;margin-bottom:4px;}
.contact-info-text{color:rgba(255,255,255,0.5);font-size:0.85rem;line-height:1.6;}
.contact-info-link{color:#A78BFA;font-size:0.85rem;font-weight:600;text-decoration:none;margin-top:4px;display:block;}
.contact-info-link:hover{color:#F9A8D4;}
/* Map embed */
.contact-map-wrap{border-radius:20px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);height:200px;background:rgba(255,255,255,0.04);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);font-size:0.9rem;}
/* Social */
.contact-social{display:flex;gap:12px;margin-top:8px;}
.contact-social-btn{width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;font-size:1.1rem;cursor:pointer;transition:all 0.3s;text-decoration:none;}
.contact-social-btn:hover{background:linear-gradient(135deg,#7C3AED,#EC4899);border-color:transparent;transform:translateY(-3px);}
/* Form */
.contact-form-wrap{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:24px;padding:40px;}
.contact-form-title{font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;color:#fff;margin-bottom:8px;}
.contact-form-sub{color:rgba(255,255,255,0.5);font-size:0.9rem;margin-bottom:32px;}
.cf-group{margin-bottom:20px;}
.cf-label{display:block;font-size:0.82rem;font-weight:700;color:rgba(255,255,255,0.65);margin-bottom:8px;}
.cf-input{width:100%;padding:13px 16px;background:rgba(255,255,255,0.06);border:1.5px solid rgba(255,255,255,0.1);border-radius:12px;color:#fff;font-size:0.92rem;font-family:'Inter',sans-serif;outline:none;transition:all 0.3s;}
.cf-input::placeholder{color:rgba(255,255,255,0.25);}
.cf-input:focus{border-color:rgba(6,182,212,0.6);background:rgba(6,182,212,0.06);box-shadow:0 0 0 4px rgba(6,182,212,0.1);}
.cf-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.cf-select{appearance:none;cursor:pointer;}
.cf-textarea{resize:vertical;min-height:130px;}
.cf-submit{width:100%;padding:15px;border-radius:12px;border:none;cursor:pointer;background:linear-gradient(135deg,#06B6D4,#7C3AED);color:#fff;font-size:1rem;font-weight:700;font-family:'Inter',sans-serif;transition:all 0.3s;box-shadow:0 8px 32px rgba(6,182,212,0.35);display:flex;align-items:center;justify-content:center;gap:10px;}
.cf-submit:hover{transform:translateY(-2px);box-shadow:0 14px 40px rgba(6,182,212,0.5);}
.cf-success{background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.3);color:#6EE7B7;padding:16px 20px;border-radius:12px;margin-bottom:24px;display:flex;align-items:center;gap:12px;font-weight:600;}
/* FAQ */
.faq-section{padding:80px 0;}
.faq-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-top:40px;}
.faq-item{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:16px;padding:22px;cursor:pointer;transition:all 0.3s;}
.faq-item:hover{background:rgba(124,58,237,0.08);border-color:rgba(124,58,237,0.25);}
.faq-q{color:#fff;font-weight:700;font-size:0.95rem;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;}
.faq-a{color:rgba(255,255,255,0.55);font-size:0.85rem;line-height:1.7;}
@media(max-width:1024px){.contact-layout{grid-template-columns:1fr}.faq-grid{grid-template-columns:1fr}}
@media(max-width:640px){.cf-row{grid-template-columns:1fr}}
</style>

<div class="contact-hero">
    <div class="contact-hero-content container">
        <div class="contact-tag">📬 Contact Us</div>
        <h1>Get in <span>Touch</span></h1>
        <p>Have a question or need help planning your trip? Our travel experts are here 24/7 to assist you.</p>
    </div>
</div>

<div class="container">
    <div class="contact-layout">
        <!-- LEFT INFO -->
        <div class="contact-info">
            <div class="contact-info-card">
                <div class="contact-info-icon" style="background:rgba(124,58,237,0.15);">📍</div>
                <div>
                    <div class="contact-info-title">Our Office</div>
                    <div class="contact-info-text">123 Travel Street, Suite 500<br>New York, NY 10001, USA</div>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon" style="background:rgba(236,72,153,0.15);">📞</div>
                <div>
                    <div class="contact-info-title">Phone Support</div>
                    <div class="contact-info-text">Available 24/7 for urgent bookings</div>
                    <a href="tel:+18005551234" class="contact-info-link">+1 (800) 555-1234</a>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon" style="background:rgba(6,182,212,0.15);">📧</div>
                <div>
                    <div class="contact-info-title">Email Us</div>
                    <div class="contact-info-text">We reply within 2 hours on business days</div>
                    <a href="mailto:hello@travellux.com" class="contact-info-link">hello@travellux.com</a>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon" style="background:rgba(16,185,129,0.15);">💬</div>
                <div>
                    <div class="contact-info-title">Live Chat</div>
                    <div class="contact-info-text">Chat with our travel experts instantly</div>
                    <a href="#" class="contact-info-link">Start Live Chat →</a>
                </div>
            </div>
            <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:24px;">
                <div style="color:#fff;font-weight:700;margin-bottom:14px;">Follow Us</div>
                <div class="contact-social">
                    <a href="#" class="contact-social-btn">📘</a>
                    <a href="#" class="contact-social-btn">📸</a>
                    <a href="#" class="contact-social-btn">🐦</a>
                    <a href="#" class="contact-social-btn">▶️</a>
                    <a href="#" class="contact-social-btn">💼</a>
                </div>
            </div>
        </div>

        <!-- RIGHT FORM -->
        <div class="contact-form-wrap">
            <div class="contact-form-title">Send Us a Message</div>
            <div class="contact-form-sub">Fill out the form and we'll get back to you within 2 hours.</div>

            <?php if ($sent): ?>
            <div class="cf-success">✅ Message sent! We'll reply within 2 hours.</div>
            <?php endif; ?>

            <form method="POST">
                <div class="cf-row">
                    <div class="cf-group">
                        <label class="cf-label">First Name *</label>
                        <input type="text" class="cf-input" placeholder="John" required>
                    </div>
                    <div class="cf-group">
                        <label class="cf-label">Last Name *</label>
                        <input type="text" class="cf-input" placeholder="Doe" required>
                    </div>
                </div>
                <div class="cf-group">
                    <label class="cf-label">Email Address *</label>
                    <input type="email" class="cf-input" placeholder="you@example.com" required>
                </div>
                <div class="cf-group">
                    <label class="cf-label">Phone Number</label>
                    <input type="tel" class="cf-input" placeholder="+1 234 567 8900">
                </div>
                <div class="cf-group">
                    <label class="cf-label">Subject *</label>
                    <select class="cf-input cf-select" required>
                        <option value="">Select a topic...</option>
                        <option>Booking Inquiry</option>
                        <option>Package Information</option>
                        <option>Cancellation / Refund</option>
                        <option>Technical Support</option>
                        <option>Partnership</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="cf-group">
                    <label class="cf-label">Message *</label>
                    <textarea class="cf-input cf-textarea" placeholder="Tell us how we can help you..." required></textarea>
                </div>
                <button type="submit" class="cf-submit">
                    <span>Send Message</span> <span>✈</span>
                </button>
            </form>
        </div>
    </div>

    <!-- FAQ -->
    <div class="faq-section">
        <div style="text-align:center;margin-bottom:40px;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);color:rgba(255,255,255,0.8);padding:8px 20px;border-radius:50px;font-size:0.78rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:16px;">❓ FAQ</div>
            <h2 style="font-family:'Poppins',sans-serif;font-size:2.2rem;font-weight:900;color:#fff;letter-spacing:-1px;">Frequently Asked <span style="background:linear-gradient(135deg,#FCD34D,#F9A8D4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Questions</span></h2>
        </div>
        <div class="faq-grid">
            <?php foreach([
                ['How do I cancel my booking?','You can cancel any booking from your dashboard up to 24 hours before check-in for a full refund. Go to My Bookings and click Cancel.'],
                ['Is my payment information secure?','Yes. We use bank-level SSL encryption and never store your card details. All payments are processed through certified payment gateways.'],
                ['Can I modify my booking dates?','Yes, you can modify dates up to 48 hours before check-in, subject to availability. Contact our support team for assistance.'],
                ['Do you offer group discounts?','Yes! Groups of 5+ rooms receive 10-15% discount. Contact us directly for group booking inquiries and custom packages.'],
                ['What is your best price guarantee?','If you find a lower price for the same hotel and dates within 24 hours of booking, we\'ll match it and give you an extra 5% off.'],
                ['How do I earn loyalty points?','Every booking earns TravelLux points. Accumulate 1,000 points for a $50 travel credit. Points never expire as long as you book annually.'],
            ] as $faq): ?>
            <div class="faq-item">
                <div class="faq-q"><?= $faq[0] ?> <span style="color:rgba(255,255,255,0.3);">+</span></div>
                <div class="faq-a"><?= $faq[1] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
