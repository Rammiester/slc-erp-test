 // JavaScript function to fetch class times based on the selected date
 function fetchClassTimes() {
    // Get the selected date from the input field
    var selectedDate = document.getElementById("selected_date").value;

    // Perform an AJAX request to fetch the class times for the selected date
    // You can use Axios, jQuery.ajax, or any other method for making AJAX requests
    // Example using Axios:
    axios.get('/fetch-class-times', {
        params: {
            selected_date: selectedDate
        }
    })
    .then(function (response) {
        // Update the dropdown list with the fetched class times
        var classTimeSelect = document.getElementById("class_time");
        console.log(selectedDate.getText);
        classTimeSelect.innerHTML = "";
        response.data.forEach(function (classTime) {
            var option = document.createElement("option");
            option.text = classTime;
            classTimeSelect.add(option);
        });
    })
    .catch(function (error) {
        console.error(error);
    });
}