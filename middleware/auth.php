<?php //middleware sebagai satpam, kaya buat cek dia udah login belum lalu rolenya apa
const LOGIN_URL = "/pages/auth/login.php";
const BOOK_INDEX_URL = "/pages/books/index.php";
const FORBIDDEN_URL = "/pages/errors/403.php";

#limiter digunakan untuk mengatasi bot yang spam buat login jadi misal cuman bisa 3 coba lalu kalau salah harus tunggu berapa menit dan tergantung web developernya

//Mengecek apakah sebuah session itu sudah dimulai atau belum, jika belum maka session akan dimulai 
function sessionStart() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

//Mengecek apakah seorang pengguna sudah login atau belum 
function isLoggedin() {
    sessionStart();
    $isLoggedIn = isset($_SESSION['user']);

    return $isLoggedIn;
}

//Mendapatkan data pengguna yang sudah login 
function getAuthorUser() {
    sessionStart();
    $user = $_SESSION['user'] ?? null;

    return $user;
}

//Mengecek apakah role pengguna itu sesuai atau tidak 
function hasRole(array $roles) {
    $user = getAuthorUser();

    $isUserHasAuthorizeRole = $user != null && in_array($user['role'], $roles);

    return $isUserHasAuthorizeRole;
}

//Menghandle pengguna yang belum login 
function requireAuth() {
    if (!isLoggedin()) {
        $_SESSION['errors'] = [
            'login' => 'Silahkan login terlebih dahulu sebelum mengakses halaman ini'
        ];

        header('Location: ' . LOGIN_URL);
        exit;
    }
}

//Menghandle pengguna yang tidak memiliki role yang sesuai atau tidak terautorisasi
function requireRole(array $roles) {
    requireAuth();

    if (!hasRole($roles)) {
        header('Location: ' . FORBIDDEN_URL);
        exit;
    }
}

//Menghandle pengguna yang sudah login sebelumnya 
function requireGuest() {
    if (isLoggedin()) {
        header('Location ' . BOOK_INDEX_URL);
        exit;
    }
}

?>