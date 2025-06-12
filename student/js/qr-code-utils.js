function renderQRCode(text) {
  const qrImg = document.getElementById('qr-code-img');
  QRCode.toDataURL(text, { width: 180, margin: 1 }, function(err, url) {
    if (err) {
      console.error(err);
      qrImg.src = '';
      document.getElementById('qr-code-container').style.display = 'none';
      return;
    }
    qrImg.src = url;
    document.getElementById('qr-code-container').style.display = 'block';
  });
}
