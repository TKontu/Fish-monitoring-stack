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
  console.log("Connected!");
  var sql = "INSERT INTO oeeyxy (length) VALUES ('31')";
  con.query(sql, function (err, result) {
    if (err) throw err;
    console.log("1 record inserted");
  });
});
