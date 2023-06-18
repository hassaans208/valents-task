<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

Please Read This
Postman (Documentation): https://documenter.getpostman.com/view/22741474/2s93si2Asg#5ffba680-e62b-41b3-8efd-7b1049d3e94f

<ul>
<li>
Created 4 APIs, to list, create, update & delete the user
</li>
<li>
Route for list is unprotected while other routes are protected
</li>
<li>
Cors are Enabled
</li>
<li>
Rate limiting is implemented to 20 requests/minute
</li>
<li>
Tested APIs Postman
</li>
<li>
Json zip folder along with Environment
</li>
<li>
Zip folder of APIs is attached in the email
</li>
<li>
Implemented and tested authentication
</li>
<li>
Tested 
</li>
</ul>

![Image of testing CORS Enabled and Rate limiting on a local react project in which I sent 100 requests using setTimeOut, it didn't gave any CORS error and also gave and error after 20 reqyests, it can be seen in the next Image](public/images/valents1.png)


![](public/images/valents1.png)

A user with the name is already created with credentials:

email: admin@gmail.com
password: password

## Installation

<hr />

Run command `composer install`

then `php artisan migrate:fresh --seed`

Copy env.example as env `cp .env.example .env`

Generate a key for the project `php artisan key:generate`

then `php artisan passport:install`

Put the obtained values with the respective key given below

<code>
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=${clientID}
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=${clientSecretKey}
</code>

Hope the project is fine, Thanks for the opportunity!
