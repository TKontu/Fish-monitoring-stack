var mysql = require('mysql');
var CONFIG = require('./config.json');

'use strict';

var length = process.argv[0];

// This needs different approach
var con = mysql.createConnection({
  host: CONFIG.dbhost,
  user: CONFIG.dbuser,
  password: CONFIG.dbpassword,
  database: CONFIG.database

});

con.connect(function(err) {
  if (err) throw err;
  var length = process.argv[2];
  console.log("Connected!");
  var sql = "INSERT INTO oeeyxy (length) VALUES ('"+length+"')";
  console.log("INSERT INTO oeeyxy (length) VALUES ('"+length+"')");
  con.query(sql, function (err, result) {
    if (err) throw err;
    console.log("1 record inserted");
  });
  console.log("End of script");
  //process.exit();
  //console.clear();
});

