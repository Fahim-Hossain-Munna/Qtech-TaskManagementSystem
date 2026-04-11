# Task Management System - NextiveE-BookSolution

একটি আধুনিক টাস্ক ম্যানেজমেন্ট সিস্টেম যা **Laravel 12**, **Vue 3**, **Tailwind CSS**, এবং **JWT Authentication** দিয়ে তৈরি।

![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.5-4FC08D?style=for-the-badge&logo=vue.js)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![Node.js](https://img.shields.io/badge/Node.js-18+-339933?style=for-the-badge&logo=node.js)

---

## 📋 প্রজেক্ট সম্পর্কে

এটি একটি সম্পূর্ণ ফুল-স্ট্যাক ওয়েব অ্যাপ্লিকেশন যা ব্যবহারকারীদের তাদের টাস্কগুলি সংগঠিত, ট্র্যাক এবং পরিচালনা করতে সাহায্য করে। প্রজেক্টটিতে রয়েছে আধুনিক বৈশিষ্ট্য, সুরক্ষিত API এবং প্রতিক্রিয়াশীল ইউজার ইন্টারফেস।

---

## ✨ প্রধান বৈশিষ্ট্যসমূহ

✅ **ব্যবহারকারী প্রমাণীকরণ** - নিবন্ধন এবং লগইন JWT টোকেন সহ
✅ **টাস্ক CRUD অপারেশন** - তৈরি, পড়া, আপডেট, মুছুন
✅ **টাস্ক সার্চ** - শিরোনাম বা বর্ণনা অনুযায়ী খুঁজুন
✅ **স্ট্যাটাস ফিল্টার** - Pending, In Progress, Completed দ্বারা ফিল্টার করুন
✅ **মোডাল ভিউ** - টাস্কের বিস্তারিত তথ্য দেখুন
✅ **ভ্যালিডেশন** - সার্ভার এবং ক্লায়েন্ট-সাইড ভ্যালিডেশন
✅ **লোডিং স্টেট** - স্কেলিটন লোডার এবং প্রগতি সূচক
✅ **প্রতিক্রিয়াশীল ডিজাইন** - সব ডিভাইসে সুন্দরভাবে কাজ করে
✅ **ডার্ক মোড সাপোর্ট** - চোখের জন্য আরামদায়ক
✅ **পেজিনেশন** - বড় তালিকা সহজে নেভিগেট করুন

---

## 🛠️ প্রযুক্তি স্ট্যাক

### ব্যাকএন্ড

- **Laravel 12** - শক্তিশালী PHP ফ্রেমওয়ার্ক
- **PHP 8.2** - সর্বশেষ PHP সংস্করণ
- **MySQL/MariaDB** - ডেটাবেস ম্যানেজমেন্ট
- **JWT Authentication** - টোকেন-ভিত্তিক প্রমাণীকরণ
- **Composer** - PHP প্যাকেজ ম্যানেজার
- **Artisan CLI** - Laravel কমান্ড লাইন ইন্টারফেস

### ফ্রন্টএন্ড

- **Vue 3** - প্রগতিশীল JavaScript ফ্রেমওয়ার্ক
- **Vue Router 4** - ক্লায়েন্ট-সাইড রাউটিং
- **Pinia 3** - স্টেট ম্যানেজমেন্ট
- **Tailwind CSS 4** - ইউটিলিটি-ফার্স্ট CSS ফ্রেমওয়ার্ক
- **Axios** - HTTP ক্লায়েন্ট
- **Vite** - অত্যাধুনিক বিল্ড টুল
- **SweetAlert2** - সুন্দর সতর্কতা ডায়ালগ
- **Heroicons** - সুন্দর SVG আইকন
- **Vue Awesome Paginate** - পেজিনেশন কম্পোনেন্ট

### ডেভেলপমেন্ট টুলস

- **npm/Node.js** - JavaScript প্যাকেজ ম্যানেজার
- **PHPUnit** - PHP টেস্টিং ফ্রেমওয়ার্ক
- **Laravel Sail** - ডোকার-ভিত্তিক উন্নয়ন পরিবেশ

---

## 📋 প্রয়োজনীয়তা

আপনার সিস্টেমে নিম্নলিখিত ইনস্টল করা থাকতে হবে:

### ন্যূনতম প্রয়োজনীয়তা

- **PHP 8.2** বা তার উপরে
- **Composer** (সর্বশেষ সংস্করণ)
- **Node.js 18+** এবং **npm** অথবা **yarn**
- **MySQL 5.7+** অথবা **MariaDB 10.3+**
- **Git**

---

## 🚀 ইনস্টলেশন গাইড

### ধাপ 1: রিপোজিটরি ক্লোন করুন

```bash
git clone https://github.com/Fahim-Hossain-Munna/Qtech-TaskManagementSystem.git
cd Qtech-TaskManagementSystem
```

### ধাপ 2: এনভায়রনমেন্ট ফাইল সেটআপ করুন

```bash
# .env.example ফাইল কপি করে .env তৈরি করুন
cp .env.example .env
```

### ধাপ 3: .env ফাইল কনফিগার করুন

নিম্নলিখিত তথ্য আপনার `.env` ফাইলে পূরণ করুন:

```env
APP_NAME="Task Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# ডাটাবেস কনফিগারেশন
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=your_password

# JWT সিক্রেট - নীচে জেনারেট করবেন
JWT_SECRET=your_jwt_secret_key
```

### ধাপ 4: PHP ডিপেন্ডেন্সি ইনস্টল করুন

```bash
composer install
```

### ধাপ 5: অ্যাপ্লিকেশন কী জেনারেট করুন

```bash
php artisan key:generate
```

### ধাপ 6: JWT সিক্রেট জেনারেট করুন

```bash
php artisan jwt:secret
```

এটি স্বয়ংক্রিয়ভাবে `.env` ফাইলে `JWT_SECRET` যোগ করবে।

### ধাপ 7: ডেটাবেস সেটআপ করুন

```bash
# মাইগ্রেশন চালান
php artisan migrate --seed

```

### ধাপ 8: JavaScript ডিপেন্ডেন্সি ইনস্টল করুন

```bash
npm install
```

### ধাপ 9: ডেভেলপমেন্ট সার্ভার চালান

দুটি টার্মিনাল খুলুন এবং নিম্নলিখিত কমান্ডগুলি চালান:

**টার্মিনাল 1 - Laravel সার্ভার:**

```bash
php artisan serve
```

**টার্মিনাল 2 - Vite ডেভেলপমেন্ট সার্ভার:**

```bash
npm run dev
```

বা একটি কমান্ডে উভয়ই চালাতে:

```bash
composer run dev
```

---

## 🔐 Demo Credentials

প্রজেক্টটি টেস্ট করার জন্য নিচের ডেমো অ্যাকাউন্টগুলো ব্যবহার করতে পারো:

👨‍💼 Admin Account

```bash
Email: admin@fhmtasksystem.dev
Password: secret
```

👤 User Account

```bash
Email: user@fhmtasksystem.dev
Password: secret
```

---

## 🎨 ফ্রন্টএন্ড প্রজেক্ট স্ট্রাকচার

```
resources/js/
├── app.js                    # অ্যাপ্লিকেশন এন্ট্রি পয়েন্ট
├── App.vue                   # রুট Vue কম্পোনেন্ট
├── bootstrap.js              # Axios কনফিগারেশন
├── routes.js                 # Vue Router কনফিগারেশন
├── components/               # রিজাসেবল কম্পোনেন্টস
│   ├── Header.vue           # শীর্ষ ন্যাভিগেশন বার
│   ├── Footer.vue           # ফুটার কম্পোনেন্ট
│   ├── TaskForm.vue         # টাস্ক তৈরি/সম্পাদনা ফর্ম
│   └── TaskTable.vue        # টাস্ক লিস্ট ডিসপ্লে
├── layouts/                  # লেআউট কম্পোনেন্টস
├── pages/                    # পেজ কম্পোনেন্টস
│   ├── Home.vue             # হোম পেজ (টাস্ক ম্যানেজমেন্ট)
│   ├── Login.vue            # লগইন পেজ
│   └── Register.vue         # নিবন্ধন পেজ
└── store/                    # Pinia স্টোর (স্টেট ম্যানেজমেন্ট)
```

---

### প্রয়োজনীয় পারমিশন সেট করুন

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage bootstrap/cache
```
