const fs = require('fs');
const path = require('path');
const Turn = require('node-turn');

const configPath = path.join(__dirname, 'turn-config.json');
let config = {
  listeningPort: parseInt(process.env.REUNION_TURN_PORT || '3478', 10),
  listeningIps: [ '0.0.0.0' ],
  relayIps: [ '127.0.0.1' ],
  authMech: 'long-term',
  credentials: {
    reunion: 'reunion123'
  },
  realm: 'chat-mfpra.local',
  debugLevel: 'INFO'
};

if (fs.existsSync(configPath)) {
  try {
    const fileConfig = JSON.parse(fs.readFileSync(configPath, 'utf8'));
    config = Object.assign({}, config, fileConfig || {});
  } catch (error) {
    console.error('Invalid TURN config file:', error.message);
  }
}

if (process.env.REUNION_TURN_PORT) {
  config.listeningPort = parseInt(process.env.REUNION_TURN_PORT, 10);
}

const server = new Turn(config);
server.start();

console.log('Reunion TURN server listening on port ' + config.listeningPort);
