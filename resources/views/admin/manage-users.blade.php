<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JawDelivery - Kelola User</title>
  <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="{{ asset('css/manage-users.css') }}">
</head>
<body>
  @include('components.header')

  <main class="manage-user-main">
    <div class="user-management-container">
      <h1 class="page-title">Kelola User</h1>

      <div class="users-grid">
        @php
          $users = [
            ['name' => 'Ahmad Rizki', 'role' => 'Pelanggan'],
            ['name' => 'Sari Dewi', 'role' => 'Driver'],
            ['name' => 'Budi Santoso', 'role' => 'Admin'],
            ['name' => 'Maya Putri', 'role' => 'Pelanggan'],
            ['name' => 'Joko Widodo', 'role' => 'Driver'],
            ['name' => 'Rina Sari', 'role' => 'Pelanggan'],
            ['name' => 'Agus Setiawan', 'role' => 'Driver'],
            ['name' => 'Lisa Maharani', 'role' => 'Admin'],
            ['name' => 'Doni Pratama', 'role' => 'Pelanggan'],
            ['name' => 'Fitri Handayani', 'role' => 'Driver'],
            ['name' => 'Rudi Hermawan', 'role' => 'Pelanggan'],
            ['name' => 'Indira Sari', 'role' => 'Admin']
          ];
        @endphp

        @foreach ($users as $user)
          <div class="user-card">
            <div class="user-avatar"></div>
            <div class="user-info">
              <div class="user-name">{{ $user['name'] }}</div>
              <div class="user-role">{{ $user['role'] }}</div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </main>

  <script src="{{ asset('js/header.js') }}" defer></script>
</body>
</html>
