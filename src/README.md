# Description:

Sends email when a http post request is made to {{base_url}}/register route. It uses redis to cache the request data & sends immediate response. Late it uses queue asynchronously to fetch email from redis to database and then uses another queue to send email to given email. Later it updates the registration status.

# Requirements:

1. postgresql 14.11
2. redis 6.0.16
3. laravel 11
4. php 8.2+

# Instructions to run:

1. clone the repository `git clone https://github.com/iamjnayem/registration_system.git`
2. create database user as postgres
3. create database user password as lolipop1
4. create database as subscribe_system
5. copy .env.example to .env
6. run `composer install`
7. run `php artisan migrate`
8. run `php artisan serve`
9. run `php artisan queue:work --queue=process_registration_request`
10. run `php artisan queue:work --queue=send_mail_queue`

# Api End point:

http://localhost:8000/api/register [post] <br>
payload: {"email": "exmaple@gmail.com"}

