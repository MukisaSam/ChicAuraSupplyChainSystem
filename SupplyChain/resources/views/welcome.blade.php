<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Chicaura SCM System</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #2563eb;
      --primary-dark: #1d4ed8;
      --text-color: #1f2937;
      --text-light: #ffffff;
      --bg-overlay-color: rgba(158, 216, 243, 0.7);
      --container-bg-start: rgba(255, 255, 255, 0.95);
      --container-bg-end: rgba(255, 255, 255, 0.95);
      --shadow-color: rgba(0, 0, 0, 0.15);
    }

    body {
      font-family: 'Open Sans', sans-serif;
      background-image: url('/images/showroom.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      margin: 0;
      padding: 20px;
      color: var(--text-color);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background: rgba(255, 255, 255, 0.1);
      max-width: 90vw;
      max-height: 90vh;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 20px 40px var(--shadow-color);
      text-align: center;
      border: 2px solid rgba(37, 99, 235, 0.2);
      backdrop-filter: blur(10px);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .logo-container {
      position: relative;
      margin-bottom: 20px;
      opacity: 1;
      transform: scale(1);
      animation: logoAppear 1.5s ease-out forwards;
      display: inline-block;
      background-image: url('/images/silk.jpeg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding: 18px 32px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(37, 99, 235, 0.1);
      z-index: 1;
    }
    .logo-container::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(255,255,255,0.7); /* White overlay for contrast */
      border-radius: 12px;
      z-index: 2;
    }
    .logo {
      position: relative;
      z-index: 3;
      max-width: 120px;
      height: auto;
      filter: none;
      border-radius: 0;
      display: block;
      margin: 0 auto;
    }

    h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 2.5em;
      color: var(--text-light);
      margin-bottom: 10px;
      line-height: 1.1;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      /* Static - no animation */
    }

    .subtitle {
      font-size: 1.1em;
      color: var(--text-light);
      margin-bottom: 25px;
      font-weight: 500;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
      /* Static - no animation */
    }

    .buttons-container {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    .btn {
      display: inline-block;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      color: var(--text-light);
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 50px;
      font-size: 0.95em;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
      border: 2px solid rgba(255, 255, 255, 0.2);
      opacity: 0;
      transform: translateY(30px);
      animation: slideInButton 0.8s ease-out forwards;
    }

    .btn.login {
      animation-delay: 0.5s;
    }

    .btn.register {
      animation-delay: 0.8s;
    }

    .btn:hover {
      background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(37, 99, 235, 0.4);
      border-color: rgba(255, 255, 255, 0.4);
    }

    .features {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      margin-top: 20px;
      opacity: 0;
      animation: fadeInFeatures 1s ease-out forwards;
      animation-delay: 1.2s;
      width: 100%;
      max-width: 600px;
    }

    .feature {
      background: rgba(255, 255, 255, 0.1);
      padding: 15px;
      border-radius: 15px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(5px);
    }

    .feature h3 {
      color: var(--text-light);
      margin-bottom: 8px;
      font-size: 0.95em;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .feature p {
      color: var(--text-light);
      font-size: 0.8em;
      margin: 0;
      line-height: 1.3;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    @keyframes logoAppear {
      0% {
        opacity: 0;
        transform: scale(0.8) rotate(-5deg);
      }
      50% {
        opacity: 0.8;
        transform: scale(1.1) rotate(2deg);
      }
      100% {
        opacity: 1;
        transform: scale(1) rotate(0deg);
      }
    }

    @keyframes slideInButton {
      0% {
        opacity: 0;
        transform: translateY(30px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInFeatures {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      body {
        padding: 10px;
      }

      .container {
        margin: 0;
        padding: 20px;
        max-width: 95vw;
        max-height: 95vh;
      }

      .logo {
        max-width: 60px;
      }

      .logo-container {
        padding: 8px 15px;
        margin-bottom: 15px;
      }

      h1 {
        font-size: 2em;
        margin-bottom: 8px;
      }

      .subtitle {
        font-size: 1em;
        margin-bottom: 20px;
      }

      .buttons-container {
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
      }

      .btn {
        width: 100%;
        max-width: 250px;
        margin: 0;
        padding: 10px 20px;
        font-size: 0.9em;
      }

      .features {
        grid-template-columns: 1fr;
        gap: 10px;
        margin-top: 15px;
        max-width: 100%;
      }

      .feature {
        padding: 12px;
      }

      .feature h3 {
        font-size: 0.9em;
        margin-bottom: 6px;
      }

      .feature p {
        font-size: 0.75em;
        line-height: 1.2;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-container">
      <img src="/images/CA-WORD.png" alt="ChicAura Logo" class="logo">
    </div>
    
    <h1>Welcome to ChicAura</h1>
    <p class="subtitle">Your Premier Supply Chain Management System</p>
    
    <div class="buttons-container">
      <a href="{{ route('login') }}" class="btn login">Login to Your Account</a>
      <a href="{{ route('register') }}" class="btn register">Start Your Journey</a>
    </div>

    <div class="features">
      <div class="feature">
        <h3>🔄 Streamlined Operations</h3>
        <p>Manage your entire supply chain from procurement to delivery with ease</p>
      </div>
      <div class="feature">
        <h3>📊 Real-time Analytics</h3>
        <p>Get insights into your supply chain performance with detailed analytics</p>
      </div>
      <div class="feature">
        <h3>🤝 Collaborative Platform</h3>
        <p>Connect suppliers, manufacturers, and wholesalers in one unified system</p>
      </div>
      <div class="feature">
        <h3>👗 Fashion-Focused</h3>
        <p>Specialized tools for clothing industry with material tracking and style management</p>
      </div>
    </div>
  </div>
</body>
</html>
