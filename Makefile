help:
	@echo "Available commands:"
	@echo "  setup  : Setup Laravel API with fresh install"
	@echo "  help   : Display available commands"

setup:
	# Install Composer dependencies
	docker-compose run --rm --entrypoint composer api install

	# Set up .env file if missing
	docker-compose run --rm --entrypoint php api -r "file_exists('.env') || copy('.env.example', '.env');"

	# Set up .env.tesing file if missing
	docker-compose run --rm --entrypoint php api -r "file_exists('.env.testing') || copy('.env.testing.example', '.env.testing');"
	
	# Generate application key
	docker-compose run --rm api key:generate

	# Generate application key for phpunit env
	docker-compose run --rm api key:generate --env=testing
	
	# Run database migrations
	docker-compose run --rm api migrate:fresh

	# Passport installation for OAuth2
	docker-compose run --rm api passport:install

	# Seed the database with sample data
	docker-compose run --rm api db:seed

	# Create passport client and .env file for use with nuxt
	@docker-compose run --rm --entrypoint /bin/sh api -c \
		"php artisan passport:client \
		--redirect_uri='http://localhost:3000/api/auth/callback' \
		--name='YumeFood Tech Test' \
		--no-interaction \
		| sed -n -e 's/^Client ID: \(.*\)/CLIENT_ID=\1/p' -e 's/^Client secret: \(.*\)/CLIENT_SECRET=\1/p'" > .env