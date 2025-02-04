const baseUrl = "http://localhost:3001/";
const getFormData = (formId) => {
  const form = document.getElementById(formId);
  const formData = {};

  for (let i = 0; i < form.elements.length; i++) {
    const field = form.elements[i];
    if (field.name) {
      if (field.type === "checkbox") {
        formData[field.name] = field.checked ? true : false;
      } else if (field.type === "date") {
        formData[field.name] = new Date(field.value);
      } else {
        formData[field.name] = field.value;
      }
    }
  }
  return formData;
};

function getFormattedDateForInput(inputDate) {
  var dateObj = new Date(inputDate);

  // Format the date to mm/dd/yy
  var formattedDate =
    ("0" + (dateObj.getMonth() + 1)).slice(-2) +
    "/" +
    ("0" + dateObj.getDate()).slice(-2) +
    "/" +
    dateObj.getFullYear().toString().slice(-2);

  var isoFormattedDate = dateObj.toISOString().split("T")[0];
  return isoFormattedDate;
}

function setFormData(formId, response) {
  const form = document.getElementById(formId);
  if (Array.isArray(response)) {
    response = response[0];
  }

  for (const key in response) {
    const input = form.querySelector(`[name="${key}"], [id="${key}"]`);
    if (input) {
      if (input?.type === "date") {
        input.value = getFormattedDateForInput(response[key]); // new Date(response[key]);
      } else {
        input.value = response[key];
      }
    }
  }
}

function generateUUID() {
  return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
    const r = (Math.random() * 16) | 0;
    const v = c === "x" ? r : (r & 0x3) | 0x8;
    return v.toString(16);
  });
}

function GetFormattedDate(dateString) {
  const date = new Date(dateString);
  // Extract parts
  const day = String(date.getDate()).padStart(2, "0");
  const monthNames = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];
  const month = monthNames[date.getMonth()];
  const year = date.getFullYear();
  const hours = String(date.getHours()).padStart(2, "0");
  const minutes = String(date.getMinutes()).padStart(2, "0");
  const seconds = String(date.getSeconds()).padStart(2, "0");

  return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
}

function destroyDataTableIfExists(tableId) {
  if ($.fn.DataTable.isDataTable(tableId)) {
    $(tableId).DataTable().destroy();
  }
}

function prepareDataTable(tableId) {
  $(tableId).DataTable({
    // Set the default number of rows per page
    pageLength: 10,
    // Additional options if needed
    lengthMenu: [10, 20, 50, 100], // Include 7 in the dropdown menu
    paging: true,
    searching: true,
    ordering: true,
    order: [],
    language: {
      search: "Find:",
      lengthMenu: "Display _MENU_ records per page",
      info: "Showing _START_ to _END_ of _TOTAL_ entries",
      paginate: {
        first: "First",
        last: "Last",
        next: "Next",
        previous: "Previous",
      },
    },
  });
}
