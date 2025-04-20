<?php
$data = file_get_contents("https://lax.lv/nordpool2.json");
$nordpool = json_decode($data, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nordpoolins</title>
    <link rel="stylesheet" href="nordpool.css">
    
</head>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<body>

<div class="title">
    <h1>Elektrishon Inc.</h1>
</div>

<div class="container">



<div class="box1">
   <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
   <script>
  
   const jsonData = <?php echo json_encode($nordpool['byPriceToday'] ?? []); ?>;

  
   const xValues = Object.keys(jsonData);
   const yValues = Object.values(jsonData).map(value => parseFloat(value) || 0);

  
   new Chart("myChart", {
   type: "line",
   data: {
      labels: xValues,
      datasets: [{
         label: "Prices Today",
         data: yValues,
         borderColor: "darkgray",
         fill: false
      }]
   },
   options: {
      legend: {
         display: true,
         labels: {
            fontColor: "black" 
         }
      },
      scales: {
         xAxes: [{
            scaleLabel: {
               display: true,
               labelString: "Laiks",
               fontColor: "black" 
            },
            ticks: {
               fontColor: "black"
            }
         }],
         yAxes: [{
            scaleLabel: {
               display: true,
               labelString: "Cenas",
               fontColor: "black" 
            },
            ticks: {
               fontColor: "black"
            }
         }]
      }
   }
});
   </script>
</div>

   <div class="box2">
   <label for="sortDropdown">Kārtot:</label>
   <select id="sortDropdown" onchange="sortTable(this.value)">
      <option value="value-asc">Cenas: Low to High</option>
      <option value="value-desc">Cenas: High to Low</option>
      <option value="key-asc">Laiks: Low to High</option>
      <option value="key-desc">Laiks: High to Low</option>
   </select>
   <table id="priceTable" border="1" style="width:100%; text-align:left;">
      <thead>
         <tr>
            <th class="vertibas">Laiks</th>
            <th class="vertibas">Cena [eur] </th>
         </tr>
      </thead>
      <tbody>
         <?php
         if (!empty($nordpool) && isset($nordpool['byPriceToday'])) {
            foreach ($nordpool['byPriceToday'] as $key => $value) {
               echo "<tr>";
               echo "<td>" . htmlspecialchars($key) . "</td>";
               echo "<td>" . htmlspecialchars(is_array($value) ? json_encode($value) : $value) . "</td>";
               echo "</tr>";
            }
         } else {
            echo "<tr><td colspan='2'>No data available for byPriceToday</td></tr>";
         }
         ?>
      </tbody>
   </table>
</div>

<script>
function sortTable(option) {
   const table = document.getElementById("priceTable").getElementsByTagName("tbody")[0];
   const rows = Array.from(table.rows);

   rows.sort((a, b) => {
      let compareA, compareB;

      if (option.includes("value")) {
         
         compareA = parseFloat(a.cells[1].textContent) || 0;
         compareB = parseFloat(b.cells[1].textContent) || 0;
      } else if (option.includes("key")) {
         
         compareA = a.cells[0].textContent.toLowerCase();
         compareB = b.cells[0].textContent.toLowerCase();
      }

      if (option.endsWith("asc")) {
         return compareA > compareB ? 1 : -1;
      } else {
         return compareA < compareB ? 1 : -1;
      }
   });

   rows.forEach(row => table.appendChild(row));
}
</script>

</div>   



</body>
</html>
