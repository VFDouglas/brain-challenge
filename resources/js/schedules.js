'use strict';
for (const item of document.querySelectorAll('.btn_schedule_day')) {
    item.addEventListener('click', function () {
        let day = item.getAttribute('data-day');
        window.logAccess('Schedules', `Clicked to see the schedule of day ${day}`);
        window.location.replace(`/schedules?day=${day}`);
    });
}
window.logAccess('Schedules', 'Loaded schedules page');
setTimeout(() => {
    window.location.reload();
}, 60000);
