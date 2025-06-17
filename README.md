# E-votes

## Install `Composer`

- Kunjungi [website resmi composer](https://getcomposer.org/download/), dan download `composer`
- Setelah download, lalu lakukan instalasi sampai selesai

## Install `Git Bash`
- Kunjungi [website resmi git bash](https://git-scm.com/downloads), dan download
- Setelah download, lalu lakukan instalasi sampai selesai

## Install `XAMPP`
- Kunjungi [webstie resmi XAMPP](https://www.apachefriends.org/download.html), dan download
- Setelah download, lalu lakuakan instalasi sampai selesai

## Cara menjalankan project `e-votes`

1. Clone repository `e-votes` di `htdocs`

```bash
git clone https://github.com/archeons-sudo/E-Votes.git
```

2. Pindah ke directory `e-votes`

```bash
cd E-Votes/
```

3. Lakukan `composer install`
    
```bash
composer install
```

4. Buat file dengan nama `.env`, lalu masukan isi file dari .env yang di berikan oleh `pembantu project`

5. Buat `database` dan `tabel`

```bash
cd c:\xampp\mysql\bin
```

```
mysql -u root -p
```


```bash
-- Membuat database
CREATE DATABASE IF NOT EXISTS e_votes;
USE e_votes;

-- Tabel untuk admin
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk periode pemilihan
CREATE TABLE periods (
    id INT PRIMARY KEY AUTO_INCREMENT,
    year_start INT NOT NULL,
    year_end INT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk kelas
CREATE TABLE classes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk kandidat
CREATE TABLE candidates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    period_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    vision TEXT NOT NULL,
    mission TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);

-- Tabel untuk siswa (voters)
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nis VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    class_id INT NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    google_id VARCHAR(100) UNIQUE,
    has_voted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Tabel untuk menyimpan hasil voting
CREATE TABLE votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    candidate_id INT NOT NULL,
    period_id INT NOT NULL,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    proof_pdf VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    FOREIGN KEY (period_id) REFERENCES periods(id) ON DELETE CASCADE
);

-- Menambahkan index untuk optimasi query
CREATE INDEX idx_student_nis ON students(nis);
CREATE INDEX idx_student_email ON students(email);
CREATE INDEX idx_period_status ON periods(status);
CREATE INDEX idx_votes_period ON votes(period_id);
```

6. Install semua `dependencies`

```bash
composer require google/apiclient tecnickcom/tcpdf phpoffice/phpspreadsheet --ignore-platform-req=ext-gd --with-all-dependencies
```