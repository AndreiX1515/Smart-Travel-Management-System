<?php
session_start();

$regionsPath = '../vendor/jaydoesphp/psgc-php/src/resources/json/regions.json';
$regions = json_decode(file_get_contents($regionsPath), true);

if (!is_array($regions)) {
  exit("Error: Invalid JSON structure in regions.json.");
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>


  <?php include "../Agent Section/includes/sidebar.php"; ?>

  <div class="main-container">
    <div class="navbar">

      <div class="page-header-wrapper">

        <div class="first-half">
          <div class="page-header-top">
            <div class="back-btn-wrapper">
              <button class="back-btn" id="redirect-btn">
                <i class="fas fa-chevron-left"></i>
              </button>
            </div>
          </div>

          <div class="page-header-content">
            <div class="page-header-text">
              <h5 class="header-title">Add Guest</h5>
            </div>
          </div>
        </div>

        <div class="second-half">

        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Agent Section/agent-showGuest.php'; // Replace with your actual URL
      });
    </script>

    <!-- <pre>
      <?php
      // var_dump($regions);
      ?>
</pre> -->


    <div class="main-content">

      <form id="addressForm">
  <!-- Region -->
  <div class="mb-3">
    <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
    <select id="region" name="region" class="form-select" required>
      <option value="">-- Select Region --</option>
      <?php foreach ($regions as $r): ?>
        <option value="<?= htmlspecialchars($r['id']) ?>">
          <?= htmlspecialchars($r['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Province -->
  <div class="mb-3">
    <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
    <select id="province" name="province" class="form-select" required>
      <option value="">-- Select Province --</option>
    </select>
  </div>

  <!-- City/Municipality -->
  <div class="mb-3">
    <label for="citymun" class="form-label">City/Municipality <span class="text-danger">*</span></label>
    <select id="citymun" name="citymun" class="form-select" required>
      <option value="">-- Select City/Municipality --</option>
    </select>
  </div>

  <!-- Barangay -->
  <div class="mb-3">
    <label for="barangay" class="form-label">Barangay <span class="text-danger">*</span></label>
    <select id="barangay" name="barangay" class="form-select" required>
      <option value="">-- Select Barangay --</option>
    </select>
  </div>
</form>







    </div>


  </div>

  <?php require "../Agent Section/includes/scripts.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const regionEl = document.getElementById('region');
  const provinceEl = document.getElementById('province');
  const citymunEl = document.getElementById('citymun');
  const barangayEl = document.getElementById('barangay');

  const basePath = '../vendor/jaydoesphp/psgc-php/src/resources/json/';
  const fetchJSON = path => fetch(basePath + path).then(res => res.json());

  regionEl.addEventListener('change', async () => {
    const regionId = regionEl.value;
    resetSelect(provinceEl, 'Province');
    resetSelect(citymunEl, 'City/Municipality');
    resetSelect(barangayEl, 'Barangay');

    if (!regionId) return;

    const provinces = await fetchJSON('provinces.json');
    const filteredProvinces = provinces.filter(p => p.region_id == regionId);
    populateSelect(provinceEl, filteredProvinces);
  });

  provinceEl.addEventListener('change', async () => {
    const provinceId = provinceEl.value;
    resetSelect(citymunEl, 'City/Municipality');
    resetSelect(barangayEl, 'Barangay');

    if (!provinceId) return;

    const [cities, municipalities] = await Promise.all([
      fetchJSON('cities.json'),
      fetchJSON('municipalities.json')
    ]);

    const combined = [...cities, ...municipalities];
    const filtered = combined.filter(loc => loc.province_id == provinceId);
    populateSelect(citymunEl, filtered);
  });

  citymunEl.addEventListener('change', async () => {
    const citymunId = citymunEl.value;
    resetSelect(barangayEl, 'Barangay');

    if (!citymunId) return;

    const barangays = await fetchJSON('barangays.json');
    const filteredBarangays = barangays.filter(b => b.city_municipality_id == citymunId);
    populateSelect(barangayEl, filteredBarangays);
  });

  function populateSelect(select, data) {
    data.forEach(item => {
      const opt = document.createElement('option');
      opt.value = item.id; // You can change this to item.code if needed
      opt.textContent = item.name;
      select.appendChild(opt);
    });
  }

  function resetSelect(select, label) {
    select.innerHTML = `<option value="">-- Select ${label} --</option>`;
  }
});
</script>














</body>

</html>