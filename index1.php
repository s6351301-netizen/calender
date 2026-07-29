<?php
// ==========================================
// 資料區：將各年份與對應的月曆資料寫死為 PHP 陣列
// ==========================================

// 支援的年份與其對應的歲次生肖與主題代號
$years_meta = [
    2020 => ['minchu' => '109', 'ganzhi' => '庚子(鼠)', 'theme' => 'rat', 'animal' => '鼠'],
    2021 => ['minchu' => '110', 'ganzhi' => '辛丑(牛)', 'theme' => 'ox', 'animal' => '牛'],
    2022 => ['minchu' => '111', 'ganzhi' => '壬寅(虎)', 'theme' => 'tiger', 'animal' => '虎'],
    2023 => ['minchu' => '112', 'ganzhi' => '癸卯(兔)', 'theme' => 'rabbit', 'animal' => '兔'],
    2024 => ['minchu' => '113', 'ganzhi' => '甲辰(龍)', 'theme' => 'dragon', 'animal' => '龍'],
    2025 => ['minchu' => '114', 'ganzhi' => '乙巳(蛇)', 'theme' => 'snake', 'animal' => '蛇'],
    2026 => ['minchu' => '115', 'ganzhi' => '丙午(馬)', 'theme' => 'horse', 'animal' => '馬'],
    2027 => ['minchu' => '116', 'ganzhi' => '丁未(羊)', 'theme' => 'goat', 'animal' => '羊'],
    2028 => ['minchu' => '117', 'ganzhi' => '戊申(猴)', 'theme' => 'monkey', 'animal' => '猴'],
    2029 => ['minchu' => '118', 'ganzhi' => '己酉(雞)', 'theme' => 'rooster', 'animal' => '雞'],
    2030 => ['minchu' => '119', 'ganzhi' => '庚戌(狗)', 'theme' => 'dog', 'animal' => '狗'],
    2031 => ['minchu' => '120', 'ganzhi' => '辛亥(豬)', 'theme' => 'pig', 'animal' => '豬'],
];

// 取得使用者透過下拉選單選擇的年份（預設為 2026）
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : 2026;
if (!array_key_exists($selected_year, $years_meta)) {
    $selected_year = 2026; // 若超出範圍則預設回 2026
}

$minguo_year = $years_meta[$selected_year]['minchu'];
$ganzhi_year = $years_meta[$selected_year]['ganzhi'];
$current_theme = $years_meta[$selected_year]['theme'];
$current_animal = $years_meta[$selected_year]['animal'];

// 示範用的各月份資料陣列
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
    <title>台灣萬年曆黃曆 - 生肖主題版</title>
    <style>
        /* 12 生肖主題色系對照設定 */
        body.theme-rat { --primary-color: #4B6584; --bg-gradient: #f1f2f6; --box-border: #a4b0be; --highlight: #ff4757; }
        body.theme-ox { --primary-color: #845EC2; --bg-gradient: #f3f0ff; --box-border: #d6bcf9; --highlight: #ff6f91; }
        body.theme-tiger { --primary-color: #D65A31; --bg-gradient: #fff5f0; --box-border: #f8b195; --highlight: #ff5722; }
        body.theme-rabbit { --primary-color: #FF6F91; --bg-gradient: #fff0f5; --box-border: #ffb6c1; --highlight: #ff1493; }
        body.theme-dragon { --primary-color: #00818A; --bg-gradient: #e8f8f9; --box-border: #8fd3de; --highlight: #d63031; }
        body.theme-snake { --primary-color: #2E8B57; --bg-gradient: #f0fff0; --box-border: #8fbc8f; --highlight: #228b22; }
        body.theme-horse { --primary-color: #C0392B; --bg-gradient: #fdfefe; --box-border: #e6b0aa; --highlight: #e74c3c; }
        body.theme-goat { --primary-color: #D4AC0D; --bg-gradient: #fef9e7; --box-border: #f9e79f; --highlight: #b7950b; }
        body.theme-monkey { --primary-color: #D35400; --bg-gradient: #fef5e7; --box-border: #f5b041; --highlight: #e67e22; }
        body.theme-rooster { --primary-color: #884EA0; --bg-gradient: #f4ecf7; --box-border: #d7bde2; --highlight: #9b59b6; }
        body.theme-dog { --primary-color: #795548; --bg-gradient: #efebe9; --box-border: #bcaaa4; --highlight: #a1887f; }
        body.theme-pig { --primary-color: #E91E63; --bg-gradient: #fce4ec; --box-border: #f8bbd0; --highlight: #c2185b; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            background-color: var(--bg-gradient, #f9f9f9);
            margin: 0;
            padding: 20px;
            transition: background-color 0.5s ease;
        }

        /* 畫面載入與切換時的淡入與縮放動畫 */
        @keyframes pageTransition {
            0% {
                opacity: 0;
                transform: translateY(15px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-container {
            animation: pageTransition 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        h1 {
            color: var(--primary-color, #333);
            transition: color 0.5s ease;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        .year-select {
            margin-bottom: 20px;
            font-size: 16px;
            color: #444;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            background: var(--primary-color);
            color: #fff;
            border-radius: 20px;
            font-size: 15px;
            margin-left: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background 0.5s ease;
        }

        select {
            font-size: 16px;
            padding: 6px 14px;
            cursor: pointer;
            border-radius: 6px;
            border: 2px solid var(--primary-color);
            background: #fff;
            color: #333;
            outline: none;
            transition: all 0.3s ease;
        }
        select:hover {
            background-color: #fafafa;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .months-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .month-box {
            background: #fff;
            border: 1px solid var(--box-border, #ddd);
            padding: 12px;
            width: 320px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.5s ease;
        }
        .month-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .month-box h3 {
            margin: 5px 0 15px 0;
            border-bottom: 2px solid var(--primary-color, #a00);
            padding-bottom: 5px;
            color: var(--primary-color, #a00);
            transition: color 0.5s ease, border-color 0.5s ease;
        }

        .days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
            font-size: 13px;
        }

        .header-day {
            font-weight: bold;
            background: #f1f2f6;
            padding: 6px 0;
            border-radius: 4px;
            color: #555;
        }

        .day-cell {
            background: #fafafa;
            padding: 6px 2px;
            min-height: 38px;
            border: 1px solid #eee;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .day-cell:hover {
            background: #f0f0f0;
        }

        .day-num {
            font-weight: bold;
            display: block;
            color: #222;
        }

        .day-lunar {
            font-size: 11px;
            color: #666;
            display: block;
        }

        .holiday-note {
            font-size: 9px;
            color: #fff;
            background-color: var(--highlight, #ff4757);
            border-radius: 3px;
            padding: 1px 3px;
            display: inline-block;
            margin-top: 2px;
        }
    </style>
</head>
<body class="theme-<?= $current_theme ?>">

    <div class="animate-container">
        <h1>台灣萬年曆黃曆 - <?= $current_animal ?>年主題</h1>
        
        <!-- 年份下拉式選單 -->
        <div class="year-select">
            <strong><?= $selected_year ?> 年 (民國 <?= $minguo_year ?> 年)</strong> 
            <span class="badge">歲次 <?= $ganzhi_year ?></span> <br><br>
            <label for="yearSelector">選擇年份：</label>
            <select id="yearSelector" onchange="changeYear(this.value)">
                <?php foreach ($years_meta as $y => $meta): ?>
                    <option value="<?= $y ?>" <?= ($y == $selected_year) ? 'selected' : '' ?>>
                        <?= $y ?> 年 (民國 <?= $meta['minchu'] ?> 年 - <?= $meta['ganzhi'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <hr style="width: 80%; border: 0; border-top: 1px solid var(--box-border); margin: 20px auto; transition: border-color 0.5s ease;">

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
                            $is_holiday = ($note !== '宜行事');
                            
                            $cell_style = $is_holiday ? 'background-color: var(--bg-gradient); border-color: var(--box-border);' : '';
                            
                            echo "<div class='day-cell' style='{$cell_style}'>";
                            echo "<span class='day-num'>{$d}</span>";
                            echo "<span class='day-lunar'>{$lunar}</span>";
                            if ($is_holiday) {
                                echo "<span class='holiday-note'>{$note}</span>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // 當下拉選單變更時，自動跳轉並帶入對應年份參數
        function changeYear(year) {
            window.location.href = "?year=" + year;
        }
    </script>

</body>
</html>