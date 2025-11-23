# Amin Talent Solutions — Frontend + Backend

Amin Talent Solutions is a talent marketplace connecting freelancers and companies. This repository contains a Laravel API backend and a Vue.js frontend.

Repository layout
- `backend/` — Laravel API
- `frontend/` — Vue.js frontend
- `README.md` — this file
- `.gitignore`

Prerequisites
- Node.js 16+ (LTS recommended) and npm
- PHP 8.0+ and Composer
- A database (MySQL, MariaDB, PostgreSQL) for Laravel
- Git
- (Optional) GitHub CLI (`gh`) if you want to create remotes from the terminal

Quick start — Backend (Laravel)
1. Open PowerShell in the `backend` folder:
```powershell
cd "c:\Users\mahmoud\Documents\GitHub\Amin Talent Solutions Website\backend"
```
2. Install PHP dependencies:
```powershell
composer install
```
3. Copy and edit env file:
```powershell
copy .env.example .env
# Edit .env and set DB_DATABASE, DB_USERNAME, DB_PASSWORD, and APP_URL
```
4. Generate application key:
```powershell
php artisan key:generate
```
5. Run migrations and seeders:
```powershell
php artisan migrate --seed
# or to run the specific seeder:
php artisan db:seed --class=AdminUserSeeder
```
6. Start the backend server:
```powershell
php artisan serve --port=8000
```

Quick start — Frontend (Vue)
1. Open PowerShell in the `frontend` folder:
```powershell
cd "c:\Users\mahmoud\Documents\GitHub\Amin Talent Solutions Website\frontend"
```
2. Install Node dependencies:
```powershell
npm install
```
3. Run the dev server:
```powershell
npm run serve
# Opens at http://localhost:8080 by default
```
4. Build for production:
```powershell
npm run build
```

Seeded test users
The `AdminUserSeeder` creates a set of test users (password: `password`):
- `admin@example.com` / `password` (admin)
- `ceo@example.com` / `password` (ceo)
- `pm@example.com` / `password` (project_manager)
- `company@example.com` / `password` (company)
- `freelancer@example.com` / `password` (freelancer)

Auth and API
- The frontend expects the API at `http://localhost:8000` by default. If your backend runs elsewhere, update `frontend/src/services/api.js` baseURL accordingly.

How to push this project to GitHub (PowerShell)
1. From the repository root (one level above `backend` and `frontend`):
```powershell
cd "c:\Users\mahmoud\Documents\GitHub\Amin Talent Solutions Website"
# Initialize git (if not already initialized)
git init
# Add files
git add .
# Commit
git commit -m "Initial commit — Amin Talent Solutions"
```
2A — Create remote using GitHub CLI (`gh`) and push (recommended):
```powershell
# Login once if needed
gh auth login
# Create repo and push
gh repo create Amin-Talent-Solutions-Website --public --source=. --remote=origin --push
```
2B — Or create a repo manually on GitHub and add remote/push:
```powershell
git remote add origin https://github.com/<your-username>/Amin-Talent-Solutions-Website.git
git branch -M main
git push -u origin main
```

Suggested branching workflow
- Use feature branches: `git checkout -b feature/profile-autofill`
- Keep `main` protected; create PRs and run CI before merging

CI and tests (suggested)
- Add GitHub Actions to run `composer install`, `npm ci`, `npm run build`, and `php artisan test` on PRs to `main`. I can generate a starter workflow if you want.

Next steps I can do for you
- Create the GitHub repo and push (I will need your confirmation and/or remote URL)
- Add a CI workflow (`.github/workflows/ci.yml`)
- Add API documentation (OpenAPI/Swagger)
- Add a LICENSE file (e.g., MIT)

If you'd like, I can run the git commands now and push the repo — tell me whether you want to use `gh` (I will run `gh repo create`) or provide the remote URL to push to. If you prefer to handle the remote creation yourself, run the commands above.
