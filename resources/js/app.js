import './bootstrap';

import Alpine from 'alpinejs';

import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) return;

    const calendar = new Calendar(calendarEl, {
        plugins: [timeGridPlugin, interactionPlugin],
        initialView: 'timeGridDay',
        slotMinTime: '08:00:00',
        slotMaxTime: '18:30:00',
        slotDuration: '00:30:00',
        slotLabelInterval: '00:30',
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false,
            hour12: false
        },
        expandRows: true,
        allDaySlot: false,
        editable: true,
        droppable: true,

        events: [
            {
                daysOfWeek: [1, 2, 3, 4, 5],
                startTime: '14:00:00',
                endTime: '15:00:00',
                display: 'background',
                color: 'rgba(0,123,255,0.78)'
            }
        ],
    });

    calendar.render();
});
