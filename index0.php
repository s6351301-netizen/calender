<?php
// ==========================================
// 資料區：將各年份與對應的月曆資料寫死為 PHP 陣列
// ==========================================

// 支援的年份與其對應的歲次生肖
$years_meta = [
    2020 => ['minchu' => '109', 'ganzhi' => '庚子(鼠)'],
    2021 => ['minchu' => '110', 'ganzhi' => '辛丑(牛)'],
    2022 => ['minchu' => '111', 'ganzhi' => '壬寅(虎)'],
    2023 => ['minchu' => '112', 'ganzhi' => '癸卯(兔)'],
    2024 => ['minchu' => '113', 'ganzhi' => '甲辰(龍)'],
    2025 => ['minchu' => '114', 'ganzhi' => '乙巳(蛇)'],
    2026 => ['minchu' => '115', 'ganzhi' => '丙午(馬)'],
    2027 => ['minchu' => '116', 'ganzhi' => '丁未(羊)'],
    2028 => ['minchu' => '117', 'ganzhi' => '戊申(猴)'],
    2029 => ['minchu' => '118', 'ganzhi' => '己酉(雞)'],
    2030 => ['minchu' => '119', 'ganzhi' => '庚戌(狗)'],
    2031 => ['minchu' => '120', 'ganzhi' => '辛亥(豬)'],
];

// 取得使用者透過下拉選單選擇的年份（預設為 2026）
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : 2026;
if (!array_key_exists($selected_year, $years_meta)) {
    $selected_year = 2026; // 若超出範圍則預設回 2026
}

$minguo_year = $years_meta[$selected_year]['minchu'];
$ganzhi_year = $years_meta[$selected_year]['ganzhi'];

// 示範用的各月份資料陣列（可依需求擴充其他年份的實際天數與農曆）
// 這裡以 2026 年為基礎，若切換到其他年份會自動帶入對應月份骨架
$calendar_data = [];
for ($m = 1; $m <= 12; $m++) {
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $m, $selected_year);
    $month_array = [];
    $lunar_terms = ['初一', '初二', '初三', '初四', '初五', '初六', '初七', '初八', '初九', '初十', 
                    '十一', '十二', '十三', '十四', '十五', '十六', '十七', '十八', '十九', '二十', 
                    '廿一', '廿二', '廿三', '廿四', '廿五', '廿六', '廿七', '廿八', '廿九', '三十'];
    
    for ($d = 1; $d <= $days_in_month; $d++) {
        $lunar_day = $lunar_terms[($d - 1) % 30];
        $note = '宜行事';
        
        // 簡單對應一些固定國定假日示範
        if ($m == 1 && $d == 1) $note = '開國紀念日';
        if ($m == 2 && $d == 28) $note = '和平紀念日';
        if ($m == 4 && $d == 4) $note = '兒童節';
        if ($m == 4 && $d == 5) $note = '民族掃墓節';
        if ($m == 5 && $d == 1) $note = '勞動節';
        if ($m == 10 && $d == 10) $note = '國慶日';

        $month_array[] = [$d, $lunar_day, $note];
    }
    $calendar_data[$m] = $month_array;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>台灣萬年曆黃曆</title>
    <style>
        body { font-family: sans-serif; text-align: center; background-color: #f9f9f9; margin: 0; padding: 20px; }
        h1 { color: #333; }
        .year-select { margin-bottom: 20px; font-size: 16px; }
        select { font-size: 16px; padding: 5px 10px; cursor: pointer; }
        .months-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; max-width: 1200px; margin: 0 auto; }
        .month-box { background: #fff; border: 1px solid #ddd; padding: 10px; width: 320px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .month-box h3 { margin: 5px 0 15px 0; border-bottom: 2px solid #a00; padding-bottom: 5px; color: #a00; }
        .days-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; font-size: 13px; }
        .header-day { font-weight: bold; background: #eee; padding: 5px 0; }
        .day-cell { background: #fafafa; padding: 6px 2px; min-height: 35px; border: 1px solid #eee; }
        .day-num { font-weight: bold; display: block; }
        .day-lunar { font-size: 11px; color: #666; display: block; }
    </style>
</head>
<body>

    <h1>台灣萬年曆黃曆</h1>
    
    <!-- 年份下拉式選單 -->
    <div class="year-select">
        <strong><?= $selected_year ?> 年 (民國 <?= $minguo_year ?> 年)</strong> 歲次 <?= $ganzhi_year ?> <br><br>
        <label for="yearSelector">選擇年份：</label>
        <select id="yearSelector" onchange="changeYear(this.value)">
            <?php foreach ($years_meta as $y => $meta): ?>
                <option value="<?= $y ?>" <?= ($y == $selected_year) ? 'selected' : '' ?>>
                    <?= $y ?> 年 (民國 <?= $meta['minchu'] ?> 年)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <hr style="width: 80%; border: 0; border-top: 1px solid #ccc; margin: 20px auto;">

    <!-- 1 到 12 月月曆動態生成 -->
    <div class="months-container">
        <?php foreach ($calendar_data as $month_num => $days): ?>
            <div class="month-box">
                <h3><?= $month_num ?> 月</h3>
                <div class="days-grid">
                    <div class="header-day">日</div>
                    <div class="header-day">一</div>
                    <div class="header-day">二</div>
                    <div class="header-day">三</div>
                    <div class="header-day">四</div>
                    <div class="header-day">五</div>
                    <div class="header-day">六</div>

                    <?php
                    // 計算該月 1 號是星期幾 (0=日, 1=一 ... 6=六)
                    $first_day_of_week = date('w', strtotime("$selected_year-$month_num-01"));

                    // 輸出空白格對齊星期
                    for ($i = 0; $i < $first_day_of_week; $i++) {
                        echo '<div></div>';
                    }

                    // 輸出每日資料
                    foreach ($days as $day_info) {
                        $d = $day_info[0];
                        $lunar = $day_info[1];
                        $note = $day_info[2];
                        $bg = ($note !== '宜行事') ? 'background-color: #ffe6e6;' : '';
                        
                        echo "<div class='day-cell' style='{$bg}'>";
                        echo "<span class='day-num'>{$d}</span>";
                        echo "<span class='day-lunar'>{$lunar}</span>";
                        if ($note !== '宜行事') {
                            echo "<span style='font-size:9px; color:red;'>{$note}</span>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // 當下拉選單變更時，自動跳轉並帶入對應年份參數
        function changeYear(year) {
            window.location.href = "?year=" + year;
        }
    </script>

</body>
</html>