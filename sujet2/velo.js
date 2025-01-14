async function fetchBikeStations() {
    try {
        const apiUrl = 'https://api.cyclocity.fr/contracts/nancy/gbfs/station_information.json';
        const response = await fetch(apiUrl);
        const jsonData = await response.json();
        return jsonData.data.stations;
    } catch (err) {
        console.error('Erreur lors de la récupération des données des stations :', err);
    }
}

export async function initializeMap(lat, lon) {
    const stationDetails = await fetchBikeStations();
    const map = L.map('bikes').setView([lat, lon], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    stationDetails.forEach((station) => {
        L.marker([station.lat, station.lon]).addTo(map)
            .bindPopup(`
                <h4>${station.name}</h4>
                <p>${station.address}</p>
            `);
    });
}