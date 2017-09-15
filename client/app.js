// app.js


$("#selectCountry").change(function(e) {
  $("#output").html("Loading...");
  var country = $("#selectCountry").val();
  var calendarUrl = 'https://www.googleapis.com/calendar/v3/calendars/en.' + country
                  + '%23holiday%40group.v.calendar.google.com/events?key=<yourAPIKey>';


  $.getJSON(calendarUrl)
    .success(function(data) {
    	console.log(data);
      $("#output").empty();
      for (item in data.items) {
        $("#output").append(
          "<hr><h3>" + data.items[item].summary + "<h3>" +
          "<h4>" + data.items[item].start.date + "<h4>"
        );
      }
    })
    .error(function(error) {
      $("#output").html("An error occurred.");
    })
});
$("#selectCountry").trigger("change");
