const fs = require('fs');
const path = require('path');

const target = path.join(__dirname, '..', 'node_modules', 'progress-webpack-plugin', 'index.js');
if (!fs.existsSync(target)) {
  console.error('progress-webpack-plugin not found at', target);
  process.exit(1);
}

let src = fs.readFileSync(target, 'utf8');

const oldBlockRegex = /return new Progress\([\s\S]*?\)\s*$/m;
if (!oldBlockRegex.test(src)) {
  console.log('No matching options block found in progress-webpack-plugin; no changes applied.');
  process.exit(0);
}

const replacement = `return new Progress({ handler })`;

src = src.replace(oldBlockRegex, replacement + '\n');
fs.writeFileSync(target, src, 'utf8');
console.log('Patched progress-webpack-plugin to use handler-only ProgressPlugin options.');
