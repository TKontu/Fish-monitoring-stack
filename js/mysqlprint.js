var mysql = require('mysql');
var CONFIG = require('./config.json');

// This needs different approach
var con = mysql.createConnection({
  host: CONFIG.dbhost,
  user: CONFIG.dbuser,
  password: CONFIG.dbpassword,
  database: CONFIG.database
});

con.connect(function(err) {
  if (err) throw err;
  con.query("SELECT * FROM oeeyxy", function (err, result, fields) {
    if (err) throw err;
    console.log(result);
  });
});
