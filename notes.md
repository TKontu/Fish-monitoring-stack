To do:
2. //Need to create "forgot password" functionality
3. //Need to update profile management page
        delete account
10. //Update password system to have user specific salt + hash
11. //Add functionality which removes unactivated accounts once a week.
13. //Ensure that the activation mail is not blocked by Gmail rules:
    https://support.google.com/mail/answer/81126?hl=en
15. // Set up HTTPS for the apache.
    https://stackoverflow.com/questions/5801425/enabling-ssl-with-xampp
    https://techexpert.tips/apache/enable-https-apache/
20. //Make it so that the register page has a field to activate an account. Then the emailed activation code is input to that field to activate an account.
21. //Make it so that only activated accounts can log in.
22. //Make trap box layout similar to Shuaijun's app prototype
24. //Update AWS account so that it can be transferred to Luke
25. //Make administration user interface
26. //Make trap emptied - time / timestamp functionality
27. //Create manual for the architecture
28. //TLS for communication - mqtt
29. //Add existing trap -functionality
30. //
    




Done/redundant:
1. //Need to create protection against multiple occurences of same email. - Not needed
3. //Need to create profile management page
        change password,
4. //Need to create basic view - Done
5. //Need to create "add fishtrap functionality - Done  
6. //Need to update the MySql query functionality to PDO - DONE
    (SQL injection resistant)
7. //Need to create specific SQL user for the functionality with specific rights
8. //Need to create User specific linking to fish trap data within SQL
9. //Create prototype query with Node-red to output HTTP POST input to site to visualize data - Not needed
12. //check if Amazon RDS with MySql would be better option than EC2 instance installed MySql
    Should be also free tier eligible.
14. // Study if MQTT in PHP is worth to implement on the server (YES) - Mosquitto running on AWS server:
    https://developer.ibm.com/technologies/messaging/articles/iot-mqtt-why-good-for-iot/
    https://github.com/php-mqtt/client
    http://www.steves-internet-guide.com/mqtt-username-password-example/
    https://mosquitto.org/man/mosquitto_pub-1.html
17. //Running mqtt to sql through node.js: (Script DONE, Running persistently)
    https://www.vultr.com/docs/how-to-setup-node-js-persistent-applications-on-ubuntu-16-04
    https://gist.github.com/smching/ff414e868e80a6ee2fbc8261f8aebb8f
18. //Tested that the AWS mqtt server responds to ESP32 publishes - DONE
19. //Timestamp to each fish. Not possible to differentiate, but action can be logged.
23. //Make the node.js script robust against wrong kind of messages.









