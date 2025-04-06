![NodeJS](https://img.shields.io/badge/node.js-6DA55F?style=for-the-badge&logo=node.js&logoColor=white)
![Express.js](https://img.shields.io/badge/express.js-%23404d59.svg?style=for-the-badge&logo=express&logoColor=%2361DAFB)

# GeoJson Helper

Helper server to get JAKIM zones from GPS coordinates.

## Get Started

```shell
cd node-api/geojson-helper
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
