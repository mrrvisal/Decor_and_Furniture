# Render deployment (PHP)

This project uses the front controller `public/index.php`.

## 1) Deploy settings

- Build command: `composer install --no-dev --prefer-dist`
- Start command: `php -S 0.0.0.0:$PORT -t public public/index.php`

A sample `render.yaml` is included in the repo.

## 2) Required environment variables

### Database (MySQL)

- `DB_HOST` (e.g. `mysql.render.com`)
- `DB_NAME` (e.g. `decor_furniture`)
- `DB_USER`
- `DB_PASS`

### SMTP (for emails via PHPMailer)

- `SMTP_HOST`
- `SMTP_PORT` (e.g. `587`)
- `SMTP_USERNAME`
- `SMTP_PASSWORD`
- `SMTP_SECURE` (e.g. `tls` or `ssl`)
- `MAIL_FROM_EMAIL`
- `MAIL_FROM_NAME`

## 3) Notes

- `public/.htaccess` is mainly for Apache environments; Render here runs the PHP built-in server.
