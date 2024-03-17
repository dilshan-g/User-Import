# User-Import
This script Imports a CSV file with data into a MySQL database.

## Prerequisites

Download and Install [Docker Desktop](https://www.docker.com/products/docker-desktop/) | [Mac](https://docs.docker.com/desktop/install/mac-install/) / [Windows](https://docs.docker.com/desktop/install/windows-install/) / [Linux](https://docs.docker.com/desktop/install/linux-install/) 4.25.0 or latest 

## Features
* OS independent Docker project
* Better validation and exception handling
* All the database operations in one Database class
* All the helper functions in one Helper class
* User friendly CLI messages

## Setup the local development environment

1. Clone the repo into your local

```
git clone https://github.com/dilshan-g/User-Import.git user-import
cd user-import
```

2. Inside the project root, execute the docker-compose command to keep the project running in the background

```
docker-compose up -d
```

***Note: This command will perform the following actions***
 - Create containers for PHP and MySQL to run application locally
 - Create MariaDB database named `users` with a user named `user`

![Screenshot 2024-03-15 at 4.05.32 pm.png](screenshots%2FScreenshot%202024-03-15%20at%204.05.32%E2%80%AFpm.png)

### Access the containers via the shell

#### Execute the following command in the project root to SSH into the PHP container

```
docker-compose exec -it php sh
```

#### Execute the following command in the project root to SSH into the Database container

```
docker-compose exec -it mysql sh
```

##### Execute this command inside the mysql container to access your mariadb database

```
mariadb -h mysql -u user -ppass
```
![Screenshot 2024-03-15 at 4.19.31 pm.png](screenshots%2FScreenshot%202024-03-15%20at%204.19.31%E2%80%AFpm.png)

## Running the script locally

In order to run the script you need to SSH into the PHP container. (Please enter `docker-compose exec -it php sh`)

### List of command variations and expected outcome.

1) `php user_upload.php`

![Screenshot 2024-03-17 at 10.51.05 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.51.05%E2%80%AFpm.png)

2) `php user_upload.php --help`

![Screenshot 2024-03-17 at 10.51.40 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.51.40%E2%80%AFpm.png)

3) `php user_upload.php --file=users.csv  -uuser -ppass -hmysql` : No table name provided

![Screenshot 2024-03-17 at 10.59.59 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.59.59%E2%80%AFpm.png)

4) `php user_upload.php --file=userswrong.csv --create_table=users  -uuser -ppass -hmysql` : Incorrect CSV name given

![Screenshot 2024-03-17 at 10.58.23 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.58.23%E2%80%AFpm.png)

5) `php user_upload.php --file=users.csv --create_table=users -uuser -ppass -hmysql` : Aborted

![Screenshot 2024-03-17 at 10.52.49 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.52.49%E2%80%AFpm.png)

6) `php user_upload.php --file=userss.csv --create_table=users -uuser -ppass -hmysql --dry-run` : --dry-run to see the steps.

![Screenshot 2024-03-17 at 10.53.35 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.53.35%E2%80%AFpm.png)

7) `php user_upload.php --file=users.csv --create_table=users -uuser -ppass -hmysql` : Continue

![Screenshot 2024-03-17 at 10.54.01 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.54.01%E2%80%AFpm.png)

8) `php user_upload.php --file=userss.csv --create_table=users -uuser -ppass -hmysql --dry-run` : Rerun --dry-run

![Screenshot 2024-03-17 at 10.54.24 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.54.24%E2%80%AFpm.png)

9) `php user_upload.php --file=users.csv --create_table=users -uuser -ppass -hmysql` : Rerun the complete command

![Screenshot 2024-03-17 at 10.54.55 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.54.55%E2%80%AFpm.png)

10) `php user_upload.php --file=users.csv --create_table=users -uwrong -ppass -hwrong` : Incorrect MySQL credentials given

![Screenshot 2024-03-17 at 10.56.05 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.56.05%E2%80%AFpm.png)

![Screenshot 2024-03-17 at 10.57.26 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2010.57.26%E2%80%AFpm.png)

11) `php user_upload.php --file=users.csv --create_table=usersnew -uuser -ppass -hmysql` : A new table name given

![Screenshot 2024-03-17 at 11.36.58 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2011.36.58%E2%80%AFpm.png)


## Expected end result in the DB table

![Screenshot 2024-03-17 at 11.51.57 pm.png](screenshots%2FScreenshot%202024-03-17%20at%2011.51.57%E2%80%AFpm.png)

## Extending and future modifications

1) Install and configure ClamAV antivirus protection for better security since the user inputs a CSV
2) Break the `user_upload.php` script into small helper functions and keep the script as lean as possible.

## Debugging and Development

Xdebug used for the local debugging during the development along with PHPStorm.  
Git used as the version control. Due to the size of this task `git-flow` not initiated and changes committed to the `main` branch directly.

________________________________________________________________________________________________________________________

# FooBar Script
This very simple PHP script prints 1 to 100 numbers with numbers divisible by 3 saying `foo`, number divisible by 5 saying `bar` and both saying `foobar`.

## Execution

### SSH into the PHP container

```
docker-compose exec -it php sh
```

### Execute the below command

```
php Foobar/foobar.php
```