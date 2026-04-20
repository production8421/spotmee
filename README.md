# Spotmee

Laravel web application for gym discovery, host listings, admin management, and public gym booking (including Stripe payment flows and optional personal training add-ons).

## Requirements

- **PHP** 8.3+ with common extensions (`openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, etc.)
- **Composer** 2.x
- **Node.js** 20+ and **npm** (for Vite / React booking UI)
- **MySQL** (or another database supported by Laravel; `.env.example` defaults to MySQL)

## Getting started (local)

1. **Clone the repository** (after you initialize Git and add your remote):

   ```bash
   git clone <your-repository-url> spotmee
   cd spotmee
   ```

2. **Environment file**

   ```bash
   copy .env.example .env   # Windows
   # cp .env.example .env # macOS / Linux
   ```

   Edit `.env` and set at least `APP_URL`, database credentials (`DB_*`), and any Stripe or mail keys your features need.

3. **Install PHP dependencies**

   ```bash
   composer install
   ```

4. **Application key**

   ```bash
   php artisan key:generate
   ```

5. **Database**

   ```bash
   php artisan migrate
   ```

   Optionally run seeders if your project defines them:

   ```bash
   php artisan db:seed
   ```

6. **Storage link** (if you serve user uploads from `storage/app/public`)

   ```bash
   php artisan storage:link
   ```

7. **Install Node dependencies and build front-end assets**

   ```bash
   npm install
   npm run build
   ```

8. **Run the app**

   ```bash
   php artisan serve
   ```

   Visit the URL shown in the terminal (typically `http://127.0.0.1:8000`).

### Front-end development (Vite)

For hot reload while editing JS/CSS/React:

```bash
npm run dev
```

Run `php artisan serve` in another terminal. Ensure `APP_URL` in `.env` matches how you open the site so Vite asset URLs resolve correctly.

## Git

Suggested first-time setup in an empty or new remote:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin <your-repository-url>
git push -u origin main
```

Secrets (`.env`), dependencies (`vendor/`, `node_modules/`), and compiled assets (`public/build/`) are listed in `.gitignore` and should not be committed.

## Production notes

- Set `APP_ENV=production`, `APP_DEBUG=false`, and a strong `APP_KEY`.
- Run `composer install --no-dev --optimize-autoloader`, `npm ci`, and `npm run build`.
- Configure your web server document root to the `public/` directory.
- Use a process manager or platform queues for `php artisan queue:work` if you rely on queued jobs.

## License

Unless otherwise specified, project licensing follows the terms in `composer.json` / repository policy.
