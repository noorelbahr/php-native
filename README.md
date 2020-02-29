# Building PHP Project with OOP #

## Getting Started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Set up Project

Open terminal and move to the project root directory
```
cd ~/full/path/to/php-native
```

Here we assume that we are using `XAMPP` and our `Apache` and `MySQL` server are running.

---

Open `config/app.php` file and edit as below:
```
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'native');


define('SB_FLIP_URL', 'https://nextar.flip.id');
define('SB_FLIP_SECRET_KEY', 'HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41');
```
Don't forget to create new database named `native`, we will use it as our database in this project as mentioned above.

Run command below to make migration tables and seed default data :
```
php init.php
```

##### Run Our Project
To run the project, move the project directory (`php-native`) to your local `xampp/htdocs` folder.

So we can access our project with url http://localhost/php-native


## Testing Our API
To test our API, click button bellow : 

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/eac7899958b631025060)

##### or
visit Postman Documenter Link below :

https://documenter.getpostman.com/view/6993569/SzKZsGFV

Then click `Run in Postman` button on top right and Open with `Postman for Mac/Windows`

You can test every single endpoint in the postman collection

---

