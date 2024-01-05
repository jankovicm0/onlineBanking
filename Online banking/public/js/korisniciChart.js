document.addEventListener('DOMContentLoaded', function () {
    fetch('../../core/ucitavanjeKorisnika.php')
        .then(response => response.json())
        .then(dataFromServer => {
            var chartData = {
                labels: dataFromServer.labels,
                datasets: dataFromServer.datasets
            };

            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            myChart.canvas.parentNode.style.width = '80%';
            myChart.canvas.parentNode.style.margin = 'auto';
        })
        .catch(error => console.error('Greška pri dobavljanju podataka:', error));
});