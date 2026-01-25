<p align="center">
  <a href="#">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Aadhi Matrimony Logo">
  </a>
</p>

<h1 align="center">Aadhi Matrimony</h1>

<p align="center">
  The modern way to find your perfect life partner. AI-powered matchmaking with video profiles and verified connections.
</p>

<p align="center">
  <a href="#features">Features</a> •
  <a href="#tech-stack">Tech Stack</a> •
  <a href="#getting-started">Getting Started</a> •
  <a href="#pages">Pages</a> •
  <a href="#license">License</a>
</p>

---

## ✨ Features

### Core Features
- **🎥 Video Profiles** - 30-second video introductions for authentic connections
- **🤖 AI-Powered Matching** - Smart compatibility scores based on personality, interests, and values
- **✅ Verification System** - Phone OTP and video verification for trustworthy connections
- **💬 Real-time Chat** - Instant messaging with voice notes and icebreakers
- **📹 Video Calls** - Face-to-face conversations with your matches
- **🎮 Interactive Games** - Fun compatibility quizzes to break the ice

### Additional Features
- **📊 Profile Analytics** - Track views, likes, and engagement metrics
- **🚀 Profile Boosts** - Increase your visibility with profile boosts
- **🌙 Dark Mode** - Beautiful dark/light theme toggle
- **📱 Responsive Design** - Works seamlessly on all devices

---

## 🚀 Tech Stack

- **Backend:** Laravel 11, PHP 8.2+
- **Frontend:** Tailwind CSS v4, Alpine.js
- **Database:** MySQL/PostgreSQL
- **Search:** MeiliSearch
- **Real-time:** Laravel Reverb (WebSocket)
- **Payment:** Razorpay
- **Build Tool:** Vite

---

## 📦 Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL or PostgreSQL
- MeiliSearch (optional, for advanced search)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/viviztech/modern-matrimony-app.git
   cd modern-matrimony-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Setup environment variables**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your `.env` file**
   - Set database credentials
   - Add payment gateway keys (Razorpay)
   - Configure MeiliSearch (optional)

6. **Run migrations and seed**
   ```bash
   php artisan migrate --seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Start Vite for hot reloading (in a separate terminal)**
   ```bash
   npm run dev
   ```

---

## 📄 Pages

| Route | Description |
|-------|-------------|
| `/` | Landing page with hero, features, pricing |
| `/features` | App features showcase |
| `/how-it-works` | 4-step process guide |
| `/pricing` | Subscription plans (₹0/₹799/₹1,499) |
| `/about` | Company story and values |
| `/faq` | Frequently asked questions |
| `/contact` | Contact form and information |
| `/register` | User registration |
| `/login` | User login |
| `/privacy-policy` | Privacy policy |
| `/terms-of-service` | Terms of service |
| `/cookie-policy` | Cookie policy |

---

## 🛠️ Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
# Format PHP code
composer format

# Format JavaScript/CSS
npm run format
```

### Database Migrations
```bash
# Create new migration
php artisan make:migration create_users_table

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback
```

### Clear Cache
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

---

## 🔒 Security

If you discover any security vulnerabilities, please send an e-mail to security@aadhimatrimony.com instead of opening a public issue.

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework used
- [Tailwind CSS](https://tailwindcss.com) - CSS framework
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript framework
- [Laravel Breeze](https://laravel.com/docs/starter-kits) - Authentication scaffolding

---

<p align="center">
  Made with ❤️ by <a href="#">Aadhi Matrimony Team</a>
</p>
