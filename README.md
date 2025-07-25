# INCUBE – Q&A Forum Platform

## Table of Contents
1. [Overview](#overview)  
2. [Features](#features)  
3. [Demo](#demo)  
4. [Tech Stack](#tech-stack)  
5. [Getting Started](#getting-started)  
   - [Prerequisites](#prerequisites)  
   - [Installation](#installation)  
   - [Environment Variables](#environment-variables)  
   - [Database Setup & Migrations](#database-setup--migrations)  
   - [Running the Application](#running-the-application)  
6. [Usage](#usage)  
7. [Contributing](#contributing)  
8. [Roadmap](#roadmap)  
9. [License](#license)  
10. [Contact](#contact)  

---

## Overview
Incube is an open-source Q&A forum designed for developers to ask questions, share knowledge, and collaborate on solutions. With robust features like voting, tagging, bookmarks, and user profiles, Incube aims to foster a vibrant community where developers can learn and contribute.

---

## Features
- **Questions & Answers**: Post questions, provide answers, and vote on both.  
- **Tags**: Categorize questions by technology, language, or topic.  
- **Bookmarks**: Save questions or answers for future reference.  
- **Comments**: Add comments to clarify questions/answers.  
- **User Profiles**: View contribution stats, reputation points, and badges.  
- **Search**: Full‑text search across questions and tags.  
- **Notifications**: Real‑time updates on answers, comments, and votes.  
- **Moderation**: Admin dashboard for managing users, posts, and content.

---

## Demo
![Landing Page](/public/assets/landingpage.png)

![Feed](/public/assets/feed.png)

![Questions](/public/assets/questions.png)

![My Profile](/public/assets/myprofile.png)



---

## Tech Stack
- **Backend**: Laravel, PHP  
- **Frontend**: Blade (or Vue/React—update as needed)  
- **Database**: MySQL / PostgreSQL  
- **Authentication**: Laravel Breeze / Sanctum  
- **Styling**: Tailwind CSS (or Bootstrap)  
- **Deployment**: Docker, Render / Heroku / DigitalOcean  

---

## Getting Started

### Prerequisites
- PHP 8.1+  
- Composer  
- Node.js & npm  
- MySQL or PostgreSQL  

### Installation
```bash
# 1. Clone the repo
git clone https://github.com/anudeepmuppalla1729/TrinwoProject.git

# 2. Install PHP dependencies
composer install

# 3. Install JavaScript dependencies
npm install

# 4. For Local Database (MySQl) -- change details in env file if provided (MySql Cloud DB Currently Unavailable)
php artisan migrate:fresh --seed

# 5. If no env file avialable
copy .env.example .env

### Output

```bash
php artisan serve

### Admin Panel Access
Route : /admin/login

#### Admin Details : 
User Name : admin
Password : StrongPassword123!


