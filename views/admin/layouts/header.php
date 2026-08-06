<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : "Admin Panel" ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { background-color: #343a40; min-height: 100vh; color: white; padding-top: 20px;}
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 10px 20px; display: block;}
        .sidebar a:hover { color: white; background-color: #495057; }
        .sidebar .active { color: white; background-color: #0d6efd; }
        .content-area { padding: 20px; }
        .stat-card { border-radius: 10px; padding: 20px; color: white; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;}
        .stat-card h3 { margin: 0; font-size: 2rem; }
        .stat-card p { margin: 0; font-size: 1rem; opacity: 0.8;}
        .stat-icon { font-size: 3rem; opacity: 0.5; }
        .bg-customers { background: linear-gradient(45deg, #4e73df, #224abe); }
        .bg-products { background: linear-gradient(45deg, #1cc88a, #13855c); }
        .bg-orders { background: linear-gradient(45deg, #f6c23e, #dda20a); }
        .bg-users { background: linear-gradient(45deg, #e74a3b, #be2617); }
        .bg-brands { background: linear-gradient(45deg, #36b9cc, #258391); }
    </style>
</head>
<body>
