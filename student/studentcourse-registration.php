<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Course Registration with Payment QR Code and PDF Receipt</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <style>
    /* Modern clean styling */
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background-color: #f9fafb;
      color: #374151;
      line-height: 1.5;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 32px 16px;
    }
    header {
      max-width: 900px;
      width: 100%;
      padding-bottom: 24px;
      border-bottom: 1px solid #e5e7eb;
      margin-bottom: 32px;
    }
    header h1 {
      font-weight: 700;
      font-size: 2rem;
      color: #111827;
    }
    main {
      max-width: 900px;
      width: 100%;
      background: #fff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      border-radius: 12px;
      padding: 32px;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #4b5563;
    }
    input, select {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 1rem;
      margin-bottom: 24px;
      transition: border-color 0.2s ease;
    }
    input:focus, select:focus {
      border-color: #6366f1;
      outline-offset: 2px;
      outline: 2px solid #c7d2fe;
    }
    button {
      background: linear-gradient(135deg, #4f46e5, #3b82f6);
      color: #fff;
      font-weight: 700;
      font-size: 1rem;
      padding: 14px 24px;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      box-shadow: 0 8px 15px rgba(59,130,246,0.3);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      user-select: none;
      transition: background 0.3s ease, transform 0.15s ease;
    }
    button:focus-visible {
      outline-offset: 3px;
      outline: 3px solid #a5b4fc;
    }
    button:hover:not(:disabled) {
      background: linear-gradient(135deg, #4338ca, #2563eb);
      transform: translateY(-2px);
    }
    button:active:not(:disabled) {
      transform: translateY(0);
    }
    button:disabled {
      background: #a5b4fc;
      cursor: not-allowed;
      box-shadow: none;
      transform: none;
    }
    .material-icons {
      font-size: 20px;
      vertical-align: middle;
    }
    .message {
      margin-top: 16px;
      padding: 16px;
      background: #d1fae5;
      border-left: 4px solid #22c55e;
      color: #065f46;
      border-radius: 8px;
      font-weight: 600;
    }
    .error-message {
      background: #fee2e2;
      color: #b91c1c;
      border-left-color: #dc2626;
    }
    #qr-code-container {
      margin-top: -12px;
      margin-bottom: 40px;
      text-align: center;
      user-select: none;
    }
    #qr-code-container p {
      color: #6b7280;
      font-size: 0.9rem;
      margin-top: 8px;
      margin-bottom: 0;
    }
    @media (max-width: 600px) {
      main {
        padding: 24px 16px;
      }
      button {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Student Course Registration</h1>
  </header>
  <main>
    <form id="registrationForm" aria-label="Student course registration form">
      <label for="regNo">Registration Number</label>
      <input 
        type="text" 
        id="regNo" 
        name="regNo" 
        required 
        pattern="^[A-Za-z0-9\-]+$" 
        title="Alphanumeric characters, dashes allowed" 
        aria-describedby="regNoHelp"
        autocomplete="off"
      />
      <small id="regNoHelp" style="color:#6b7280; display:block; margin-bottom:20px;">Your unique registration number provided by Admin.</small>

      <label for="studentName">Student Name</label>
      <input 
        type="text" 
        id="studentName" 
        name="studentName" 
        required 
        pattern="^[a-zA-Z\s]+$" 
        title="Only letters and spaces allowed" 
        autocomplete="off"
      />

      <label for="courseSelect">Select Course</label>
      <select id="courseSelect" name="courseSelect" required>
        <option value="" disabled selected>Select a course</option>
        <option value="CS101">CS101 - Introduction to Computer Science</option>
        <option value="MA202">MA202 - Calculus and Linear Algebra</option>
        <option value="PH303">PH303 - Modern Physics</option>
        <option value="EN404">EN404 - Advanced English Literature</option>
      </select>

      <label for="paymentStatus">Payment Status</label>
      <select id="paymentStatus" name="paymentStatus" required aria-describedby="qrDesc">
        <option value="" disabled selected>Select payment status</option>
        <option value="pending">Pending - Scan QR code to pay</option>
        <option value="paid">Paid</option>
      </select>

      <div id="qr-code-container" aria-live="polite" aria-atomic="true" aria-describedby="qrDesc" style="display:none;">
        <div id="qr-code"></div>
        <p id="qrDesc">Scan the QR code above to proceed with payment.</p>
      </div>

      <button type="submit" id="submitBtn" aria-live="polite" aria-atomic="true">
        Register and Generate PDF
        <span class="material-icons" aria-hidden="true">article</span>
      </button>
    </form>

    <div id="message" role="alert" class="message" style="display:none;"></div>
  </main>

  <!-- jsPDF Library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <!-- QRCode library (QRCode.js) -->
  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

  <script>
    (function() {
      const { jsPDF } = window.jspdf;
      const form = document.getElementById('registrationForm');
      const messageDiv = document.getElementById('message');
      const submitBtn = document.getElementById('submitBtn');
      const paymentStatus = document.getElementById('paymentStatus');
      const qrCodeContainer = document.getElementById('qr-code-container');
      const qrCodeDiv = document.getElementById('qr-code');

      // Placeholder payment URL (could be your real payment gateway link)
      function getPaymentQRCodeText(regNo, courseCode) {
        // For demo, we encode some payment info as a URL string
        return `https://payment.example.com/pay?reg=${encodeURIComponent(regNo)}&course=${encodeURIComponent(courseCode)}`;
      }

      // Render QR code for payment
      function renderQRCode(text) {
        qrCodeDiv.innerHTML = ''; // Clear old QR
        QRCode.toCanvas(qrCodeDiv, text, { width: 180, margin: 1 }, function (error) {
          if (error) {
            console.error(error);
            qrCodeContainer.style.display = 'none';
            return;
          }
          qrCodeContainer.style.display = 'block';
        });
      }

      // Hide QR code initially
      qrCodeContainer.style.display = 'none';

      // Change QR code visibility based on paymentStatus select value
      paymentStatus.addEventListener('change', () => {
        const status = paymentStatus.value;
        if (status === 'pending') {
          const regNo = form.regNo.value.trim();
          const courseCode = form.courseSelect.value;

          if (regNo && courseCode) {
            const qrText = getPaymentQRCodeText(regNo, courseCode);
            renderQRCode(qrText);
          } else {
            // Missing required data to generate QR - hide
            qrCodeContainer.style.display = 'none';
          }
        } else {
          qrCodeContainer.style.display = 'none';
        }
      });

      // Also update QR code when regNo or courseSelect changes if paymentStatus is pending
      form.regNo.addEventListener('input', () => {
        if(paymentStatus.value === 'pending') {
          const regNo = form.regNo.value.trim();
          const courseCode = form.courseSelect.value;
          if(regNo && courseCode) {
            renderQRCode(getPaymentQRCodeText(regNo, courseCode));
          } else {
            qrCodeContainer.style.display = 'none';
          }
        }
      });

      form.courseSelect.addEventListener('change', () => {
        if(paymentStatus.value === 'pending') {
          const regNo = form.regNo.value.trim();
          const courseCode = form.courseSelect.value;
          if(regNo && courseCode) {
            renderQRCode(getPaymentQRCodeText(regNo, courseCode));
          } else {
            qrCodeContainer.style.display = 'none';
          }
        }
      });

      // Submission handler with PDF generation and payment check
      form.addEventListener('submit', function(event) {
        event.preventDefault();
        messageDiv.style.display = 'none';

        const regNo = form.regNo.value.trim();
        const studentName = form.studentName.value.trim();
        const courseCode = form.courseSelect.value;
        const courseName = form.courseSelect.options[form.courseSelect.selectedIndex].text.split(' - ')[1];
        const paymentStat = paymentStatus.value;

        // Payment must be completed
        if(paymentStat !== 'paid') {
          messageDiv.textContent = 'Payment must be completed to register and generate PDF. Please scan the QR code and complete payment.';
          messageDiv.className = 'message error-message';
          messageDiv.style.display = 'block';
          return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Generating PDF...';

        // Build PDF content
        const doc = new jsPDF({
          unit: 'pt',
          format: 'a4'
        });

        const pageWidth = doc.internal.pageSize.getWidth();

        doc.setFontSize(20);
        doc.setTextColor('#3b82f6');
        doc.text('Course Registration Receipt', pageWidth/2, 60, { align: 'center' });

        doc.setDrawColor('#3b82f6');
        doc.setLineWidth(1);
        doc.line(40, 75, pageWidth - 40, 75);

        doc.setFontSize(12);
        doc.setTextColor('#374151');
        doc.text(`Date: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}`, 40, 110);

        doc.setFontSize(14);
        doc.setTextColor('#111827');
        doc.text('Student Details:', 40, 150);

        doc.setFontSize(12);
        doc.setTextColor('#374151');
        doc.text(`Registration Number: ${regNo}`, 60, 175);
        doc.text(`Name: ${studentName}`, 60, 195);

        doc.setFontSize(14);
        doc.setTextColor('#111827');
        doc.text('Course Details:', 40, 230);

        doc.setFontSize(12);
        doc.setTextColor('#374151');
        doc.text(`Course Code: ${courseCode}`, 60, 255);
        doc.text(`Course Name: ${courseName}`, 60, 275);

        doc.setFontSize(12);
        doc.setTextColor('#16a34a');
        doc.text('Payment Status: Paid', 60, 310);

        // Footer
        doc.setFontSize(10);
        doc.setTextColor('#6b7280');
        doc.text('Thank you for registering. Please keep this receipt for your records.', pageWidth/2, 770, { align: 'center' });

        const filename = `${regNo}_course_registration_receipt.pdf`;
        doc.save(filename);

        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Register and Generate PDF <span class="material-icons" aria-hidden="true">article</span>';

        messageDiv.textContent = 'Registration successful! PDF has been downloaded.';
        messageDiv.className = 'message';
        messageDiv.style.display = 'block';

        form.reset();
        qrCodeContainer.style.display = 'none';
      });
    })();
  </script>
</body>
</html>

