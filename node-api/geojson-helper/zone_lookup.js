// zone_lookup.js
import PolygonLookup from 'polygon-lookup';
import fs from 'fs';
import path from 'path';

const geojsonPath = path.resolve('./jakim.geojson');
const geojsonRaw = fs.readFileSync(geojsonPath, 'utf-8');
const geojsonData = JSON.parse(geojsonRaw);

export function zoneLookup(lat, long) {
    const lookup = new PolygonLookup(geojsonData);
    const result = lookup.search(long, lat);

    if (!result || !result.properties || result.properties.jakim_code === undefined) {
        throw new Error(`No JAKIM code associated with this coordinate.`);
    }

    return {
        zone: result.properties.jakim_code,
        state: result.properties.state,
        district: result.properties.name
    };
}
