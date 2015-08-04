
freelancer-office-v.1.7.1

The details below can be used to login to the demo:
Demo Link: Online Demo Here
To login as Admin:
Username: admin
Password: admin
To login as Staff:
Username: gitbench
Password: gitbench
To login as Client:
Username: client
Password: client


You may use this method if the Quick Installation fails or you get an error. So here's what you have to do:

Download a copy of Freelancer Office from Envato Market.
Extract the file FreelancerOffice-[VERSION].zip to htdocs folder in XAMPP or www folder in WAMP or Upload it as ZIP file if installing to your Online Server using FTP and remember to Extract it to a folder on your Live Server.
Open your favourite MySQL Administration tool e.g PHPMyAdmin and create a database Example: FreelanceOffice
Select your database and click on Import : freelanceoffice-v1.7.1.sql
Click Browse and select InitialDB.sql file from the install folder and hit the Go button. Your Database is now setup so we'll need to make a few changes to the database config values.
Open fx_config table and change the base_url value to your domain e.g http://example.com/FOLDERNAME/ Remember the forward slash in URL.If you wish you can edit other config values directly but you can edit them after installing Freelancer Office.
Now open application/config/database.php and enter your database settings as follows:
$db['default']['hostname'] = 'YOUR DATABASE HOST';
$db['default']['username'] = 'YOUR DATABASE USERNAME';
$db['default']['password'] = 'YOUR DATABASE PASSWORD';
$db['default']['database'] = 'YOUR DATABASE NAME';
Remember to delete the install folder or rename it if using this method.
Open your favorite browser and type in the address bar http://your-domain.com/FOLDERNAME/