// Monthly Sale Chart
new Chart(document.getElementById('saleChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: Monthly_Sale,
            data: monthlyIncome,
            borderColor: '#4bc0c0',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: '#4bc0c0',
            pointBorderWidth: 2,
            lineTension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: Monthly_Sale,
                font: {
                    size: 17,
                    weight: 'bold',
                },
                color: themeColor == 'dark' ? '#fff' : '#333',
            },
            legend: {
                display: true,
                position: 'top',
                labels: {
                    font: {
                        size: 14,
                    },
                    color: themeColor == 'dark' ? '#fff' : '#333',
                }
            },
        },
        scales: {
            y: {
                min: minMonth,
                max: maxMonth + 1000,
                grid: {
                    color: '#e0e0e0',
                    lineWidth: 1,
                },
                ticks: {
                    beginAtZero: true,
                    stepSize: 1000,
                    font: {
                        size: 12,
                        weight: 'bold',
                        family: 'Arial',
                    },
                    color: themeColor == 'dark' ? '#fff' : '#333',
                }
            },
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: 'bold',
                    },
                    color: themeColor == 'dark' ? '#fff' : '#333',
                }
            }
        },
    }
});

// Total Orders Chart
new Chart(document.getElementById('userChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: Monthly_Orders,
            data: monthlyOrder,
            borderColor: '#ff9f40',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: '#ff9f40',
            pointBorderWidth: 2,
            lineTension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: Monthly_Orders,
                font: {
                    size: 17,
                    weight: 'bold',
                },
                color: themeColor == 'dark' ? '#fff' : '#333',
            },
            legend: {
                display: true,
                position: 'top',
                labels: {
                    font: {
                        size: 14,
                    },
                    color: themeColor == 'dark' ? '#fff' : '#333',
                }
            },
        },
        scales: {
            y: {
                min: minOrder,
                max: maxOrder + 10,
                grid: {
                    color: '#e0e0e0',
                    lineWidth: 1,
                },
                ticks: {
                    beginAtZero: true,
                    stepSize: 5,
                    font: {
                        size: 12,
                        weight: 'bold',
                        family: 'Arial',
                    },
                    color: themeColor == 'dark' ? '#fff' : '#333',
                }
            },
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: 'bold',
                    },
                    color: themeColor == 'dark' ? '#fff' : '#333',
                }
            }
        },
    }
});
