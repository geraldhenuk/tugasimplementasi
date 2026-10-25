<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk - EcoWeave</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h1>🌿 EcoWeave</h1>
            <p class="subtitle">Masuk ke akun Anda</p>

            <% if (request.getAttribute("error") != null) { %>
                <div class="alert alert-error"><%= request.getAttribute("error") %></div>
            <% } %>
            <% if (request.getAttribute("message") != null) { %>
                <div class="alert alert-success"><%= request.getAttribute("message") %></div>
            <% } %>

            <form action="login" method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Masukkan Email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan Password" required>
                </div>
                <button type="submit" class="btn btn-primary">Masuk</button>
            </form>

            <p class="auth-switch">Belum punya akun? <a href="register">Daftar</a></p>
            <p class="auth-switch" style="margin-top:6px;"><a href="product-list">Lanjutkan sebagai tamu</a></p>

            <p style="text-align:center; margin-top:18px; font-size:12px; color:#aab8aa;">
                Demo admin: admin@ecoweave.com / admin123<br>
                Demo customer: annisa@gmail.com / customer123
            </p>
        </div>
    </div>
</body>
</html>
