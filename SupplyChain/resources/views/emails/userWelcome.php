<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registration Confirmation</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="padding: 24px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
          <tr>
            <td style="background: linear-gradient(to right, #155dfc, #00a63e); padding: 24px;">
              <h1 style="color:#ffffff; font-size:24px; font-weight:800; margin:0;">Welcome to ChicAura</h1>
            </td>
          </tr>
          <tr>
            <td style="padding: 24px;">
              <p style="color:#374151; font-size:16px; margin-bottom:16px;">Hello <strong>{{ $name }}</strong>,</p>
              <p style="color:#374151; font-size:16px; margin-bottom:24px;">
               We are delighted to officially welcome you to <strong>ChicAura</strong> following your recent facility visit, 
               your profile has been successfully registered in our system, and you now have full access to our platform.
              </p>
              <div style="background-color:#eff6ff; border-left:4px solid #60a5fa; padding:16px; margin-bottom:24px;">
                <p style="color:#1e40af; margin:0;">
                  <strong style="color:#1e3a8a; font-size:18px;">Your Login Credentials:</strong><br>
                  <strong>Username:</strong> <span style="color:#1e3a8a;">{{ $email }}</span><br>
                  <strong>Password:</strong> <span style="color:#1e3a8a;">{{ $password }}</span><br>

                </p>
              </div>
              <p style="color:#374151; font-size:16px; margin-bottom:24px;">
                We are excited to have you on board and look forward to your contributions. 
                Welcome once again to the <strong>ChicAura</strong> family!
                
                <br><br><br>
                Warm regards,<br>
                Kirabo Eria<br>
                CEO<br>
                ChicAura
              </p>
              <hr style="border:none; border-top:1px solid #e5e7eb; margin:32px 0;">
              <p style="font-size:14px; color:#6b7280;">
                If you have any questions, feel free to reply to this email or contact us at
                <a href="mailto:chicaura.ug@gmail.com" style="color:#3b82f6; text-decoration:underline;">chicaura.ug@gmail.com</a>.
              </p>
            </td>
          </tr>
          <tr>
            <td style="background-color:#f9fafb; text-align:center; padding:16px;">
              <p style="font-size:12px; color:#9ca3af; margin:0;">&copy; 2025 ChicAura. All rights reserved.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>

