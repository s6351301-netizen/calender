<?php
// ==========================================
// 資料區：將各年份與對應的月曆資料寫死為 PHP 陣列
// ==========================================

// 支援的年份與其對應的歲次生肖與主題設定
$years_meta = [
    2020 => ['minchu' => '109', 'ganzhi' => '庚子(鼠)', 'theme' => 'rat', 'animal' => '鼠', 'color' => '#4B6584', 'accent' => '#ff4757', 'bg_color' => '#f1f2f6'],
    2021 => ['minchu' => '110', 'ganzhi' => '辛丑(牛)', 'theme' => 'ox', 'animal' => '牛', 'color' => '#845EC2', 'accent' => '#ff6f91', 'bg_color' => '#f3f0ff'],
    2022 => ['minchu' => '111', 'ganzhi' => '壬寅(虎)', 'theme' => 'tiger', 'animal' => '虎', 'color' => '#D65A31', 'accent' => '#ff5722', 'bg_color' => '#fff5f0'],
    2023 => ['minchu' => '112', 'ganzhi' => '癸卯(兔)', 'theme' => 'rabbit', 'animal' => '兔', 'color' => '#FF6F91', 'accent' => '#ff1493', 'bg_color' => '#fff0f5'],
    2024 => ['minchu' => '113', 'ganzhi' => '甲辰(龍)', 'theme' => 'dragon', 'animal' => '龍', 'color' => '#00818A', 'accent' => '#d63031', 'bg_color' => '#e8f8f9'],
    2025 => ['minchu' => '114', 'ganzhi' => '乙巳(蛇)', 'theme' => 'snake', 'animal' => '蛇', 'color' => '#2E8B57', 'accent' => '#228b22', 'bg_color' => '#f0fff0'],
    2026 => ['minchu' => '115', 'ganzhi' => '丙午(馬)', 'theme' => 'horse', 'animal' => '馬', 'color' => '#C0392B', 'accent' => '#e74c3c', 'bg_color' => '#fdfefe'],
    2027 => ['minchu' => '116', 'ganzhi' => '丁未(羊)', 'theme' => 'goat', 'animal' => '羊', 'color' => '#D4AC0D', 'accent' => '#b7950b', 'bg_color' => '#fef9e7'],
    2028 => ['minchu' => '117', 'ganzhi' => '戊申(猴)', 'theme' => 'monkey', 'animal' => '猴', 'color' => '#D35400', 'accent' => '#e67e22', 'bg_color' => '#fef5e7'],
    2029 => ['minchu' => '118', 'ganzhi' => '己酉(雞)', 'theme' => 'rooster', 'animal' => '雞', 'color' => '#884EA0', 'accent' => '#9b59b6', 'bg_color' => '#f4ecf7'],
    2030 => ['minchu' => '119', 'ganzhi' => '庚戌(狗)', 'theme' => 'dog', 'animal' => '狗', 'color' => '#795548', 'accent' => '#a1887f', 'bg_color' => '#efebe9'],
    2031 => ['minchu' => '120', 'ganzhi' => '辛亥(豬)', 'theme' => 'pig', 'animal' => '豬', 'color' => '#E91E63', 'accent' => '#c2185b', 'bg_color' => '#fce4ec'],
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
    <title>台灣萬年曆黃曆 - 藝術生肖動態版</title>
    <style>
        /* 定義 12 生肖專屬的背景裝飾與主題色彩 */
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

        /* 藝術背景生肖圖騰：浮水印效果，並帶有緩慢旋轉與呼吸燈動畫 */
        .zodiac-bg-art {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(1);
            width: 600px;
            height: 600px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.08;
            z-index: -1;
            animation: bgFloatAndRotate 20s infinite alternate ease-in-out;
            pointer-events: none;
        }

        /* 依據不同生肖動態指定大型浮水印圖案 (使用內嵌 SVG 確保隨開即用) */
        body.theme-rat .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%234B6584' d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z'/%3E%3C/svg%3E"); }
        body.theme-ox .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23845EC2' d='M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12z'/%3E%3C/svg%3E"); }
        body.theme-tiger .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23D65A31' d='M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99z'/%3E%3C/svg%3E"); }
        body.theme-rabbit .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23FF6F91' d='M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z'/%3E%3C/svg%3E"); }
        body.theme-dragon .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%2300818A' d='M12 2l-5.5 9h11L12 2zm0 3.84L15.29 10H8.71L12 5.84zM6 18c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm12 0c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z'/%3E%3C/svg%3E"); }
        body.theme-snake .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%232E8B57' d='M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z'/%3E%3C/svg%3E"); }
        body.theme-horse .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23C0392B' d='M12 2L2 22h20L12 2z'/%3E%3C/svg%3E"); }
        body.theme-goat .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23D4AC0D' d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z'/%3E%3C/svg%3E"); }
        body.theme-monkey .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23D35400' d='M12 2L2 19h20L12 2zm0 3.51L18.33 17H5.67L12 5.51z'/%3E%3C/svg%3E"); }
        body.theme-rooster .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23884EA0' d='M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 14h-2v-4h2v4zm0-6h-2V7h2v3z'/%3E%3C/svg%3E"); }
        body.theme-dog .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23795548' d='M4 6h16v12H4z'/%3E%3C/svg%3E"); }
        body.theme-pig .zodiac-bg-art { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23E91E63' d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z'/%3E%3C/svg%3E"); }

        /* 背景圖案旋轉與縮放動畫 */
        @keyframes bgFloatAndRotate {
            0% {
                transform: translate(-50%, -50%) rotate(0deg) scale(0.95);
            }
            50% {
                transform: translate(-50%, -50%) rotate(180deg) scale(1.05);
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg) scale(0.95);
            }
        }

        /* 整個頁面載入時的進場旋轉與淡入動畫 */
        @keyframes contentEntrance {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.96);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .main-wrapper {
            animation: contentEntrance 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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
            backdrop-filter: blur(5px);
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

    <!-- 藝術背景浮水印（具備動態旋轉與縮放效果） -->
    <div class="zodiac-bg-art"></div>

    <div class="main-wrapper">
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