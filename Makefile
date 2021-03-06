run:
	docker-compose	up  --build  -d
	docker  exec    finaltask_app_1 composer    require     laravelcollective/html
	docker	exec	finaltask_app_1	composer	install
	docker	exec	finaltask_app_1	php	artisan 	migrate
	docker	exec	finaltask_app_1	php	artisan 	db:seed
	docker	exec	finaltask_app_1	php	artisan 	config:cache
	docker	exec	finaltask_app_1	php	artisan 	config:clear
	docker  exec    finaltask_app_1 php artisan     key:generate
	docker	exec	finaltask_app_1	chmod 777 -R storage/
	docker	exec	finaltask_app_1	chmod 777 -R bootstrap/cache
