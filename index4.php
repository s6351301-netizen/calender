<?php
// ==========================================
// 資料區：將各年份與對應的月曆資料寫死為 PHP 陣列
// ==========================================

// 支援的年份與其對應的歲次生肖與主題設定
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

$meta = $years_meta[$selected_year];
$current_theme = $meta['theme'];
$current_animal = $meta['animal'];
$minguo_year = $meta['minchu'];
$ganzhi_year = $meta['ganzhi'];

// 產生各月份月曆資料
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
    <title>台灣萬年曆黃曆 - 生肖浮水印藝術版</title>
    <style>
        /* 12 生肖專屬主題配色 */
        body.theme-rat { --main-color: #4B6584; --accent-color: #ff4757; --bg-color: #f1f2f6; }
        body.theme-ox { --main-color: #845EC2; --accent-color: #ff6f91; --bg-color: #f3f0ff; }
        body.theme-tiger { --main-color: #D65A31; --accent-color: #ff5722; --bg-color: #fff5f0; }
        body.theme-rabbit { --main-color: #FF6F91; --accent-color: #ff1493; --bg-color: #fff0f5; }
        body.theme-dragon { --main-color: #00818A; --accent-color: #d63031; --bg-color: #e8f8f9; }
        body.theme-snake { --main-color: #2E8B57; --accent-color: #228b22; --bg-color: #f0fff0; }
        body.theme-horse { --main-color: #C0392B; --accent-color: #e74c3c; --bg-color: #fdfefe; }
        body.theme-goat { --main-color: #D4AC0D; --accent-color: #b7950b; --bg-color: #fef9e7; }
        body.theme-monkey { --main-color: #D35400; --accent-color: #e67e22; --bg-color: #fef5e7; }
        body.theme-rooster { --main-color: #884EA0; --accent-color: #9b59b6; --bg-color: #f4ecf7; }
        body.theme-dog { --main-color: #795548; --accent-color: #a1887f; --bg-color: #efebe9; }
        body.theme-pig { --main-color: #E91E63; --accent-color: #c2185b; --bg-color: #fce4ec; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            background-color: var(--bg-color, #f9f9f9);
            margin: 0;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            transition: background-color 0.6s ease;
        }

        /* 藝術背景生肖大型浮水印：具備緩慢旋轉與呼吸縮放動畫 */
        .zodiac-bg-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 550px;
            height: 550px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.08;
            z-index: -1;
            pointer-events: none;
            animation: watermarkAnim 25s infinite alternate ease-in-out;
        }

        /* 12 生肖專屬背景文字或圖形浮水印（使用大型藝術化文字 SVG 呈現該生肖名稱） */
        body.theme-rat .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%234B6584'%3E鼠%3C/text%3E%3C/svg%3E"); }
        body.theme-ox .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23845EC2'%3E牛%3C/text%3E%3C/svg%3E"); }
        body.theme-tiger .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23D65A31'%3E虎%3C/text%3E%3C/svg%3E"); }
        body.theme-rabbit .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23FF6F91'%3E兔%3C/text%3E%3C/svg%3E"); }
        body.theme-dragon .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%2300818A'%3E龍%3C/text%3E%3C/svg%3E"); }
        body.theme-snake .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%232E8B57'%3E蛇%3C/text%3E%3C/svg%3E"); }
        body.theme-horse .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23C0392B'%3E馬%3C/text%3E%3C/svg%3E"); }
        body.theme-goat .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23D4AC0D'%3E羊%3C/text%3E%3C/svg%3E"); }
        body.theme-monkey .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23D35400'%3E猴%3C/text%3E%3C/svg%3E"); }
        body.theme-rooster .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23884EA0'%3E雞%3C/text%3E%3C/svg%3E"); }
        body.theme-dog .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23795548'%3E狗%3C/text%3E%3C/svg%3E"); }
        body.theme-pig .zodiac-bg-watermark { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='220' fill='%23E91E63'%3E豬%3C/text%3E%3C/svg%3E"); }

        /* 背景浮水印旋轉與縮放動畫特效 */
        @keyframes watermarkAnim {
            0% {
                transform: translate(-50%, -50%) rotate(-8deg) scale(0.95);
            }
            50% {
                transform: translate(-50%, -50%) rotate(8deg) scale(1.08);
            }
            100% {
                transform: translate(-50%, -50%) rotate(-8deg) scale(0.95);
            }
        }

        /* 整個網頁載入時的進場流暢動畫 */
        @keyframes pageEntrance {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .main-container {
            animation: pageEntrance 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        h1 {
            color: var(--main-color, #333);
            transition: color 0.5s ease;
            font-size: 2.2rem;
            margin-bottom: 5px;
        }

        .year-select {
            margin-bottom: 25px;
            font-size: 16px;
            color: #444;
        }

        .zodiac-badge {
            display: inline-block;
            padding: 5px 14px;
            background: var(--main-color);
            color: #fff;
            border-radius: 20px;
            font-size: 15px;
            font-weight: bold;
            margin-left: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            transition: background 0.5s ease;
        }

        select {
            font-size: 16px;
            padding: 6px 14px;
            cursor: pointer;
            border-radius: 6px;
            border: 2px solid var(--main-color);
            background: #fff;
            color: #333;
            outline: none;
            transition: all 0.3s ease;
        }
        select:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
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
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 320px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .month-box:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
        }

        .month-box h3 {
            margin: 5px 0 15px 0;
            border-bottom: 2px solid var(--main-color);
            padding-bottom: 6px;
            color: var(--main-color);
            font-size: 1.2rem;
            transition: color 0.5s ease, border-color 0.5s ease;
        }

        .days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            font-size: 13px;
        }

        .header-day {
            font-weight: bold;
            background: rgba(0, 0, 0, 0.04);
            padding: 6px 0;
            border-radius: 4px;
            color: #555;
        }

        .day-cell {
            background: #fafafa;
            padding: 6px 2px;
            min-height: 40px;
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
            background-color: var(--accent-color);
            border-radius: 3px;
            padding: 1px 4px;
            display: inline-block;
            margin-top: 2px;
        }
    </style>
</head>
<body class="theme-<?= $current_theme ?>">

    <!-- 背景生肖大型藝術浮水印（隨年份連動、自動緩慢旋轉與縮放） -->
    <div class="zodiac-bg-watermark"></div>

    <div class="main-container">
        <h1>台灣萬年曆黃曆 - <?= $current_animal ?>年主題</h1>
        
        <!-- 年份下拉式選單 -->
        <div class="year-select">
            <strong><?= $selected_year ?> 年 (民國 <?= $minguo_year ?> 年)</strong> 
            <span class="zodiac-badge">歲次 <?= $ganzhi_year ?></span> <br><br>
            <label for="yearSelector">選擇年份：</label>
            <select id="yearSelector" onchange="changeYear(this.value)">
                <?php foreach ($years_meta as $y => $meta): ?>
                    <option value="<?= $y ?>" <?= ($y == $selected_year) ? 'selected' : '' ?>>
                        <?= $y ?> 年 (民國 <?= $meta['minchu'] ?> 年 - <?= $meta['ganzhi'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <hr style="width: 80%; border: 0; border-top: 1px solid rgba(0,0,0,0.15); margin: 25px auto;">

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
                            
                            $cell_style = $is_holiday ? 'background-color: rgba(255,0,0,0.03); border-color: var(--accent-color);' : '';
                            
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