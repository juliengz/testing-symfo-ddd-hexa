
# Install symfony in api folder
# For a fresh install empty api folder and run command again
docker-compose run --rm --no-deps api composer create-project symfony/skeleton .

# Create the database
docker-compose run --rm --no-deps api bin/console doc:data:create --if-not-exists


# test

## php unit
- Namespace is different from classic installation -> configure autoload in compser.json
- kernel.pgp is in a directory different from the default directory -> configure phpunit.xml

## Create the database
docker-compose run --rm --no-deps api bin/console --env=test doctrine:database:create
docker-compose run --rm --no-deps api bin/console --env=test doctrine:schema:create


