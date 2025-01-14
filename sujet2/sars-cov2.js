export async function fetchCovidData() {
    const apiUrl = 'https://tabular-api.data.gouv.fr/api/resources/2963ccb5-344d-4978-bdd3-08aaf9efe514/data/?page=1&page_size=50';
    try {
        const apiResponse = await fetch(apiUrl);
        const jsonData = await apiResponse.json();
        const filteredData = jsonData.data
            .filter(record => record.MAXEVILLE !== null)
            .map(record => ({
                date: record.semaine,
                value: record.MAXEVILLE
            }));
        return filteredData;
    } catch (fetchError) {
        console.error('An error occurred while fetching COVID data:', fetchError);
        return [];
    }
}
