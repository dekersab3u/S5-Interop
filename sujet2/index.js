import {fetchCovidData} from "./sars-cov2.js";
import {createCovidGraph} from "./graphchart.js";
import { initializeMap } from './velo.js'; // Assurez-vous que le chemin est correct

document.addEventListener('DOMContentLoaded', async () => {
    async function fetchIp() {
        try {
            const response = await fetch('https://ipapi.co/json/');
            return await response.json();
        } catch (error) {
            console.error(error);
        }
    }

        let ipInfo = await fetchIp();
        let lat;
        let long;
        if (!ipInfo) {
            lat = 48.6602783203125;
            long = 6.164169788360596;
            console.error('Impossible de récupérer les informations de localisation');
        } else {
            lat = ipInfo.latitude;
            long = ipInfo.longitude;
        }
    await initializeMap(lat, long);
});



const maxevilleData = await fetchCovidData();

const epidemicsDiv = document.getElementById('epidemics');
const canvas = document.createElement('canvas');
canvas.id = 'covidChart';
epidemicsDiv.appendChild(canvas);

createCovidGraph(maxevilleData);