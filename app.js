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
    const datepicker1 = new Pikaday({
      field: document.getElementById('datepicker1'),
      format: 'YYYY-MM-DD',
      toString(date) {
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
      }
    });
  
    const datepicker2 = new Pikaday({
      field: document.getElementById('datepicker2'),
      format: 'YYYY-MM-DD',
      toString(date) {
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
      }
    }); 
    const datepicker3 = new Pikaday({
      field: document.getElementById('datepicker3'),
      format: 'YYYY-MM-DD',
      toString(date) {
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
      }
    });
});
  
  
  
  