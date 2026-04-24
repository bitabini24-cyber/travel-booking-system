// ===== BOOKING PRICE CALCULATOR =====
document.addEventListener('DOMContentLoaded', () => {
    const checkIn = document.getElementById('bookCheckIn');
    const checkOut = document.getElementById('bookCheckOut');
    const summary = document.getElementById('priceSummary');
    const nightsLabel = document.getElementById('nightsLabel');
    const nightsPrice = document.getElementById('nightsPrice');
    const totalPrice = document.getElementById('totalPrice');
    const roomsSelect = document.querySelector('select[name="rooms"]');

    if (!checkIn || !checkOut || typeof hotelPrice === 'undefined') return;

    function calcAndShow() {
        const inDate = new Date(checkIn.value);
        const outDate = new Date(checkOut.value);
        if (!checkIn.value || !checkOut.value || outDate <= inDate) {
            if (summary) summary.style.display = 'none';
            return;
        }
        const nights = Math.round((outDate - inDate) / (1000 * 60 * 60 * 24));
        const rooms = parseInt(roomsSelect?.value || 1);
        const total = hotelPrice * nights * rooms;

        if (nightsLabel) nightsLabel.textContent = `${nights} night${nights > 1 ? 's' : ''} × ${rooms} room${rooms > 1 ? 's' : ''}`;
        if (nightsPrice) nightsPrice.textContent = `$${total.toFixed(2)}`;
        if (totalPrice) totalPrice.textContent = `$${total.toFixed(2)}`;
        if (summary) summary.style.display = 'block';
    }

    checkIn.addEventListener('change', () => {
        // Ensure check-out is after check-in
        const minOut = new Date(checkIn.value);
        minOut.setDate(minOut.getDate() + 1);
        checkOut.min = minOut.toISOString().split('T')[0];
        if (checkOut.value && new Date(checkOut.value) <= new Date(checkIn.value)) {
            checkOut.value = minOut.toISOString().split('T')[0];
        }
        calcAndShow();
    });

    checkOut.addEventListener('change', calcAndShow);
    roomsSelect?.addEventListener('change', calcAndShow);

    // Initial calc if dates pre-filled
    calcAndShow();

    // Form validation
    const form = document.getElementById('bookingForm');
    if (form) {
        form.addEventListener('submit', (e) => {
            if (!checkIn.value || !checkOut.value) {
                e.preventDefault();
                showToast('Please select check-in and check-out dates.', 'error');
                return;
            }
            if (new Date(checkOut.value) <= new Date(checkIn.value)) {
                e.preventDefault();
                showToast('Check-out must be after check-in.', 'error');
            }
        });
    }
});
