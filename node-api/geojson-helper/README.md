# GeoJson Helper

Helper server to get JAKIM zones from GPS coordinates.

## Get Started

```shell
node server.js
```

## Usage

GET `/location/{latitude}/{longitude}`

Example:

```shell
curl http://localhost:5166/location/3.06147/101.63153
```

## Resources

The `jakim.geojson` file can be obtained from https://github.com/mptwaktusolat/jakim.geojson/blob/master/malaysia.district-jakim.geojson. Please keep this file updated.
