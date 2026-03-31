<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — Exchange Pro</title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/img/arm.ico'); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous">

  <style>
    :root {
      --primary: #1a73e8;
      --primary-hover: #1557b0;
      --login-bg: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
      --login-card-bg: #fff;
      --login-card-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
      --login-text: #202124;
      --login-text-secondary: #5f6368;
      --login-text-muted: #9aa0a6;
      --login-border: #dadce0;
      --login-input-bg: #fff;
    }

    [data-theme="dark"] {
      --login-bg: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
      --login-card-bg: #1e1e2d;
      --login-card-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
      --login-text: #e8eaed;
      --login-text-secondary: #9aa0a6;
      --login-text-muted: #6b7280;
      --login-border: #3a3a4a;
      --login-input-bg: #252536;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--login-bg);
      padding: 1rem;
      transition: background 0.3s;
    }

    .login-wrapper {
      width: 100%;
      max-width: 420px;
      animation: fadeInUp 0.5s ease-out both;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Flash messages */
    .flash-messages { margin-bottom: 1rem; }
    .flash-messages .alert {
      font-size: 0.875rem;
      border: none;
      border-radius: 10px;
      padding: 0.75rem 1rem;
      animation: fadeInUp 0.4s ease-out both;
    }

    /* Card */
    .login-card {
      background: var(--login-card-bg);
      border-radius: 16px;
      box-shadow: var(--login-card-shadow);
      padding: 2.5rem 2rem 2rem;
      transition: background 0.3s, box-shadow 0.3s;
    }

    /* Brand */
    .brand { text-align: center; margin-bottom: 1.75rem; }

    .brand-icon {
      width: 56px;
      height: 56px;
      background: var(--primary);
      border-radius: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.75rem;
    }
    .brand-icon i { font-size: 1.5rem; color: #fff; }

    .brand h1 {
      font-size: 1.375rem;
      font-weight: 700;
      color: var(--login-text);
      margin: 0 0 0.25rem;
    }
    .brand p {
      font-size: 0.8125rem;
      color: var(--login-text-secondary);
      margin: 0;
    }

    /* Section heading */
    .section-heading {
      font-size: 1.05rem;
      font-weight: 600;
      color: var(--login-text);
      margin-bottom: 1.25rem;
      text-align: center;
    }

    /* Input groups */
    .form-floating-group { position: relative; margin-bottom: 1rem; }

    .form-floating-group .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--login-text-muted);
      font-size: 0.9rem;
      transition: color 0.2s;
      z-index: 4;
      pointer-events: none;
    }

    .form-floating-group .form-control {
      padding-left: 2.5rem;
      padding-right: 1rem;
      height: 48px;
      border: 1.5px solid var(--login-border);
      border-radius: 10px;
      font-size: 0.9375rem;
      color: var(--login-text);
      background: var(--login-input-bg);
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-floating-group .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.15);
      outline: none;
    }
    .form-floating-group .form-control:focus ~ .input-icon { color: var(--primary); }

    .form-floating-group .form-control::placeholder {
      color: var(--login-text-muted);
      font-weight: 400;
    }

    /* Password toggle */
    .password-wrapper { position: relative; }
    .password-wrapper .form-control { padding-right: 2.75rem; }

    .btn-toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--login-text-muted);
      cursor: pointer;
      padding: 4px 6px;
      font-size: 0.95rem;
      z-index: 4;
      transition: color 0.2s;
      line-height: 1;
    }
    .btn-toggle-password:hover { color: var(--primary); }

    /* Validation errors */
    .field-error {
      color: #d93025;
      font-size: 0.775rem;
      margin-top: 0.3rem;
      padding-left: 2px;
    }

    /* Submit button */
    .btn-login {
      width: 100%;
      height: 48px;
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 0.9375rem;
      font-weight: 600;
      letter-spacing: 0.01em;
      cursor: pointer;
      transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
      margin-top: 0.5rem;
    }
    .btn-login:hover {
      background: var(--primary-hover);
      box-shadow: 0 2px 12px rgba(26, 115, 232, 0.3);
    }
    .btn-login:active { transform: scale(0.985); }

    /* Theme toggle */
    .btn-theme-login {
      position: absolute;
      top: 1rem;
      right: 1rem;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 1px solid var(--login-border);
      background: var(--login-card-bg);
      color: var(--login-text-muted);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      transition: all 0.2s;
      z-index: 10;
    }
    .btn-theme-login:hover {
      color: var(--primary);
      border-color: var(--primary);
      background: rgba(26,115,232,0.08);
    }

    /* Footer */
    .login-footer {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.75rem;
      color: var(--login-text-muted);
    }
  </style>
</head>
<body>

  <!-- Dark mode toggle -->
  <button type="button" class="btn-theme-login" id="loginThemeToggle" aria-label="Basculer le thème">
    <i class="fa-solid fa-moon"></i>
  </button>

  <div class="login-wrapper">

    <!-- Flash messages -->
    <div class="flash-messages">
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check me-1"></i> <?php echo $this->session->flashdata('success'); ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('info')): ?>
        <div class="alert alert-info"><i class="fa-solid fa-circle-info me-1"></i> <?php echo $this->session->flashdata('info'); ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark me-1"></i> <?php echo $this->session->flashdata('error'); ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('warning')): ?>
        <div class="alert alert-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo $this->session->flashdata('warning'); ?></div>
      <?php endif; ?>
    </div>

    <!-- Login card -->
    <div class="login-card">

      <!-- Brand -->
      <div class="brand">
        <div class="brand-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
        <h1>Exchange Pro</h1>
        <p>Plateforme d'Échange Professionnel</p>
      </div>

      <div class="section-heading">Connexion</div>

      <?php echo form_open('users/login/?' . $_SERVER['QUERY_STRING']); ?>

        <!-- Username -->
        <div class="form-floating-group">
          <i class="fa-solid fa-user input-icon"></i>
          <?php echo form_input('users_username', set_value('users_username'), [
            'class'       => 'form-control',
            'placeholder' => "Nom d'utilisateur",
            'autocomplete'=> 'username',
          ]); ?>
          <div class="field-error"><?php echo form_error('users_username'); ?></div>
        </div>

        <!-- Password -->
        <div class="form-floating-group password-wrapper">
          <i class="fa-solid fa-lock input-icon"></i>
          <?php echo form_password('users_password', '', [
            'class'       => 'form-control',
            'id'          => 'passwordField',
            'placeholder' => 'Mot de passe',
            'autocomplete'=> 'current-password',
          ]); ?>
          <button type="button" class="btn-toggle-password" id="togglePassword" aria-label="Afficher le mot de passe">
            <i class="fa-solid fa-eye"></i>
          </button>
          <div class="field-error"><?php echo form_error('users_password'); ?></div>
        </div>

        <!-- Submit -->
        <?php echo form_submit('submit', 'Se connecter', ['class' => 'btn-login']); ?>

      <?php echo form_close(); ?>
    </div>

    <!-- Footer -->
    <div class="login-footer">
      &copy; <?php echo date('Y'); ?> Exchange Pro &mdash; Tous droits réservés
    </div>

  </div>

  <script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>
  <script>
    // Password toggle
    var toggleBtn = document.getElementById('togglePassword');
    var field     = document.getElementById('passwordField');
    if (toggleBtn && field) {
      toggleBtn.addEventListener('click', function () {
        var icon = this.querySelector('i');
        if (field.type === 'password') {
          field.type = 'text';
          icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
          field.type = 'password';
          icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
      });
    }

    // Dark mode toggle (synced with main app)
    (function() {
      var html = document.documentElement;
      var btn = document.getElementById('loginThemeToggle');
      var stored = localStorage.getItem('ep-theme');
      if (stored === 'dark') {
        html.setAttribute('data-theme', 'dark');
        btn.querySelector('i').classList.replace('fa-moon', 'fa-sun');
      }
      btn.addEventListener('click', function() {
        var icon = this.querySelector('i');
        if (html.getAttribute('data-theme') === 'dark') {
          html.setAttribute('data-theme', 'light');
          localStorage.setItem('ep-theme', 'light');
          icon.classList.replace('fa-sun', 'fa-moon');
        } else {
          html.setAttribute('data-theme', 'dark');
          localStorage.setItem('ep-theme', 'dark');
          icon.classList.replace('fa-moon', 'fa-sun');
        }
      });
    })();
  </script>
</body>
</html>
