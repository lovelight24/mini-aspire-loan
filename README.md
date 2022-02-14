Steps:
1. Run composer update
2. Execute below query in mysql for create new schema:
CREATE SCHEMA `aspire-loan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
3. Update DB details in .env file. (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
4. Run php artisan migrate
5. Run php artisan db:seed
6. Run php artisan passport:install
7. Run php artisan serve and open postman Application After import API collection shared with you.
8. Run Borrower Login API and get token, userid, type
9. run loan request api: update token and submit
10. run Admin Login API and get token, userid, type
11. update token in loan request approval by admin and submit api.
12. run borrower login and get token
13. update token in loan Repayment API and run.
