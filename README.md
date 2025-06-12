# 📚 E-Book Vault

The **E-Book Vault** is a free, user-friendly digital library platform where users can explore, download, and manage a wide selection of e-books. Designed for book lovers, it offers personalized features such as tailored book suggestions, profile management, and a smooth search-and-download experience — all powered by PHP and MySQL.

---

## 🧠 Project Overview

**E-Book Vault** was created to solve the frustration of limited book access and outdated library systems. It provides:

- 📥 Instant downloads of e-books  
- 👤 Personalized accounts  
- 🎯 Smart recommendations  
- 📚 A searchable, categorized book catalog  

Whether you're a casual reader or a student, this platform makes discovering and reading digital books easy and efficient.

---

## 🛠️ Tech Stack

| Layer        | Technology Used    |
|--------------|--------------------|
| Frontend     | HTML, CSS          |
| Backend      | PHP                |
| Database     | MySQL (phpMyAdmin) |
| Local Server | XAMPP              |

---

## 📦 Features

- ✅ User registration and login  
- ✅ Edit and update profile  
- ✅ Personalized book recommendations  
- ✅ Search books by title, author, or genre  
- ✅ View detailed book descriptions  
- ✅ Download e-books (PDF/EPUB)  
- ✅ Admin dashboard to manage books and users  
- ✅ Contact form for support & inquiries  

---

## 🖥️ How to Run the Project Locally (Using XAMPP)

### 🔧 Step 1: Install XAMPP

- Download from: [https://www.apachefriends.org](https://www.apachefriends.org)  
- Install and launch **XAMPP Control Panel**

---

### ▶️ Step 2: Start Services in XAMPP

- Launch **XAMPP Control Panel**
- Start:
  - Apache ✅
  - MySQL ✅

---

### 📁 Step 3: Clone or Download This Repo

**Option A: Using Git**

```bash
git clone https://github.com/your-username/ebook-vault.git
```

**Option B: Using ZIP**

```text
1. Download the ZIP
2. Extract the folder
3. Rename it to: library
4. Move it to: C:/xampp/htdocs/library
```

---

### 🗂️ Step 4: Import the MySQL Database

```text
1. Go to http://localhost/phpmyadmin
2. Create a new database named: library
3. Click on the new database
4. Import the file: database.sql (from the project root)
```

---

### ⚙️ Step 5: Configure the DB Connection

Open `includes/db.php` and check the following:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "library";
```

> 💡 No password is required for MySQL in XAMPP by default.

---

### 🌐 Step 6: Launch the Project

Open your browser and visit:

```
http://localhost/library/
```

---

## 🔑 Sample Credentials

```text
User Login
Email: user@example.com
Password: password123

Admin Login
Email: admin@vault.com
Password: adminpass
```

---

## 📸 Screenshots

Place screenshots in a folder named `screenshots/` and link them like this:

```markdown
![Home Page](screenshots/home.png)
![Dashboard](screenshots/dashboard.png)
```

---

## 🧱 System Architecture

```markdown
![Architecture](screenshots/architecture.png)
```

> Add your system architecture or data flow diagram to the `screenshots/` folder.

---

## 🚧 Future Improvements

- 🌟 Ratings and reviews for books  
- 🌙 Dark and light mode toggle  
- 📤 Upload functionality for user-shared books  
- 📧 Email verification on signup  
- 📊 Admin analytics dashboard  
- 🧑‍🤝‍🧑 Book club/forum feature  

---

## 📄 License

This project is licensed under the MIT License.

---

## 🙋‍♀️ Support

If you face any issues, open an issue on GitHub or contact the maintainer directly.

---
