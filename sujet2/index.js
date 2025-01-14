import {fetchCovidData} from "./sars-cov2.js";
import {createCovidGraph} from "./graphchart.js";

const maxevilleData = await fetchCovidData();

const epidemicsDiv = document.getElementById('epidemics');
const canvas = document.createElement('canvas');
canvas.id = 'covidChart';
epidemicsDiv.appendChild(canvas);

createCovidGraph(maxevilleData);