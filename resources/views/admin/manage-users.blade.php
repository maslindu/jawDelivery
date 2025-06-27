<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JawDelivery - Kelola User</title>
  <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="{{ asset('css/manage-users.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="admin-manage-users-page">
  @include('components.header')

  <main class="admin-manage-users-main">
    <div class="admin-manage-users-container">
      <div class="admin-manage-users-header">
        <h1 class="admin-manage-users-title">Kelola User</h1>
        <button class="admin-add-user-btn" onclick="openAddModal()">
          + Tambah User
        </button>
      </div>

      @if(session('success'))
        <div class="admin-alert admin-alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="admin-alert admin-alert-error">
          {{ session('error') }}
        </div>
      @endif

      @if($errors->any())
        <div class="admin-alert admin-alert-error">
          <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="admin-users-grid">
        @forelse ($users as $user)
          <div class="admin-user-card">
            <div class="admin-user-card-header">
              @if($user->avatar_link)
                <img src="{{ $user->getAvatarUrlAttribute() }}" alt="Avatar" class="admin-user-avatar">
              @else
                <div class="admin-user-avatar-placeholder">
                  {{ strtoupper(substr($user->fullName, 0, 1)) }}
                </div>
              @endif
              
              <div class="admin-user-basic-info">
                <div class="admin-user-name">{{ $user->fullName }}</div>
                <div class="admin-user-email">{{ $user->email }}</div>
                {{-- PERBAIKAN: Role display yang lebih aman --}}
                @php
                  $userRole = $user->roles->first();
                  $roleName = $userRole ? $userRole->name : 'no-role';
                  $roleDisplay = $userRole ? ucfirst($userRole->name) : 'Tidak Ada Role';
                @endphp
                <span class="admin-user-role-badge admin-role-{{ strtolower($roleName) }}">
                  {{ $roleDisplay }}
                </span>
              </div>
            </div>

            <div class="admin-user-details">
              <div class="admin-user-detail-item">
                <strong>Username:</strong> {{ $user->username }}
              </div>
              @if($user->phone)
                <div class="admin-user-detail-item">
                  <strong>Telepon:</strong> {{ $user->phone }}
                </div>
              @endif
              @if($user->address)
                <div class="admin-user-detail-item">
                  <strong>Alamat:</strong> {{ Str::limit($user->address, 50) }}
                </div>
              @endif
              {{-- PERBAIKAN: Tampilkan debug info role --}}
              <div class="admin-user-detail-item" style="font-size: 12px; color: #6b7280;">
                <strong>Role ID:</strong> {{ $userRole ? $userRole->id : 'N/A' }}
              </div>
            </div>

            <div class="admin-user-actions">
              <button class="admin-action-btn admin-edit-btn" onclick="openEditModal({{ $user->id }})">
                Edit
              </button>
              @if($user->id !== auth()->user()->id)
                <button class="admin-action-btn admin-delete-btn" onclick="confirmDelete({{ $user->id }}, '{{ $user->fullName }}')">
                  Hapus
                </button>
              @endif
            </div>
          </div>
        @empty
          <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6b7280;">
            <p>Belum ada user yang terdaftar.</p>
          </div>
        @endforelse
      </div>

      <div class="admin-pagination-wrapper">
        {{ $users->links() }}
      </div>
    </div>
  </main>

  <!-- Add User Modal -->
  <div class="admin-modal-overlay" id="addModal">
    <div class="admin-modal-content">
      <div class="admin-modal-header">
        <h2 class="admin-modal-title">Tambah User Baru</h2>
        <button class="admin-close-btn" onclick="closeModal('addModal')">&times;</button>
      </div>
      
      <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-group">
          <label class="admin-form-label">Username *</label>
          <input type="text" name="username" class="admin-form-input" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Nama Lengkap *</label>
          <input type="text" name="fullName" class="admin-form-input" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Email *</label>
          <input type="email" name="email" class="admin-form-input" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Password *</label>
          <input type="password" name="password" class="admin-form-input" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Telepon</label>
          <input type="text" name="phone" class="admin-form-input">
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Alamat</label>
          <textarea name="address" class="admin-form-textarea"></textarea>
        </div>
        
        {{-- PERBAIKAN: Role select yang lebih aman --}}
        <div class="admin-form-group">
          <label class="admin-form-label">Role *</label>
          <select name="role" class="admin-form-select" required>
            <option value="">Pilih Role</option>
            {{-- Hanya tampilkan role yang ada di RoleEnum --}}
            @php
              $validRoles = ['admin', 'pelanggan', 'kurir'];
            @endphp
            @foreach($roles as $role)
              @if(in_array($role->name, $validRoles))
                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
              @endif
            @endforeach
          </select>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Avatar</label>
          <input type="file" name="avatar" class="admin-form-input" accept="image/*">
        </div>
        
        <div class="admin-form-actions">
          <button type="submit" class="admin-btn-primary">Simpan</button>
          <button type="button" class="admin-btn-secondary" onclick="closeModal('addModal')">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div class="admin-modal-overlay" id="editModal">
    <div class="admin-modal-content">
      <div class="admin-modal-header">
        <h2 class="admin-modal-title">Edit User</h2>
        <button class="admin-close-btn" onclick="closeModal('editModal')">&times;</button>
      </div>
      
      <form id="editForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="admin-form-group">
          <label class="admin-form-label">Username *</label>
          <input type="text" name="username" id="edit_username" class="admin-form-input" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Nama Lengkap *</label>
          <input type="text" name="fullName" id="edit_fullName" class="admin-form-input" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Email *</label>
          <input type="email" name="email" id="edit_email" class="admin-form-input" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Password (kosongkan jika tidak ingin mengubah)</label>
          <input type="password" name="password" class="admin-form-input">
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Telepon</label>
          <input type="text" name="phone" id="edit_phone" class="admin-form-input">
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Alamat</label>
          <textarea name="address" id="edit_address" class="admin-form-textarea"></textarea>
        </div>
        
        {{-- PERBAIKAN: Role select untuk edit --}}
        <div class="admin-form-group">
          <label class="admin-form-label">Role *</label>
          <select name="role" id="edit_role" class="admin-form-select" required>
            @php
              $validRoles = ['admin', 'pelanggan', 'kurir'];
            @endphp
            @foreach($roles as $role)
              @if(in_array($role->name, $validRoles))
                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
              @endif
            @endforeach
          </select>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label">Avatar Baru</label>
          <input type="file" name="avatar" class="admin-form-input" accept="image/*">
        </div>
        
        <div class="admin-form-actions">
          <button type="submit" class="admin-btn-primary">Update</button>
          <button type="button" class="admin-btn-secondary" onclick="closeModal('editModal')">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="admin-modal-overlay" id="deleteModal">
    <div class="admin-modal-content">
      <div class="admin-modal-header">
        <h2 class="admin-modal-title">Konfirmasi Hapus</h2>
        <button class="admin-close-btn" onclick="closeModal('deleteModal')">&times;</button>
      </div>
      
      <p style="margin-bottom: 20px; text-align: center;">
        Apakah Anda yakin ingin menghapus user <strong id="deleteUserName"></strong>?
      </p>
      
      <div class="admin-form-actions">
        <button class="admin-btn-primary" style="background: #ef4444;" onclick="executeDelete()">Hapus</button>
        <button class="admin-btn-secondary" onclick="closeModal('deleteModal')">Batal</button>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/header.js') }}" defer></script>
  <script>
    let deleteUserId = null;

    function openAddModal() {
      document.getElementById('addModal').classList.add('active');
    }

    function openEditModal(userId) {
      fetch(`/admin/users/${userId}`)
        .then(response => response.json())
        .then(user => {
          console.log('User data:', user); // PERBAIKAN: Debug log
          
          document.getElementById('edit_username').value = user.username;
          document.getElementById('edit_fullName').value = user.fullName;
          document.getElementById('edit_email').value = user.email;
          document.getElementById('edit_phone').value = user.phone || '';
          document.getElementById('edit_address').value = user.address || '';
          
          // PERBAIKAN: Set role dengan lebih aman
          const roleSelect = document.getElementById('edit_role');
          const userRole = user.roles && user.roles.length > 0 ? user.roles[0].name : '';
          console.log('Setting role to:', userRole); // Debug log
          
          if (userRole) {
            roleSelect.value = userRole;
          }
          
          document.getElementById('editForm').action = `/admin/manage-users/${userId}`;
          document.getElementById('editModal').classList.add('active');
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Gagal memuat data user');
        });
    }

    function confirmDelete(userId, userName) {
      deleteUserId = userId;
      document.getElementById('deleteUserName').textContent = userName;
      document.getElementById('deleteModal').classList.add('active');
    }

    function executeDelete() {
      if (deleteUserId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/manage-users/${deleteUserId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
      }
    }

    function closeModal(modalId) {
      document.getElementById(modalId).classList.remove('active');
      if (modalId === 'deleteModal') {
        deleteUserId = null;
      }
    }

    // Close modal when clicking outside
    document.querySelectorAll('.admin-modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', function(e) {
        if (e.target === this) {
          this.classList.remove('active');
        }
      });
    });

    // Auto hide alerts
    setTimeout(() => {
      const alerts = document.querySelectorAll('.admin-alert');
      alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.5s ease';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);
  </script>
</body>
</html>
