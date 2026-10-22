document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-site-nav]');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        nav.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    const galleryView = document.getElementById('gallery-view');
    const galleryFull = document.getElementById('gallery-full');

    if (galleryView && galleryFull) {
        document.querySelectorAll('[data-gallery]').forEach((button) => {
            button.addEventListener('click', () => {
                galleryFull.src = button.dataset.gallery;
                galleryView.showModal();
            });
        });

        galleryView.addEventListener('click', (event) => {
            if (event.target === galleryView) {
                galleryView.close();
            }
        });
    }

    const bookingForm = document.getElementById('booking-form');

    if (!bookingForm) {
        return;
    }

    const packageField = bookingForm.querySelector('[name="package"]');
    const dateField = bookingForm.querySelector('[name="date"]');
    const postcodeField = bookingForm.querySelector('[name="postcode"]');
    const slotBox = document.getElementById('slot-list');
    const centreField = document.getElementById('test-centre-field');
    const packages = JSON.parse(document.getElementById('booking-packages')?.textContent || '{}');
    const summaryLesson = document.getElementById('summary-lesson');
    const summaryDate = document.getElementById('summary-date');
    const summaryDateRow = document.getElementById('summary-date-row');
    const summaryTime = document.getElementById('summary-time');
    const summaryPostcode = document.getElementById('summary-postcode');
    const summaryPostcodeRow = document.getElementById('summary-postcode-row');
    const summaryTotal = document.getElementById('summary-total');
    const summaryLessonPrice = document.getElementById('summary-lesson-price');
    const summaryDeposit = document.getElementById('summary-deposit');
    const summaryBalance = document.getElementById('summary-balance');
    const otherField = document.getElementById('postcode-other-field');
    const otherInput = document.getElementById('postcode-other');

    const updateSummary = () => {
        const pack = packages[packageField.value];
        if (pack && summaryLesson) {
            summaryLesson.innerHTML = `${pack.name}<br><span>${pack.hours} hours · Manual</span>`;
            if (summaryLessonPrice) summaryLessonPrice.textContent = `£${pack.price}`;
            if (summaryDeposit) summaryDeposit.textContent = `£${pack.deposit}`;
            if (summaryBalance) summaryBalance.textContent = `£${pack.balance}`;
            if (summaryTotal) summaryTotal.textContent = `£${pack.deposit}`;
        }

        if (summaryDate && summaryDateRow) {
            summaryDateRow.classList.toggle('is-hidden', !dateField.value);
            if (dateField.value) {
                const picked = new Date(`${dateField.value}T12:00:00`);
                summaryDate.textContent = picked.toLocaleDateString('en-GB', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                });
            }
        }

        const chosen = slotBox.querySelector('input:checked');
        if (summaryTime) {
            summaryTime.textContent = chosen
                ? chosen.closest('label').querySelector('span').textContent
                : 'Choose a time';
        }

        if (summaryPostcode && summaryPostcodeRow) {
            const postcode = postcodeField.value === 'other'
                ? (otherInput?.value.trim() ?? '')
                : postcodeField.value.trim();
            summaryPostcodeRow.classList.toggle('is-hidden', postcode === '');
            if (postcode !== '') {
                summaryPostcode.textContent = postcode;
            }
        }
    };

    const toggleOtherPostcode = () => {
        const open = postcodeField.value === 'other';
        otherField?.classList.toggle('is-hidden', !open);
        if (otherInput) {
            otherInput.required = open;
        }
        updateSummary();
    };

    const slotNotice = (message) => `<p class="slot-notice">${message}</p>`;

    const loadSlots = async () => {
        const selected = packageField.value;
        centreField?.classList.toggle('is-hidden', selected !== 'test');
        updateSummary();

        if (!dateField.value) {
            slotBox.innerHTML = slotNotice('Choose a date to see free times.');
            return;
        }

        slotBox.innerHTML = '<p class="form-note">Checking free times…</p>';

        try {
            const url = new URL(bookingForm.dataset.slotsUrl, window.location.origin);
            url.searchParams.set('package', selected);
            url.searchParams.set('date', dateField.value);
            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            const data = await response.json();
            const slots = data.slots ?? [];

            if (slots.length === 0) {
                slotBox.innerHTML = slotNotice('No times on this day. Lessons are Monday to Sunday, and each session must finish by 10:00pm.');
                updateSummary();
                return;
            }

            slotBox.innerHTML = slots.map((slot) => `
                <label class="slot-option ${slot.available ? '' : 'is-disabled'}">
                    <span>${slot.label}</span>
                    <input type="radio" name="slot" value="${slot.start}" ${slot.available ? 'required' : 'disabled'}>
                </label>
            `).join('');
            updateSummary();
        } catch (error) {
            slotBox.innerHTML = slotNotice('The times could not be loaded. Refresh the page or call the school.');
        }
    };

    packageField.addEventListener('change', loadSlots);
    dateField.addEventListener('change', loadSlots);
    postcodeField.addEventListener('change', toggleOtherPostcode);
    postcodeField.addEventListener('input', updateSummary);
    otherInput?.addEventListener('input', updateSummary);
    slotBox.addEventListener('change', updateSummary);
    toggleOtherPostcode();
    loadSlots();
});
