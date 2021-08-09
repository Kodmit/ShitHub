# DLY
Daily manager

## 1) Installation

### Pre-requisites

Before installing, you need to install :
- [docker](https://docs.docker.com/install/) and set [rights for non-root users](https://docs.docker.com/install/linux/linux-postinstall/) if you are on a linux platform.
- [docker-compose](https://docs.docker.com/compose/install/). This is shipped with Docker for mac. Therefore, you only need to install it on linux plateforms.

### Setup & run

1. Clone the repository
```shell script
$> git clone https://github.com/Kodmit/dly.git
```

2. Go to the root of the directory and init the project
```shell script
$> cd dly
$> make init
```

3. Then run the local environment
```shell script
$> make up
```

Type `make help` to get a list of available commands.

## 2) How to use

### Web paths

**Symfony App**: http://localhost:80  
**VueJS App**: http://localhost:3000
**S3 App**: http://localhost:7000

### Database
**PostgreSQL**  
HOST: localhost  
PORT: 5432   
DATABASE: dly  
USER: root  
PASSWORD: root