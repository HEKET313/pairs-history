# Build

```
docker-compose build
```

# Start

```
cd docker && docker-compose up -d
```

# Documentation

Go to http://0.0.0.0:81/api/doc

# Import

Go to the PHP container

```
docker exec -ti <container_id> /bin/bash
```

Execute import command

```
php bin/console pairs:import
```
