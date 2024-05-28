
export function fetchCalendarData(categorie, id) {

    return fetch(`/CRemprunts/public/${categorie}/api/${id}/calendar`)
        .then((response) => {
            if (!response.ok) {
                throw new Error('Failed to fetch calendar data');
            }
            const calendarResponse = response;

            return new Promise((resolve, reject) => {

                calendarResponse.json()
                    .then((calendarData) => {
                        resolve(calendarData);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        });
}


export function initializeCalendar(calendarElt, data) {

    const calendarConfig = {
        initialView: 'timeGridWeek',
        locale: 'fr',
        firstDay: 1,
        timeZone: 'Europe/Paris',
        headerToolbar: {
            start: '',
            center: 'title',
            end: 'today,prev,next',
        },
        contentHeight: 380,
        slotMinTime: '07:00:00',
        slotMaxTime: '19:00:00',
        buttonText: {
            today: 'aujoud\'hui',
            month: 'mois',
            week: 'semaine',
            day: 'jour',
            list: 'liste'
        },
        slotDuration: '01:00:00',
        events: data,
        eventColor: '#970b13',
        eventBackgroundColor: '#970b13',
        eventMinHeight: 20,
        eventTimeFormat: {
            hour: '2-digit',
            omitZeroMinute: true,
        },
        displayEventEnd: true,
        eventOrder: "start,-duration,allDay,title",
        eventClassNames: function () {
            const classes = ['fc-event','background-event'];
            return classes.join(' ');
        },
    };

    const calendar = new FullCalendar.Calendar(calendarElt, calendarConfig);

    return calendar;
}

export function renderCalendar(calendar) {
    // Render the calendar
    calendar.render();
}

