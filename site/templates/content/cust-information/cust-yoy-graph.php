<?php 
    $nbrOfYears  = $config->ciYoyYearsback;
    $currentYear = intval(date('Y'));
    $yearsKeys = [];

    for ($i = ($currentYear - $nbrOfYears); $i <= $currentYear; $i++) {
        $yearsKeys[] = "$i";
    }
    $colors = ["#054CB0","#F61067","#70234F","#00AF54"];
?>
<h2>Year over Year Sales</h2>

<div id="yoy-chart"></div>

<script>
    let yoyData = <?= json_encode($custYoyData->toArray()); ?>;
    let yoyYearsKeys = <?= json_encode($yearsKeys); ?>;
    let yoyColors = <?= json_encode($colors); ?>;
</script>