/**
 * QR code display - uses API or fallback placeholder
 * For full QR generation install endroid/qr-code and use PHP to output image, or use a CDN lib here
 */
function renderQR(container, data) {
    if (!container || !data) return;
    // Option 1: Use a free QR API (no PHP lib needed)
    var size = 200;
    var url = 'https://api.qrserver.com/v1/create-qr-code/?size=' + size + 'x' + size + '&data=' + encodeURIComponent(data);
    var img = document.createElement('img');
    img.src = url;
    img.alt = 'QR Code';
    img.width = size;
    img.height = size;
    container.innerHTML = '';
    container.appendChild(img);
}
