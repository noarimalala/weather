require([
    "jquery"
], function($){
    $('#search-btn').click(function () {
        var cityValue    = $('input[name="city"]').val();
        var url          = jsConfigData.url;
        const table      = $('table'),
            cityInput    = $('input[name="city"]');

        if(cityValue !== "") {

            cityInput.removeClass("mage-error");
            $('#city-error').remove();

            $.ajax({
                url: url,
                method: "GET",
                data: {city: cityValue},
                global: false,
                contentType: "application/json; charset=utf-8",
                success: function (data) {

                    $('#data-weather-tomorrow').remove();
                    $('#data-weather-nextTomorrow').remove();
                    $('#info').remove();

                    var response = JSON.parse(data),
                        info = $("<div id='info'>"),
                        now = "En ce moment à " + response.now.name + ", il fait  " + response.now.temperature + "°C, avec des conditions météo : " + response.now.condition,
                        weatherOthersDays = "<tr id='data-weather-tomorrow'><td>Demain</td>\n" +
                            "<td>" + response.tomorrow.temperature + " °C</td>\n" +
                            "<td>" + response.tomorrow.condition + "</td>\n" +
                            "<td>" + response.tomorrow.wind_kph + " km/h - " + response.tomorrow.wind_dir + "</td></tr>" +
                            "<tr id='data-weather-nextTomorrow'><td>J+2</td>\n" +
                            "<td>" + response.nextTomorrow.temperature + " °C</td>\n" +
                            "<td>" + response.nextTomorrow.condition + "</td>\n" +
                            "<td>" + response.nextTomorrow.wind_kph + " km/h - " + response.nextTomorrow.wind_dir + "</td></tr>";

                    info.html(now + '<br/>');

                    table.before(info);

                    table.append(weatherOthersDays);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
        else{
            cityInput.addClass("mage-error");
            cityInput.after('<div for="city" generated="true" class="mage-error" id="city-error">This is a required field.</div>');
        }
    });
});
