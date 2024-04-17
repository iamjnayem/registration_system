# Description
It is a registration system where you provide email and it sends email to the provided email addres through gmail api asynchronously with help of laravel queue. It caches the request data to redis and dispatches a queue
to process the data and store it in the postgresql and after inserting the data it dispatches another queue to send the actual email. For Google Api a project has created to google cloud console and enabled gmail api. Later
Google provided some credentials. With the help of this credentails for the first time I have generated a seeding token from redirected url from google. Later this token has used to reporduces refresh token and new token. 

# Tools
  1. php 8.2+
  2. postgresql 14.11
  3. redis 6.0.16
  4. laravel 11
  5. nginx

Note: The project is developed using service & repository pattern. All the necessary application log has managed so that if anything wrong happens, it can be debug easily. For production purpose the token & credentials must 
have to manage outside of the actual code. If you use windows as os, you should use git bash for running docker command.


# Instructions

1. `git clone https://github.com/iamjnayem/registration_system.git`

2. `cd registration_system`

3. `cd src`

4. `sudo chmod -R 777 .`
  
5. `cd ..`

6. `docker compose up --build -d`

7. `docker compose exec php composer install`

8. `docker compose exec -it php sh`

9. `cp .env.example .env`

10. `php artisan migrate:fresh`

11. `nohup php artisan queue:work --queue=process_registration_request > db.log & nohup php artisan queue:work --queue=send_mail_queue > mail.log`

12. [go to] (http://localhost:38000)
  
13. 13. api route: (http://localhost:38000/api/register) (method post)

14. payload
```
{
  "email": "iamj.nayem@gmail.com"
}
```


# Improvements
  1. The migration & queue running command can be made automatic within Dockerfile. 
  2. Unit test can be written.
     
