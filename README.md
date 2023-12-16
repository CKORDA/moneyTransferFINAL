# moneyTransferFINAL
Collaborators: Ramatoulaye Signate Davd-Michael Davies Cheyenne Korda

In order to access moneyTransfer database, username and password are both HARDCODED as admin to connect.
Admin is not an account in the moneyTransfer database, however must have access through SQL to read the database.


The following command may be necessary to grant access to moneyTransfer from the root database:
GRANT ALL PRIVILEGES ON moneyTransfer.* TO 'admin'@'localhost' IDENTIFIED BY 'your_password';
FLUSH PRIVILEGES;

Admin Credentials: username: kordac1 password: kordac1
User Credentials: username: chey password: chey

