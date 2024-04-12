import { initializeCalendar, renderCalendar, fetchCalendarData} from './calendrier.js';


    const calendarElements = document.querySelectorAll('.catalogue_calendrier');

    calendarElements.forEach((calendarElt) => {
        const id = calendarElt.id.split('_')[1];
        const categorie =  calendarElt.id.split('_')[0];

        fetchCalendarData(categorie, id)
            .then((calendarData) => {
                // Initialize the calendar for the current vehicle
                const calendar = initializeCalendar(calendarElt, calendarData);
                // Render the calendar for the current vehicle
                renderCalendar(calendar);

            })
            .catch((error) => {
                // Handle any errors that occur during the data fetching process
                console.error(error);
            });
    });
