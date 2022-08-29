// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily =
  '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

$(document).ready(function () {
  $.post("utils/pichart.php", function (data) {
    var os_name = [];
    var clients_number = [];
    for (var i in data) {
      os_name.push(data[i].label);
      clients_number.push(data[i].data);
    }

    const chartData = {
      labels: os_name,
      data: clients_number,
    };

    createChart("myPieChart", chartData);
  });
});

/* Set up Chart.js Pie Chart */
function createChart(chartId, chartData) {
  /* Grab chart element by id */
  const chartElement = document.getElementById(chartId);

  /* Create chart */
  const myChart = new Chart(chartElement, {
    type: "pie",
    data: {
      labels: chartData.labels,
      datasets: [
        {
          data: chartData.data,
          fill: false,
        },
      ],
    },
    options: {
      plugins: {
        colorschemes: {
          scheme: "tableau.ClassicColorBlind10",
        },
      },
      responsive: true,
      legend: {
        display: false,
      },
      hover: {
        onHover: function (e) {
          var point = this.getElementAtEvent(e);
          e.target.style.cursor = point.length ? "pointer" : "default";
        },
      },
    },
  });

  return myChart;
}
