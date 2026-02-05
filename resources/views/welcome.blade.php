<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Image Point LocalStorage</title>
<style>
  body {
    font-family: sans-serif;
  }

  #image-container {
    position: relative;
    display: inline-block;
    max-width: 600px;
  }

  #myImage {
    width: 100%;
    display: block;
  }

  .point {
    position: absolute;
    width: 12px;
    height: 12px;
    background: red;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    cursor: pointer;
  }

  .tooltip {
    position: absolute;
    background: black;
    color: white;
    padding: 5px;
    border-radius: 3px;
    display: none;
    pointer-events: none;
    z-index: 10;
  }

  button {
    margin-top: 10px;
  }
</style>
</head>
<body>

<h2>Klik gambar untuk membuat titik</h2>

<div id="image-container">
  <img id="myImage" src="https://picsum.photos/800/500" />
</div>

<br/>
<button onclick="clearPoints()">Hapus Semua Titik</button>

<script>
const img = document.getElementById("myImage");
const container = document.getElementById("image-container");
const STORAGE_KEY = "image_points_demo";

const tooltip = document.createElement('div');
tooltip.className = 'tooltip';
container.appendChild(tooltip);

// Ambil data dari localStorage saat load
window.onload = function () {
  loadPoints();
};

// Klik gambar
img.addEventListener("click", function (e) {
  const rect = img.getBoundingClientRect();

  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;

  const xPercent = x / rect.width;
  const yPercent = y / rect.height;

  drawPoint(xPercent, yPercent);
  savePoint(xPercent, yPercent);
});

// Gambar titik
function drawPoint(xPercent, yPercent) {
  const point = document.createElement("div");
  point.className = "point";

  point.style.left = (xPercent * 100) + "%";
  point.style.top = (yPercent * 100) + "%";

  point.dataset.x = xPercent;
  point.dataset.y = yPercent;

  point.addEventListener('mouseover', function(e) {
    tooltip.style.display = 'block';
    tooltip.style.left = (e.clientX + 10) + 'px';
    tooltip.style.top = (e.clientY + 10) + 'px';
    tooltip.textContent = `X: ${(this.dataset.x * 100).toFixed(2)}%, Y: ${(this.dataset.y * 100).toFixed(2)}%`;
  });

  point.addEventListener('mouseout', function() {
    tooltip.style.display = 'none';
  });

  container.appendChild(point);
}

// Simpan ke localStorage
function savePoint(x, y) {
  const points = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
  points.push({ x, y });
  localStorage.setItem(STORAGE_KEY, JSON.stringify(points));
}

// Load titik dari localStorage
function loadPoints() {
  const points = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
  points.forEach(p => drawPoint(p.x, p.y));
}

// Hapus semua titik
function clearPoints() {
  localStorage.removeItem(STORAGE_KEY);
  location.reload();
}
</script>

</body>
</html>
