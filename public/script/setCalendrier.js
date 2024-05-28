import { initializeCalendar, renderCalendar, fetchCalendarData} from './calendrier.js';


    const calendarElements = document.querySelectorAll('.catalogue_calendrier');

    calendarElements.forEach((calendarElt) => {
        const id = calendarElt.id.split('_')[1];
        const categorie =  calendarElt.id.split('_')[0];

        fetchCalendarData(categorie, id)
            .then((calendarData) => {

                const calendar = initializeCalendar(calendarElt, calendarData);

                renderCalendar(calendar);

            })
            .catch((error) => {

                console.error(error);
            });
    });
