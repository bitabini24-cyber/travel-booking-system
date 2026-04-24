<!-- ===== GLOBAL MODALS ===== -->

<!-- QUICK VIEW MODAL -->
<div class="modal-overlay" id="quickViewModal">
    <div class="modal" style="max-width:700px;">
        <div class="modal-header">
            <h3 style="font-weight:700;" id="quickViewTitle">Hotel Details</h3>
            <button class="modal-close" onclick="closeModal('quickViewModal')">✕</button>
        </div>
        <div id="quickViewContent">
            <div class="spinner"></div>
        </div>
    </div>
</div>

<!-- IMAGE GALLERY MODAL -->
<div class="modal-overlay" id="galleryModal">
    <div class="modal" style="max-width:900px; background:var(--dark); padding:20px;">
        <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:16px; margin-bottom:16px;">
            <h3 style="color:var(--white); font-weight:700;">Gallery</h3>
            <button class="modal-close" style="color:var(--white);" onclick="closeModal('galleryModal')">✕</button>
        </div>
        <div id="galleryContent" style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px;"></div>
    </div>
</div>

<!-- CONFIRM MODAL -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal" style="max-width:420px; text-align:center;">
        <div style="font-size:3rem; margin-bottom:16px;" id="confirmIcon">⚠️</div>
        <h3 style="font-weight:700; margin-bottom:8px;" id="confirmTitle">Are you sure?</h3>
        <p style="color:var(--text-light); margin-bottom:28px;" id="confirmMessage">This action cannot be undone.</p>
        <div style="display:flex; gap:12px; justify-content:center;">
            <button class="btn btn-outline" style="color:var(--text); border-color:var(--border);"
                    onclick="closeModal('confirmModal')">Cancel</button>
            <button class="btn btn-danger" id="confirmAction">Confirm</button>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id)?.classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id)?.classList.remove('active');
    document.body.style.overflow = '';
}

// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
        if (e.target === overlay) closeModal(overlay.id);
    });
});

// Close on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => closeModal(m.id));
    }
});

// Confirm modal helper
function showConfirm(title, message, onConfirm, icon = '⚠️') {
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmIcon').textContent = icon;
    const btn = document.getElementById('confirmAction');
    btn.onclick = () => { closeModal('confirmModal'); onConfirm(); };
    openModal('confirmModal');
}
</script>
