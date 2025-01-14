export async function fetchCovidData() {
    const apiUrl = 'https://tabular-api.data.gouv.fr/api/resources/...key.../data/?page=1&page_size=50';
    try {
        const apiResponse = await fetch(apiUrl);
        const jsonData = await apiResponse.json();
        const filteredData = jsonData.data
            .filter(record => record.maxeville !== null)
            .map(record => ({
                date: record.semaine,
                value: record.maxeville
            }));
        return filteredData;
    } catch (fetchError) {
        console.error('An error occurred while fetching COVID data:', fetchError);
        return [];
    }
}
