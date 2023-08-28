/*document.addEventListener('DOMContentLoaded', function () {
    const datepickerInput = document.getElementById('datepicker');
    const selectedDate = document.getElementById('selected-date');*/
  
    // Initialize Pikaday date picker
    /*const picker = new Pikaday({
      field: datepickerInput,
      format: 'YYYY-MM-DD',
      toString(date) {
        // Format the date to display in the input field
        return date ? date.toISOString().slice(0, 10) : '';
      },
      onSelect: function (date) {
        // Update the selected date in the paragraph
        selectedDate.textContent = date ? date.toISOString().slice(0, 10) : '–';
      },
    });
  });*/
  document.addEventListener('DOMContentLoaded', function () {
    const defaultZeroDate = new Date(0);
    const emptyDateValue = '0000-00-00';

    function formatDate(date) {
        if (!date || +date === +defaultZeroDate) {
            return emptyDateValue;
        }

        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
    }

    function initializeDatepicker(elementId) {
        const field = document.getElementById(elementId);
        return new Pikaday({
            field: field,
            format: 'YYYY-MM-DD',
            toString: formatDate,
            defaultDate: field.value === emptyDateValue ? null : defaultZeroDate
        });
    }

    const datepicker1 = initializeDatepicker('datepicker1');
    const datepicker2 = initializeDatepicker('datepicker2');
    const datepicker3 = initializeDatepicker('datepicker3');
    const datepicker4 = initializeDatepicker('datepicker4');
    const datepicker5 = initializeDatepicker('datepicker5');
    const datepicker6 = initializeDatepicker('datepicker6');
    const datepicker7 = initializeDatepicker('datepicker7');
});   

    /*function handleFileSelect(evt) {
      const file = evt.target.files[0];
      const reader = new FileReader();
  
      // Event listener for when the file is loaded
      reader.onload = function (e) {
          const data = e.target.result;
           // Parse the Excel data using SheetJS
          const workbook = XLSX.read(data, { type: 'biry' });
          const sheetName = workbook.SheetNames[0];
          const sheet = workbook.Sheets[sheetName];
          const jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });
  
          // Remove the first row (title row)
          jsonData.shift();
  
          // Call the function to insert the data into the database
          jsonData.forEach(function(row) {
              insertRowToDatabase(row);
          });
      };
  
      // Read the file as binary data
      reader.readAsBinaryString(file);
  }
  
    // Function to insert a row of data into the database
    function insertRowToDatabase(row) {
      // Call the stored procedure to insert the row into the database
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload.php'); // <-- Use the correct URL for your upload.php file
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.onload = function () {
        if (xhr.status === 200) {
          console.log('Data inserted successfully using stored procedure');
        } else {
          console.log('Error inserting data using stored procedure');
        }
      };
      xhr.send(JSON.stringify(row));
    }
  // Add event listener to the file input element
  document.getElementById('uploadButton').addEventListener('click', function () {
      // When the "Excel" button is clicked, trigger the click event of the file input
      document.getElementById('fileInput').click();
  });
  
  // Add event listener for file input element change
  document.getElementById('fileInput').addEventListener('change', handleFileSelect);*/


  
  
  