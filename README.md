# User-Import
Imports a CSV file with data into a MySQL database.

## Prerequisites

Download and Install [Docker Desktop](https://www.docker.com/products/docker-desktop/) | [Mac](https://docs.docker.com/desktop/install/mac-install/) / [Windows](https://docs.docker.com/desktop/install/windows-install/) / [Linux](https://docs.docker.com/desktop/install/linux-install/) 4.25.0 or latest 

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

![Screenshot 2024-03-15 at 4.05.32 pm.png](..%2F..%2Fscreenshots%2FScreenshot%202024-03-15%20at%204.05.32%E2%80%AFpm.png)

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
![Screenshot 2024-03-15 at 4.19.31 pm.png](..%2F..%2Fscreenshots%2FScreenshot%202024-03-15%20at%204.19.31%E2%80%AFpm.png)