@push('scripts')
<script>
(function () {
    const DAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    function parseTimeToMinutes(t) {
        const m = /^(\d{2}):(\d{2})$/.exec(String(t || ''));
        if (!m) {
            return null;
        }
        return parseInt(m[1], 10) * 60 + parseInt(m[2], 10);
    }

    function formatMinutes(mins) {
        const h = Math.floor(mins / 60);
        const m = mins % 60;
        return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
    }

    function buildSlotsForDuration(startM, endM, durationM) {
        const slots = [];
        for (let t = startM; t + durationM <= endM; t += durationM) {
            slots.push(formatMinutes(t) + '|' + formatMinutes(t + durationM));
        }
        return slots;
    }

    function ensureAtLeastOneGymSlot(day) {
        const row = document.querySelector('[data-day-schedule-row="' + day + '"]');
        if (!row) {
            return;
        }
        const boxes = row.querySelectorAll('input[name="gym_availability[' + day + '][slot_duration][]"]');
        const checked = row.querySelectorAll('input[name="gym_availability[' + day + '][slot_duration][]"]:checked');
        if (boxes.length && checked.length === 0) {
            boxes[0].checked = true;
        }
    }

    function getGymDayState(day) {
        const row = document.querySelector('[data-day-schedule-row="' + day + '"]');
        if (!row) {
            return { closed: true, start: '', end: '', durations: [] };
        }
        const closed = !!row.querySelector('[data-gym-closed-checkbox]')?.checked;
        const start = row.querySelector('input[name="gym_availability[' + day + '][start_time]"]')?.value || '';
        const end = row.querySelector('input[name="gym_availability[' + day + '][end_time]"]')?.value || '';
        const checked = row.querySelectorAll('input[name="gym_availability[' + day + '][slot_duration][]"]:checked');
        const durations = Array.from(checked)
            .map(function (i) { return parseInt(i.value, 10); })
            .filter(function (n) { return n === 40 || n === 60; });
        return { closed: closed, start: start, end: end, durations: durations };
    }

    function syncGymRowDisabled(day) {
        const row = document.querySelector('[data-day-schedule-row="' + day + '"]');
        if (!row) {
            return;
        }
        const cb = row.querySelector('[data-gym-closed-checkbox]');
        const times = row.querySelectorAll('[data-gym-time-input]');
        const closed = !!cb?.checked;
        times.forEach(function (t) {
            t.disabled = closed;
            t.classList.toggle('bg-light', closed);
        });
    }

    function refreshPtTimeRangeDropdowns(day) {
        const row = document.querySelector('[data-day-pt-row="' + day + '"]');
        if (!row) {
            return;
        }
        const list = row.querySelector('[data-pt-time-list="' + day + '"]');
        if (!list) {
            return;
        }
        let initial = [];
        try {
            initial = JSON.parse(row.getAttribute('data-initial-pt-slots') || '[]');
        } catch (e) {
            initial = [];
        }
        if (!Array.isArray(initial)) {
            initial = [];
        }
        const existingChecked = Array.from(row.querySelectorAll('.pt-time-slot-cb:checked')).map(function (c) {
            return c.value;
        });
        const preset = existingChecked.length ? existingChecked : initial;

        const gym = getGymDayState(day);
        const ptClosed = !!row.querySelector('[data-pt-closed-checkbox]')?.checked;
        const hint = row.querySelector('[data-pt-no-hour-slots-hint="' + day + '"]');
        const actions = row.querySelector('[data-pt-slot-actions="' + day + '"]');

        list.innerHTML = '';

        const cell = row.querySelector('.pt-time-slots-cell');
        if (cell) {
            cell.classList.toggle('opacity-50', ptClosed || gym.closed);
        }

        if (hint) {
            hint.classList.add('d-none');
        }
        if (actions) {
            actions.classList.remove('d-none');
        }

        if (ptClosed || gym.closed || !gym.start || !gym.end) {
            return;
        }

        const s = parseTimeToMinutes(gym.start);
        const e = parseTimeToMinutes(gym.end);
        if (s === null || e === null || s >= e) {
            return;
        }

        if (gym.durations.indexOf(60) === -1) {
            if (hint) {
                hint.classList.remove('d-none');
            }
            if (actions) {
                actions.classList.add('d-none');
            }
            return;
        }

        const unique = buildSlotsForDuration(s, e, 60);

        unique.forEach(function (val) {
            const safeId = 'pt_slot_' + day + '_' + val.replace(/[^0-9A-Za-z]/g, '_');
            const wrap = document.createElement('div');
            wrap.className = 'form-check mb-1';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'form-check-input pt-time-slot-cb';
            input.name = 'pt_time_slots[' + day + '][]';
            input.value = val;
            input.id = safeId;
            input.checked = preset.indexOf(val) !== -1;
            const label = document.createElement('label');
            label.className = 'form-check-label small';
            label.setAttribute('for', safeId);
            label.textContent = val.replace('|', ' – ');
            wrap.appendChild(input);
            wrap.appendChild(label);
            list.appendChild(wrap);
        });
    }

    function syncPtRowDisabled(day) {
        const row = document.querySelector('[data-day-pt-row="' + day + '"]');
        if (!row) {
            return;
        }
        const cb = row.querySelector('[data-pt-closed-checkbox]');
        const closed = !!cb?.checked;
        const list = row.querySelector('[data-pt-time-list="' + day + '"]');
        if (list) {
            list.querySelectorAll('.pt-time-slot-cb').forEach(function (c) {
                c.disabled = closed;
            });
        }
    }

    function refreshAllPtForDay(day) {
        refreshPtTimeRangeDropdowns(day);
        syncPtRowDisabled(day);
    }

    function refreshEveryDay() {
        DAYS.forEach(function (day) {
            syncGymRowDisabled(day);
            refreshAllPtForDay(day);
        });
    }

    const block = document.getElementById('equipment-block');
    const container = document.getElementById('equipment-rows');
    const template = document.getElementById('equipment-row-template');
    if (block && container && template) {
        let nextIndex = container.querySelectorAll('[data-equipment-row]').length;
        block.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-remove-equipment]');
            if (btn) {
                const rows = container.querySelectorAll('[data-equipment-row]');
                const row = btn.closest('[data-equipment-row]');
                if (rows.length > 1 && row) {
                    row.remove();
                }
            }
            const addBtn = e.target.closest('[data-add-equipment]');
            if (addBtn) {
                const html = template.innerHTML.replace(/__INDEX__/g, String(nextIndex++));
                const wrap = document.createElement('div');
                wrap.innerHTML = html.trim();
                const node = wrap.firstElementChild;
                if (node) {
                    container.appendChild(node);
                }
            }
        });
    }
    document.querySelectorAll('[data-upload-trigger]').forEach(function (zone) {
        if (zone.dataset.uploadBound === '1') {
            return;
        }
        zone.dataset.uploadBound = '1';
        const id = zone.getAttribute('data-upload-trigger');
        const input = id ? document.getElementById(id) : null;
        if (!input) {
            return;
        }
        // Zones are <label> wrapping the file input: the browser opens the picker once.
        // Do not call input.click() here — that fires a second dialog (label + programmatic click).
        zone.addEventListener('dragover', function (e) {
            e.preventDefault();
            zone.classList.add('border-primary');
        });
        zone.addEventListener('dragleave', function () {
            zone.classList.remove('border-primary');
        });
        zone.addEventListener('drop', function (e) {
            e.preventDefault();
            zone.classList.remove('border-primary');
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        input.addEventListener('change', function () {
            const label = zone.querySelector('span.text-muted');
            if (label && input.files && input.files.length) {
                if (input.multiple) {
                    label.textContent = input.files.length + ' ' + @json(__('file(s) selected'));
                } else {
                    label.textContent = input.files[0].name;
                }
            }
        });
    });

    document.querySelectorAll('[data-day-schedule-row]').forEach(function (row) {
        const day = row.getAttribute('data-day-schedule-row');
        const cb = row.querySelector('[data-gym-closed-checkbox]');
        if (cb) {
            cb.addEventListener('change', function () {
                syncGymRowDisabled(day);
                refreshAllPtForDay(day);
            });
        }
        row.querySelectorAll('[data-gym-time-input], [data-gym-slot-duration]').forEach(function (el) {
            el.addEventListener('change', function () {
                if (el.matches('[data-gym-slot-duration]')) {
                    ensureAtLeastOneGymSlot(day);
                }
                refreshAllPtForDay(day);
            });
        });
        syncGymRowDisabled(day);
    });

    document.querySelector('[data-apply-all-availability]')?.addEventListener('click', function () {
        const mon = document.querySelector('[data-day-schedule-row="monday"]');
        if (!mon) {
            return;
        }
        const closed = mon.querySelector('[data-gym-closed-checkbox]');
        const start = mon.querySelector('input[name="gym_availability[monday][start_time]"]');
        const end = mon.querySelector('input[name="gym_availability[monday][end_time]"]');
        const slotChecks = mon.querySelectorAll('input[name="gym_availability[monday][slot_duration][]"]');
        const days = ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        days.forEach(function (day) {
            const row = document.querySelector('[data-day-schedule-row="' + day + '"]');
            if (!row) {
                return;
            }
            const c = row.querySelector('[data-gym-closed-checkbox]');
            const s = row.querySelector('input[name="gym_availability[' + day + '][start_time]"]');
            const eInp = row.querySelector('input[name="gym_availability[' + day + '][end_time]"]');
            if (c && closed) {
                c.checked = closed.checked;
            }
            if (s && start) {
                s.value = start.value;
            }
            if (eInp && end) {
                eInp.value = end.value;
            }
            const targets = row.querySelectorAll('input[name="gym_availability[' + day + '][slot_duration][]"]');
            slotChecks.forEach(function (src, i) {
                const tgt = targets[i];
                if (tgt && src) {
                    tgt.checked = src.checked;
                }
            });
            syncGymRowDisabled(day);
            refreshAllPtForDay(day);
        });
    });

    const ptSelect = document.querySelector('[data-pt-available-toggle]');
    const ptDetails = document.querySelector('[data-pt-details]');
    function syncPtDetailsVisibility() {
        if (!ptDetails || !ptSelect) {
            return;
        }
        const on = ptSelect.value === '1';
        ptDetails.classList.toggle('d-none', !on);
        if (on) {
            refreshEveryDay();
        }
    }
    if (ptSelect) {
        ptSelect.addEventListener('change', syncPtDetailsVisibility);
        syncPtDetailsVisibility();
    }

    document.querySelectorAll('[data-day-pt-row]').forEach(function (row) {
        const day = row.getAttribute('data-day-pt-row');
        const cb = row.querySelector('[data-pt-closed-checkbox]');
        if (cb) {
            cb.addEventListener('change', function () {
                syncPtRowDisabled(day);
                refreshPtTimeRangeDropdowns(day);
            });
        }
    });

    document.addEventListener('click', function (e) {
        const sel = e.target.closest('[data-pt-select-all-day]');
        if (sel) {
            e.preventDefault();
            const day = sel.getAttribute('data-pt-select-all-day');
            const row = document.querySelector('[data-day-pt-row="' + day + '"]');
            row?.querySelectorAll('.pt-time-slot-cb:not(:disabled)').forEach(function (c) {
                c.checked = true;
            });
            return;
        }
        const clr = e.target.closest('[data-pt-clear-day]');
        if (clr) {
            e.preventDefault();
            const day = clr.getAttribute('data-pt-clear-day');
            const row = document.querySelector('[data-day-pt-row="' + day + '"]');
            row?.querySelectorAll('.pt-time-slot-cb').forEach(function (c) {
                c.checked = false;
            });
        }
    });

    document.querySelector('[data-apply-all-pt-availability]')?.addEventListener('click', function () {
        const mon = document.querySelector('[data-day-pt-row="monday"]');
        if (!mon) {
            return;
        }
        const closed = mon.querySelector('[data-pt-closed-checkbox]');
        const monPreset = Array.from(mon.querySelectorAll('.pt-time-slot-cb:checked')).map(function (c) {
            return c.value;
        });
        const days = ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        days.forEach(function (day) {
            const row = document.querySelector('[data-day-pt-row="' + day + '"]');
            if (!row) {
                return;
            }
            const c = row.querySelector('[data-pt-closed-checkbox]');
            if (c && closed) {
                c.checked = closed.checked;
            }
            refreshAllPtForDay(day);
            Array.from(row.querySelectorAll('.pt-time-slot-cb')).forEach(function (cbx) {
                cbx.checked = monPreset.indexOf(cbx.value) !== -1;
            });
        });
    });

    refreshEveryDay();
})();
</script>
@endpush
