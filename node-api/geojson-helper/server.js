const express = require('express');
const { zoneLookup } = require('./zone_lookup.js');

const app = express();
const port = 5166;

app.use(express.json());

app.get('/location/:lat/:long', (req, res) => {
    const { lat, long } = req.params;

    try {
        const result = zoneLookup(lat, long);
        res.status(200).json(result);
    } catch (error) {
        res.status(404).json({
            error: error.message || 'Unable to find the zone for the provided latitude and longitude. Please check the input values and try again.',
        });
    }
});

app.get('/up', (req, res) => {
    res.status(200).json({
        status: 'ok',
        message: 'Service is running'
    });
});

app.listen(port, () => {
    console.log(`GeoJson helper listening on port ${port}`);
});
