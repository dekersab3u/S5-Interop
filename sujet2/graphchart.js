//import {Chart} from "chart.js";

export function createCovidGraph(data) {

    const labels = data.map(entry => entry.date); // Dates (e.g., 2022-S30)
    const values = data.map(entry => entry.value); // Contamination levels


    const ctx = document.getElementById('epidemics').querySelector('canvas');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Niveau de contamination (Maxéville)',
                data: values,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 1,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Semaine',
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Contamination SARS-CoV-2',
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            },
        }
    });
}
