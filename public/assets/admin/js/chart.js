// Monthly Sale Chart (Bar)
new Chart(document.getElementById('saleChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: Monthly_Sale,
            data: monthlyIncome,
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
            borderColor: '#4bc0c0',
            borderWidth: 1,
            borderRadius: 6,
            barThickness: 30
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
                },
                ticks: {
                    beginAtZero: true,
                    stepSize: 1000,
                    font: {
                        size: 12,
                        weight: 'bold',
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


// Total Orders Chart (Bar)
new Chart(document.getElementById('userChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: Monthly_Orders,
            data: monthlyOrder,
            backgroundColor: 'rgba(255, 159, 64, 0.7)',
            borderColor: '#ff9f40',
            borderWidth: 1,
            borderRadius: 6,
            barThickness: 30
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
                },
                ticks: {
                    beginAtZero: true,
                    stepSize: 5,
                    font: {
                        size: 12,
                        weight: 'bold',
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
