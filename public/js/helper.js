
const dateToString = (dateTimeString) => {
	if (!dateTimeString || !/^\d{4}-\d{2}-\d{2} \d{1,2}:\d{2}:\d{2}$/.test(dateTimeString)) {
		return "";
	}

	const datePart = dateTimeString.split(' ')[0];
	const [year, month, day] = datePart.split('-');
	return `${day}-${month}-${year}`;
};

const dateToStringAlt = (dateTimeString) => {
	const datePart = dateTimeString.split(' ')[0];
	const [year, month, day] = datePart.split('-');
	return `${day}-${month}-${year}`;
};


const stringToISO = (dateString) => {
  const [day, month, year] = dateString.split('-');
  return `${year}-${month}-${day}`;
};

const formatNumber = (numeric_value) => parseFloat(numeric_value).toLocaleString('en-US', {
  style: 'decimal',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
});

const config = {
  'ID_LENGTH': 6,	
  'ID_TURNERO_LENGTH': 4,
  'ID_TURNERO_BASE': 1000,
  'ID_RESERVA_LENGTH': 6,
  'ID_RESERVA_BASE': 100000,
}

const fixedTimeMoment = (dateTimeString, format) => {
	if (!dateTimeString || !/^\d{4}-\d{2}-\d{2} \d{1,2}:\d{2}:\d{2}$/.test(dateTimeString)) {
		return "";
	}
	const time = dateTimeString.split(' ')[1];
	// return moment(time, 'HH:mm:ss').format(format) + " hrs.";
	return moment(time, 'HH:mm:ss').format(format);
}

const calculateDaysBetween = (startDay, endDay) => {
  const start = moment(startDay, "YYYY-MM-DD");
  const end = moment(endDay, "YYYY-MM-DD");
  return end.diff(start, 'days') + 1;
};

const format_id = (id, type) => {
  let base;

  switch(type) {
    case "id":
        return String(Number(id)).padStart(config.ID_LENGTH, '0');
      break;
		case "turnero":
				base = config.ID_TURNERO_BASE;
				return String(base + Number(id)).padStart(config.ID_TURNERO_LENGTH, '0');
			break;
		case "reserva":
					base = config.ID_RESERVA_BASE;
				return String(base + Number(id)).padStart(config.ID_RESERVA_LENGTH, '0');
			break;
  }
}


const restore_format_id = (numberString, type) => {
  let base;

  switch(type) {
    case "id":
        return Number(numberString);
      break;
    case "turnero":
          base = config.ID_TURNERO_BASE;
        return Number(numberString) - base;
      break;
    case "reserva":
          base = config.ID_RESERVA_BASE;
        return Number(numberString) - base;
      break;
  }
}




const ellipsis = (string, maxLength) => {
  if (string.length <= maxLength) {
    return string;
  }

  const string_ellipsis = string.substring(0, maxLength);
  return `${string_ellipsis}.`;
};

/**
 * Extracts the file name from a file path and encodes it for use in URLs.
 * @param {string} filePath - The file path string.
 * @returns {string} - The URL-encoded file name.
 */
const getEncodedFileName = (filePath) => {
  const parts = filePath.split("\/"); // Split the file path
  const fileName = parts[parts.length - 1]; // Get the last part (file name)
	const fileNameFix = fileName.replace(/ /g, "_"); 
  return encodeURIComponent(fileNameFix); // Encode and return
};

const getTimeDiff = (start, end) => {
	if(!start || !end) {
		return "";
	}

	const startTime = moment(start, "YYYY-MM-DD HH:mm:ss");
	const endTime = moment(end, "YYYY-MM-DD HH:mm:ss");

	const duration = moment.duration(endTime.diff(startTime));
	const hours = Math.floor(duration.asHours());
	const minutes = duration.minutes();

	return `${hours} h. ${minutes} min.`;
};
 
const formatToMonthYear = (dateStr) => {
  return moment(dateStr).format('MMM-YYYY').toUpperCase();
};



const getTimeDiffArray = (start, end) => {
	if (!start) {
			return "";
	}

	let totalMinutes = 0;

	// Helper to process one pair
	const calculateDuration = (s, e) => {
			if (!s || !e) return 0;
			const startTime = moment(s, "YYYY-MM-DD HH:mm:ss");
			const endTime = moment(e, "YYYY-MM-DD HH:mm:ss");
			return moment.duration(endTime.diff(startTime)).asMinutes();
	};

	// Check if input is an array of arrays
	if (Array.isArray(start) && Array.isArray(start[0])) {
			for (let [s, e] of start) {
					totalMinutes += calculateDuration(s, e);
			}
	} else {
			// Assume it's a single pair
			totalMinutes = calculateDuration(start, end);
	}

	const hours = Math.floor(totalMinutes / 60);
	const minutes = Math.round(totalMinutes % 60);

	return `${hours} h. ${minutes} min.`;
};


const disableOptions = (estado_ticket) => {
	const select = document.getElementById("estado_ticket1");
	// const selectedValue = select.value;

	// Enable all options first
	[...select.options].forEach(option => option.disabled = false);

	// If "EN PROGRESO" (value = "2") is selected, disable "ABIERTO" (1) and "EN PROGRESO" (2)
	if ( estado_ticket === "2") {
			select.querySelector('option[value="1"]').disabled = true;
			select.querySelector('option[value="2"]').disabled = true;
			select.querySelector('option[value=""]').disabled = true;
	}
	if ( estado_ticket === "3") {
			select.querySelector('option[value="1"]').disabled = true;
			select.querySelector('option[value="2"]').disabled = true;
			select.querySelector('option[value="3"]').disabled = true;
			select.querySelector('option[value=""]').disabled = true;
	}
	if ( estado_ticket === "4") {
		select.querySelector('option[value="1"]').disabled = true;
		select.querySelector('option[value="2"]').disabled = true;
		select.querySelector('option[value="3"]').disabled = true;
		select.querySelector('option[value="4"]').disabled = true;
		select.querySelector('option[value=""]').disabled = true;
	}
};

const setSelectedOption = (selectId, value, type = null) => {
  const select = document.querySelector(selectId);
  [...select.options].forEach(option => {
    if(type == 'string') {
      option.selected = option.value == value;
    } else {
      option.selected = parseInt(option.value) === value;
    }
  });
}

const setDateToInput = (inputId, datetimeString) => {
  const input = document.querySelector(inputId);
  const date = datetimeString.split(' ')[0];
  input.value = date;
};


const setTicketStatus = (status) => {
  switch(status) {
    case "1":
			return {'text': 'ABIERTO', 'color': 'text-gray'};
    break;
    case "2":
			return {'text': 'EN PROGRESO', 'color': 'text-link'};
    break;
    case "3":
			return {'text': 'RESUELTO', 'color': 'text-icon'};
    break;
    case "4":
			return {'text': 'CERRADO', 'color': 'text-red'};
    break;
  }
}

const setArticleStatusText = (status) => {
  switch(status) {
    case "PENDIENTE":
    return 'text-pendiente';
    break;
    case "APROBADO":
    return 'text-aprobado';
    break;
    case "NO APROBADO":
    return 'text-rechazado';
    break;
  }
}

const setArticleStatus = (status) => {
  switch(status) {
    case "PENDIENTE":
    return {'color': 'text-pendiente border-pendiente text-opacity-0', 'icon': '<i class="fa fa-minus px-1"></i>'};
    break;
    case "APROBADO":
    return {'color': 'text-aprobado border-aprobado', 'icon': '<i class="fa fa-check px-1"></i>'};
    break;
    case "NO APROBADO":
    return {'color': 'text-rechazado border-rechazado', 'icon': '<i class="fa fa-minus px-1"></i>'};
    break;
  }
}

const getIcon = (icon_name) => {
  let icons = {
    'clip': `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
            </svg>`,
    'search': `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>`,
    'edit': `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
              </svg>`,
    'delete': `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>`,
		'eye': `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  						<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
  						<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
						</svg>`,
  };

  return icons[icon_name];                      
}

const setRowValidation = (costos, desarrollo, calidad) => {
	console.log(costos, desarrollo, calidad)
	if (costos == "APROBADO" && desarrollo == "APROBADO" && calidad == "APROBADO") {
		return "row__green";
	} else if (costos == "NO APROBADO" && (desarrollo == "APROBADO" || "NO APROBADO") && (calidad == "APROBADO" || "NO APROBADO")) {
		return "";
	} else if ((costos == "PENDIENTE" || "APROBADO" || "NO APROBADO")  &&  (desarrollo == "NO APROBADO" || calidad == "NO APROBADO")) {
		return "row__red";
	} 
}	

const capitalize = (str) => {
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

const resetSelect = (selectId) => {
	const select = document.getElementById(selectId);
	select.innerHTML = '<option value="" disabled selected>Seleccionar...</option>';
};

const formatNumberMex = (number, decimals = 2) => 
    number.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });

const dateToFormatUS = (dateTimeString) => {
	const datePart = dateTimeString.split(' ')[0];
	const [year, month, day] = datePart.split('-');
	return `${month}-${day}-${year}`;
};

const dateToFormatEU = (dateTimeString) => {
	const datePart = dateTimeString.split(' ')[0];
	const [year, month, day] = datePart.split('-');
	return `${day}-${month}-${year}`;
};

const timeToFormat = (timeString, format = "HH:mm") => {
	if (!timeString) return "";

	const [hh, mm, ss = "00"] = timeString.split(":");

	if (format === "HH:mm:ss") {
		return `${hh}:${mm}:${ss}`;
	}

	return `${hh}:${mm}`;
};

const debugFormData = (formData) => {
    const formDataObj = {};

    formData.forEach((value, key) => {
        if (!formDataObj[key]) {
            formDataObj[key] = [];
        }
        formDataObj[key].push(value);
    });

    console.log(formDataObj);
};

const refreshCsrfTokens = (token) => {
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    const csrfInput = form.querySelector(`input[name="${token.name}"]`);
    if (csrfInput) {
      csrfInput.value = token.hash;
    }
  });
};