<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ChicAura SCM System</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <style>
    :root {
      --primary-color: #6366f1;
      --primary-dark: #4f46e5;
      --secondary-color: #8b5cf6;
      --accent-color: #06b6d4;
      --text-dark: #1e293b;
      --text-light: #64748b;
      --text-white: #ffffff;
      --bg-gradient-start: #f8fafc;
      --bg-gradient-end: #e2e8f0;
      --card-bg: #ffffff;
      --border-color: #e2e8f0;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', 'FigTree', sans-serif;
      background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
      min-height: 100vh;
      color: var(--text-dark);
      line-height: 1.6;
      overflow-x: hidden;
    }

    .main-container {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
      padding-top: 84px; /* header height + margin */
    }

    /* Header */
    .header {
      background: var(--card-bg);
      box-shadow: var(--shadow-sm);
      padding: 1rem 0;
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      z-index: 100;
      transition: box-shadow 0.2s;
    }

    .header-content {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo-section {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .logo {
      height: 64px;
      width: auto;
      transition: height 0.2s;
    }

    .brand-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-color);
      letter-spacing: -0.025em;
    }

    .header-actions {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .btn-secondary {
      background: transparent;
      color: var(--text-light);
      border: 1px solid var(--border-color);
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .btn-secondary:hover {
      background: var(--bg-gradient-start);
      color: var(--text-dark);
      border-color: var(--primary-color);
    }

    /* Hero Section */
    .hero {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4rem 2rem;
      position: relative;
    }

    .hero-content {
      max-width: 1200px;
      width: 100%;
      text-align: center;
      position: relative;
      z-index: 2;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: var(--text-white);
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      font-size: 0.875rem;
      font-weight: 500;
      margin-bottom: 2rem;
      box-shadow: var(--shadow-md);
    }

    .hero-title {
      font-size: 3.5rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 1.5rem;
      line-height: 1.1;
      letter-spacing: -0.025em;
    }

    .hero-subtitle {
      font-size: 1.25rem;
      color: var(--text-light);
      margin-bottom: 3rem;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    .hero-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      margin-bottom: 4rem;
      flex-wrap: wrap;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--text-white);
      padding: 1rem 2rem;
      border-radius: 0.75rem;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      box-shadow: var(--shadow-lg);
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-xl);
      background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    }

    .btn-outline {
      background: transparent;
      color: var(--primary-color);
      border: 2px solid var(--primary-color);
      padding: 1rem 2rem;
      border-radius: 0.75rem;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-outline:hover {
      background: var(--primary-color);
      color: var(--text-white);
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    /* Features Section */
    .features {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
      max-width: 1000px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    .feature-card {
      background: var(--card-bg);
      padding: 1.1rem 1rem;
      border-radius: 0.75rem;
      box-shadow: var(--shadow-md);
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
      text-align: left;
      min-width: 0;
    }

    .feature-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-xl);
      border-color: var(--primary-color);
    }

    .feature-icon {
      width: 2.2rem;
      height: 2.2rem;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
      color: var(--text-white);
      font-size: 1rem;
    }

    .feature-title {
      font-size: 1.05rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .feature-description {
      color: var(--text-light);
      line-height: 1.6;
      font-size: 0.92rem;
    }

    /* Decorative Elements */
    .decoration {
      position: absolute;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      opacity: 0.1;
      z-index: 1;
    }

    .decoration-1 {
      width: 300px;
      height: 300px;
      top: 10%;
      left: -150px;
      animation: float 6s ease-in-out infinite;
      background: linear-gradient(rgba(30,41,59,0.18), rgba(30,41,59,0.18)), url('/images/showroom.png') center center/cover no-repeat;
      box-shadow: 0 8px 32px rgba(0,0,0,0.13);
      position: absolute;
      border-radius: 50%;
      opacity: 0.42;
    }

    .decoration-2 {
      width: 200px;
      height: 200px;
      bottom: 20%;
      right: -100px;
      animation: float 8s ease-in-out infinite reverse;
      background: linear-gradient(rgba(30,41,59,0.13), rgba(30,41,59,0.13)), url('/images/manufacturer.png') center center/cover no-repeat;
      box-shadow: 0 8px 32px rgba(0,0,0,0.13);
      position: absolute;
      border-radius: 50%;
      opacity: 0.42;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
      .features {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media (max-width: 768px) {
      .header-content {
        padding: 0 1rem;
        flex-direction: column;
        gap: 1rem;
      }

      .hero {
        padding: 2rem 1rem;
      }

      .hero-title {
        font-size: 2.5rem;
      }

      .hero-subtitle {
        font-size: 1.125rem;
      }

      .hero-buttons {
        flex-direction: column;
        align-items: center;
      }

      .btn-primary,
      .btn-outline {
        width: 100%;
        max-width: 300px;
        justify-content: center;
      }

      .features {
        grid-template-columns: 1fr;
        padding: 0 1rem;
      }
      .feature-card {
        padding: 1rem;
      }
      .decoration {
        display: none;
      }
      .logo {
        height: 48px;
      }
      .main-container {
        padding-top: 68px;
      }
    }

    @media (max-width: 480px) {
      .hero-title {
        font-size: 2rem;
      }

      .hero-subtitle {
        font-size: 1rem;
      }

      .feature-card {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="main-container">
    <!-- Header -->
    <header class="header">
      <div class="header-content">
        <div class="logo-section">
          <img src="/images/logo.png" alt="ChicAura Logo" class="logo">
        </div>
        <div class="header-actions">
          <a href="{{ route('login') }}" class="btn-secondary">Sign In</a>
          <a href="{{ route('register') }}" class="btn-secondary">Get Started</a>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
      <!-- Decorative Elements -->
      <div class="decoration decoration-1"></div>
      <div class="decoration decoration-2"></div>

      <div class="hero-content">
        <div class="hero-badge">
          <i class="fas fa-star"></i>
          <span>Premier Supply Chain Management</span>
        </div>
        
        <h1 class="hero-title">Transform Your Fashion Supply Chain</h1>
        <p class="hero-subtitle">
          Streamline operations, enhance collaboration, and drive growth with our comprehensive 
          supply chain management platform designed specifically for the fashion industry.
        </p>
        
        <div class="hero-buttons">
          <a href="{{ route('login') }}" class="btn-primary">
            <i class="fas fa-sign-in-alt"></i>
            Login to Your Account
          </a>
          <a href="{{ route('register') }}" class="btn-outline">
            <i class="fas fa-rocket"></i>
            Start Your Journey
          </a>
        </div>

        <!-- Features Grid -->
        <div class="features">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-sync-alt"></i>
            </div>
            <h3 class="feature-title">Streamlined Operations</h3>
            <p class="feature-description">
              Manage your entire supply chain from procurement to delivery with our intuitive 
              platform designed for maximum efficiency.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="feature-title">Real-time Analytics</h3>
            <p class="feature-description">
              Get comprehensive insights into your supply chain performance with detailed 
              analytics and actionable reports.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-users"></i>
            </div>
            <h3 class="feature-title">Collaborative Platform</h3>
            <p class="feature-description">
              Connect suppliers, manufacturers, and wholesalers in one unified system 
              for seamless collaboration and communication.
            </p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-tshirt"></i>
            </div>
            <h3 class="feature-title">Fashion-Focused</h3>
            <p class="feature-description">
              Specialized tools for the clothing industry with advanced material tracking, 
              style management, and trend analysis.
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>
</html>
