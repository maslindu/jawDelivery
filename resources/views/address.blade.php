<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Profile</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: rgb(251, 251, 251);
            min-height: 100vh;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 20px;
            min-height: calc(100vh - 80px);
        }

        .address-container {
            background: white;
            border: 2px solid #333;
            border-radius: 12px;
            padding: 32px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .address-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .delete-address {
            color: #ef4444;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .delete-address:hover {
            text-decoration: underline;
        }

        .address-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .address-item {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 20px;
            transition: border-color 0.2s;
        }

        .address-item.editing {
            border-color: #06b6d4;
            background-color: #f0fdff;
        }

        .address-item:hover:not(.editing) {
            border-color: #ccc;
        }

        .address-content {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .location-icon {
            width: 24px;
            height: 24px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .address-details {
            flex: 1;
        }

        .address-actions {
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .address-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
        }

        .form-textarea {
            min-height: 80px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .checkbox-input {
            width: 16px;
            height: 16px;
            accent-color: #ef4444;
        }

        .checkbox-label {
            font-size: 14px;
            color: #333;
            cursor: pointer;
        }

        .address-type {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .address-type .primary-label {
            color: #ef4444;
            font-weight: 700;
        }

        .address-text {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-edit {
            background: #06b6d4;
            color: white;
        }

        .btn-edit:hover:not(:disabled) {
            background: #0891b2;
        }

        .btn-edit:disabled {
            background: #94a3b8;
        }

        .btn-save {
            background: #10b981;
            color: white;
        }

        .btn-save:hover {
            background: #059669;
        }

        .btn-cancel {
            background: #6b7280;
            color: white;
        }

        .btn-cancel:hover {
            background: #4b5563;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .btn-icon {
            width: 16px;
            height: 16px;
        }

        .add-address {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            color: #ef4444;
            font-weight: 600;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .add-address:hover {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .add-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 16px;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 40px 16px;
            }

            .address-container {
                padding: 24px;
            }

            .address-title {
                font-size: 20px;
            }

            .address-content {
                flex-direction: column;
                gap: 12px;
            }

            .address-actions {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
    @include('components.header')

    <main class="main-content">
        <div class="address-container">
            <div class="address-header">
                <h1 class="address-title">Alamat Anda</h1>
            </div>

            <div class="address-list">
                @foreach($addresses as $address)
                <div class="address-item" data-id="{{ $address->id }}">
                    <div class="address-content">
                        <svg class="location-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>

                        <div class="address-details">
                            <!-- Display Mode -->
                            <div class="display-mode">
                                <div class="address-type">
                                    {{ $address->label }}
                                    @if($address->is_primary)
                                        <span class="primary-label">- UTAMA</span>
                                    @endif
                                </div>
                                <div class="address-text">{{ $address->address }}</div>
                            </div>

                            <form class="address-form edit-mode hidden" action="{{ route('user.address.update', ['id' => $address->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="form-label">Label Alamat</label>
                                    <input type="text" name="label" class="form-input" value="{{ $address->label }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="address" class="form-input form-textarea" required>{{ $address->address }}</textarea>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" name="is_primary" class="checkbox-input" id="primary-{{ $address->id }}"
                                           value="1" {{ $address->is_primary ? 'checked' : '' }}>
                                    <label for="primary-{{ $address->id }}" class="checkbox-label">Alamat utama</label>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-cancel cancel-edit">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-save">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20,6 9,17 4,12"></polyline>
                                        </svg>
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="address-actions">
                            <button class="btn btn-edit edit-btn" data-id="{{ $address->id }}">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('user.address.destroy', ['id' => $address->id]) }}" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3,6 5,6 21,6"></polyline>
                                        <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Add New Address Form -->
                <div class="address-item" id="add-address-form" style="display: none;">
                    <div class="address-content">
                        <svg class="location-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>

                        <div class="address-details">
                            <form class="address-form" action="{{ route('user.address.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Label Alamat</label>
                                    <input type="text" name="label" class="form-input" placeholder="Contoh: Rumah, Kantor" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="address" class="form-input form-textarea" placeholder="Masukkan alamat lengkap" required></textarea>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" name="is_primary" class="checkbox-input" id="primary-new" value="1">
                                    <label for="primary-new" class="checkbox-label">Alamat utama</label>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-cancel" id="cancel-add">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-save">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20,6 9,17 4,12"></polyline>
                                        </svg>
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="#" class="add-address" id="show-add-form">
                    <svg class="add-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span>Tambah Alamat Baru</span>
                </a>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/header.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            const cancelButtons = document.querySelectorAll('.cancel-edit');
            const showAddForm = document.getElementById('show-add-form');
            const addAddressForm = document.getElementById('add-address-form');
            const cancelAdd = document.getElementById('cancel-add');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const addressId = this.dataset.id;
                    const addressItem = document.querySelector(`[data-id="${addressId}"]`);
                    const displayMode = addressItem.querySelector('.display-mode');
                    const editMode = addressItem.querySelector('.edit-mode');

                    // Disable all other edit buttons
                    editButtons.forEach(btn => {
                        if (btn !== this) {
                            btn.disabled = true;
                        }
                    });

                    displayMode.classList.add('hidden');
                    editMode.classList.remove('hidden');
                    addressItem.classList.add('editing');
                    this.style.display = 'none';
                });
            });

            cancelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('.edit-mode');
                    const addressItem = this.closest('.address-item');
                    const displayMode = addressItem.querySelector('.display-mode');
                    const editBtn = addressItem.querySelector('.edit-btn');

                    displayMode.classList.remove('hidden');
                    form.classList.add('hidden');
                    addressItem.classList.remove('editing');
                    editBtn.style.display = 'inline-flex';

                    editButtons.forEach(btn => {
                        btn.disabled = false;
                    });
                });
            });

            showAddForm.addEventListener('click', function(e) {
                e.preventDefault();
                this.style.display = 'none';
                addAddressForm.style.display = 'block';

                editButtons.forEach(btn => {
                    btn.disabled = true;
                });
            });

            cancelAdd.addEventListener('click', function() {
                showAddForm.style.display = 'flex';
                addAddressForm.style.display = 'none';
                addAddressForm.querySelector('form').reset();

                editButtons.forEach(btn => {
                    btn.disabled = false;
                });
            });
        });
    </script>
</body>
</html>
