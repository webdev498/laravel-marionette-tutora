var zombie = require('zombie');

zombie.localhost('tutora.testing', 1337);

module.exports = new zombie({
    debug : true,
    waitDuration : 30000
});
