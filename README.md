
<div align="center">

<a href="#">
  <img src="https://readme-typing-svg.herokuapp.com?font=Fira+Code&weight=600&size=32&pause=1000&color=00FFCC&center=true&vCenter=true&width=800&lines=CRITICAL+SYSTEM+BREACH+DETECTED...;INITIATING+SQL+INJECTION+ANALYSIS...;EXECUTING+DEFENSIVE+PROTOCOLS...;RESEARCH+BY+RIZKY+MAULANA" alt="Typing SVG" />
</a>

<img src="https://raw.githubusercontent.com/andreasbm/readme/master/assets/lines/aqua.gif" width="100%">

# 🛑 𝗖𝗬𝗕𝗘𝗥 𝗦𝗘𝗖𝗨𝗥𝗜𝗧𝗬 𝗥𝗘𝗦𝗘𝗔𝗥𝗖𝗛: 𝗦𝗤𝗟 𝗜𝗡𝗝𝗘𝗖𝗧𝗜𝗢𝗡 𝗔𝗡𝗔𝗟𝗬𝗦𝗜𝗦 🛑

<p align="center">
  <kbd>Status: COMPLETED</kbd> • <kbd>Severity: CRITICAL (CVS 9.8)</kbd> • <kbd>Target: AUTHENTICATION MODULE</kbd>
</p>

> **DISCLAIMER:** *Repositori dan eksperimen ini dibuat secara eksklusif untuk tujuan edukasi dan pemenuhan Tugas Ujian Tengah Semester (UTS). Segala bentuk teknik eksploitasi di sini hanya dilakukan pada server lokal (Localhost) dan tidak disalahgunakan untuk meretas sistem nyata.*

</div>

<br>

<table align="center" width="100%">
  <tr>
    <td width="50%" align="center">
      <h3>👨‍💻 SECURITY RESEARCHER</h3>
      <code>NAMA  : Rizky Maulana</code><br>
      <code>NIM   : 312410430</code><br>
      <code>KELAS : I241C</code><br>
      <code>TUGAS : UTS Pemrograman Web</code>
    </td>
    <td width="50%" align="center">
      <h3>🛠️ TECH STACK DASHBOARD</h3>
      <img src="https://img.shields.io/badge/PHP_8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" />
      <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" /><br>
      <img src="https://img.shields.io/badge/Apache_Server-D22128?style=for-the-badge&logo=apache&logoColor=white" />
      <img src="https://img.shields.io/badge/Article_Medium-12100E?style=for-the-badge&logo=medium&logoColor=white" />
    </td>
  </tr>
</table>

<br>

<div align="center">
  
## 📡 𝗣𝗨𝗕𝗟𝗜𝗞𝗔𝗦𝗜 𝗥𝗜𝗦𝗘𝗧 𝗘𝗞𝗦𝗞𝗟𝗨𝗦𝗜𝗙

Dokumentasi naratif dan pembahasan mendalam mengenai eksperimen ini telah dipublikasikan di **Medium**. Klik *badge* di bawah ini untuk membaca artikel lengkap:

[![READ FULL ARTICLE ON MEDIUM](https://img.shields.io/badge/READ_FULL_ARTICLE_ON_MEDIUM-00FFCC?style=for-the-badge&logo=medium&logoColor=black&labelColor=black)]([LINK_MEDIUM_KAMU])

<img src="https://raw.githubusercontent.com/andreasbm/readme/master/assets/lines/aqua.gif" width="80%">

</div>

## 📑 𝗧𝗔𝗕𝗟𝗘 𝗢𝗙 𝗖𝗢𝗡𝗧𝗘𝗡𝗧𝗦
1. [Executive Summary](#1-executive-summary)
2. [System Architecture & Database Blueprint](#2-system-architecture--database-blueprint)
3. [Vulnerability Analysis (Celah Keamanan)](#3-vulnerability-analysis-celah-keamanan)
4. [Exploitation Methodology (Metode Serangan)](#4-exploitation-methodology-metode-serangan)
5. [Defensive Countermeasure (Mitigasi)](#5-defensive-countermeasure-mitigasi)
6. [Visual Evidence (Log Sistem)](#6-visual-evidence-log-sistem)
7. [References](#7-references)

---

## 1. EXECUTIVE SUMMARY
Repositori ini berisi *source code* dan dokumentasi dari pengujian penetrasi (Pen-Test) skala kecil pada sistem autentikasi web. Fokus utama riset ini adalah mendemonstrasikan **CWE-89: Improper Neutralization of Special Elements used in an SQL Command** (SQL Injection). Eksperimen membuktikan bahwa sistem login yang dibangun dengan `mysqli_query` dinamis tanpa sanitasi dapat dibobol sepenuhnya menggunakan manipulasi operator logika `OR`.

---

## 2. SYSTEM ARCHITECTURE & DATABASE BLUEPRINT
Pengujian dilakukan pada *environment* lokal (XAMPP). Berikut adalah skema *database* `eksperimen_keamanan` yang digunakan sebagai target operasi:

```sql
-- DDL & DML untuk target database
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);

-- Menyisipkan data rahasia target (Admin)
INSERT INTO users (username, password) VALUES ('admin', 'rahasia123');
```

---

## 3. VULNERABILITY ANALYSIS (CELAH KEAMANAN)
File `vulnerable.php` mensimulasikan sistem yang rentan. Kerentanan terjadi karena input dari pengguna (`$_POST`) langsung digabungkan (*concatenated*) ke dalam string SQL tanpa proses validasi, *escaping*, atau sanitasi.

**Code Breakdown (Bagian Rentan):**
```php
$username = $_POST['username'];
$password = $_POST['password'];

// ❌ CRITICAL FLAW: Input langsung dimasukkan ke dalam query
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($query);
```

---

## 4. EXPLOITATION METHODOLOGY (METODE SERANGAN)
Untuk mengeksploitasi celah di atas, serangan dilakukan pada kolom input `username` untuk memanipulasi *parser* MySQL.

* **Attack Vector / Payload:** `' OR '1'='1'#`
* **Dummy Password:** `hacker123`

**Proses di Database Engine:**
Ketika payload dikirimkan, *query* yang ditangkap oleh server berubah menjadi seperti ini:
```sql
SELECT * FROM users WHERE username = '' OR '1'='1'# AND password = 'hacker123'
```
**Analisis Logika:**
1.  `'` di awal menutup string untuk parameter username.
2.  `OR '1'='1'` adalah kondisi *tautology* (selalu benar/TRUE).
3.  `#` adalah simbol komentar dalam MySQL. Ini membutakan mesin database, sehingga perintah pengecekan `AND password = 'hacker123'` diabaikan sepenuhnya.
4.  **Hasil:** Server mengembalikan *TRUE* dan memberikan akses masuk sebagai pengguna pertama di tabel (Admin).

---

## 5. DEFENSIVE COUNTERMEASURE (MITIGASI)
Untuk menambal (*patching*) kerentanan *Zero-day* ini, file `secure.php` diimplementasikan menggunakan arsitektur **Prepared Statements**.

**Code Breakdown (Bagian Aman):**
```php
// ✅ SECURE APPROACH: Menggunakan Prepared Statements
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");

// Mengikat parameter ('ss' berarti 2 data string). Input pengguna dianggap murni sebagai DATA, bukan PERINTAH SQL.
$stmt->bind_param("ss", $username, $password); 
$stmt->execute();
$result = $stmt->get_result();
```
Dengan arsitektur ini, *database engine* memisahkan struktur *query* dari data. Meskipun pengguna memasukkan simbol berbahaya seperti `'` atau `#`, itu hanya akan dibaca sebagai teks biasa, menetralisir ancaman SQLi secara absolut.

---

## 6. VISUAL EVIDENCE (LOG SISTEM)
> *Klik pada setiap dropdown untuk memperluas tangkapan layar dari hasil eksperimen.*

<details>
<summary><b>🟢 LOG 01: NORMAL AUTHENTICATION (SYSTEM STABLE)</b></summary>
<br>
Sistem memproses kredensial yang valid dengan benar. Logika <i>query</i> berjalan sesuai desain.<br><br>

> *(Masukkan link gambar normal login di sini)*

<img width="598" height="341" alt="login normal" src="https://github.com/user-attachments/assets/a45c2416-8d14-4d4c-9d52-828e573d93c5" />

</details>

<details>
<summary><b>🔴 LOG 02: SYSTEM BREACHED (SQL INJECTION SUCCESS)</b></summary>
<br>
<b>CRITICAL ALERT:</b> Injeksi logika OR sukses dieksekusi. Autentikasi berhasil di-bypass total tanpa mengetahui <i>password</i> yang sebenarnya.<br><br>

> *(Masukkan link gambar pembobolan di sini)*

<img width="394" height="208" alt="login bobol" src="https://github.com/user-attachments/assets/b820a80a-af1c-44b4-b698-e2a4d03f4eb9" />

</details>

<details>
<summary><b>🛡️ LOG 03: SYSTEM SECURED (ATTACK BLOCKED)</b></summary>
<br>
Serangan SQLi ditangkis. Payload berbahaya direduksi menjadi string biasa oleh <i>Prepared Statements</i>. Akses ditolak.<br><br>

> *(Masukkan link gambar aman/gagal login di sini)*

<img width="500" height="273" alt="login gagal" src="https://github.com/user-attachments/assets/b3a45191-13fa-42d2-9446-36151b16897a" />


</details>

---

## 7. REFERENCES
* [OWASP (Open Web Application Security Project) - SQL Injection](https://owasp.org/www-community/attacks/SQL_Injection)
* [PHP Official Documentation - Prepared Statements](https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
* Materi Pembelajaran Pemrograman Web.

<br>

<div align="center">
<img src="https://raw.githubusercontent.com/andreasbm/readme/master/assets/lines/aqua.gif" width="100%">

### *"A chain is only as strong as its weakest link. Validate strictly, sanitize deeply."*

<code>© 2026 Penetration Test Documentation by Rizky Maulana. All Rights Reserved.</code>

</div>
```

***
