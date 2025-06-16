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
      --primary-color: #4983ff;
      --primary-dark: #4edaf3;
      --text-color: #061c5a;
      --bg-overlay-color: rgba(158, 216, 243, 0.7);
      --container-bg-start: rgba(186, 218, 240, 0.98);
      --container-bg-end: rgba(172, 222, 245, 0.98);
      --shadow-color: rgba(0, 0, 0, 0.2);
    }

    body {
      font-family: 'Open Sans', sans-serif;
      background-image: url('/images/showroom.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      margin: 0;
      padding: 0;
      color: var(--text-color);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      overflow: hidden;
    }

    .container {
      background-image: linear-gradient(145deg, rgba(255,255,255,0.8), rgba(0,0,0,0.8)), url('/images/manequin.jpeg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      max-width: 650px;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 15px 35px var(--shadow-color);
      text-align: center;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInContainer 1s ease-out forwards;
      animation-delay: 0.2s;
      border: 1px solid rgba(220, 220, 220, 0.7);
    }

    h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 3.5em;
      color: var(--primary-color);
      margin-bottom: 25px;
      line-height: 1.1;
      opacity: 0;
      animation: fadeInContent 1s ease-out forwards;
      animation-delay: 0.8s;
    }

    .remark-text {
      font-size: 1.3em;
      line-height: 1.5;
      opacity: 0;
      transition: opacity 1s ease-in-out;
      position: absolute;
      left: 0;
      right: 0;
    }

    .remark-text.active {
      opacity: 1;
      position: relative;
    }

    .remarks {
      position: relative;
      height: 60px;
      margin-bottom: 35px;
    }

    .btn {
      display: inline-block;
      background-color: var(--primary-color);
      color: white;
      padding: 15px 30px;
      margin: 0 15px;
      text-decoration: none;
      border-radius: 30px;
      font-size: 1.1em;
      font-weight: 600;
      transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
      opacity: 0;
      animation: fadeInContent 1s ease-out forwards;
      animation-delay: 1.3s;
    }

    .btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-3px);
      box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
    }

    @keyframes fadeInContainer {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInContent {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .container {
        margin: 50px 20px;
        padding: 30px;
      }

      h1 {
        font-size: 2.5em;
      }

      .remark-text {
        font-size: 1em;
      }

      .btn {
        display: block;
        margin: 15px auto;
        width: 80%;
        max-width: 250px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome to Chicaura SCM System</h1>
    <a href="{{ route('login') }}" class="btn">Login to Your Account</a>
    <a href="{{ route('register') }}" class="btn">Start Your Journey</a>
    <div class="remarks">
      <p class="remark-text active">Your one-stop solution for efficient supply chain management.</p>
      <p class="remark-text">Manage your supply chain with ease, from procurement to delivery.</p>
      <p class="remark-text">Join us today and streamline your operations!</p>
    </div>
  </div>


  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const remarks = document.querySelectorAll('.remark-text');
      let index = 0;

      setInterval(() => {
        remarks[index].classList.remove('active');
        index = (index + 1) % remarks.length;
        remarks[index].classList.add('active');
      }, 3000);
    });
  </script>
</body>
</html>
