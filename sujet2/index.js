import {fetchCovidData} from "./sars-cov2";
import {createCovidGraph} from "./graphchart";

const maxevilleData = await fetchCovidData();

const epidemicsDiv = document.getElementById('epidemics');
const canvas = document.createElement('canvas');
canvas.id = 'covidChart';
epidemicsDiv.appendChild(canvas);

createCovidGraph(maxevilleData);