// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily =
  '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

$(document).ready(function () {
  $.post("utils/barchart.php", function (data) {
    var install_date = [];
    var numbers_of_clients = [];

    for (var i in data) {
      install_date.push(data[i].label);
      numbers_of_clients.push(data[i].data);
    }

    // Bar Chart Example
    var ctx = document.getElementById("myBarChart");
    var myLineChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: install_date,
        datasets: [
          {
            label: "Clients",
            backgroundColor: "rgba(2,117,216,1)",
            borderColor: "rgba(2,117,216,1)",
            data: numbers_of_clients,
          },
        ],
      },
      options: {
        scales: {
          xAxes: [
            {
              time: {
                unit: "year",
              },
              gridLines: {
                display: true,
              },
              ticks: {
                maxTicksLimit: 10,
              },
            },
          ],
          yAxes: [
            {
              ticks: {
                min: 0,
                max: 16,
                maxTicksLimit: 32,
              },
              gridLines: {
                display: true,
              },
            },
          ],
        },
        legend: {
          display: false,
        },
      },
    });
  });
});
