const PolygonLookup = require('polygon-lookup');
const geojsonData = require('./jakim.geojson.json');

function zoneLookup(lat, long) {
    const lookup = new PolygonLookup(geojsonData);
    const result = lookup.search(long, lat);

    if (!result || !result.properties || result.properties.jakim_code === undefined) {
        throw new Error(`No JAKIM code associated with the coordinate [lat: ${lat}, long: ${long}].`);
    }

    return {
        zone: result.properties.jakim_code,
        state: result.properties.state,
        district: result.properties.name
    };
}

module.exports = { zoneLookup };
