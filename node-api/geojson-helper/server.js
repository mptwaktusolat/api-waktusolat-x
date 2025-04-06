import express from 'express';
import { zoneLookup } from './zone_lookup.js';

const app = express();
const port = 5166;

// jakim.geojson is a little large.
app.use(express.json({ limit: '5mb' }));

app.get('/location/:lat/:long', (req, res) => {
    const { lat, long } = req.params;

    try {
        const result = zoneLookup(lat, long);
        res.status(200).json(result);
    } catch (error) {
        res.status(404).json({
            error: error.message || 'An error occurred',
        });
    }
});

app.listen(port, () => {
    console.log(`Geojon helper listening on port ${port}`);
});
