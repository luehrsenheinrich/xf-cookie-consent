'use strict';
const fs = require('fs');
const {exec} = require('child_process');

const args = process.argv.slice(2);
const semVer = args[0];

const rawdata = fs.readFileSync('./build/CookieConsent/addon.json');
const addon = JSON.parse(rawdata);

const versionId = addon.version_id + 1;

exec('npm run cmd xf-addon:bump-version LH/CookieConsent --version-id ' + versionId + ' --version-string ' + semVer);
