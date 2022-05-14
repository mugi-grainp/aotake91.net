<!DOCTYPE html>
<html lang="ja">
<head>
    <title>西鉄車両運用一覧（2014年3月22日改正：平日ダイヤ）</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,user-scalable=yes">
    <link rel="stylesheet" type="text/css" href="../iphone.css" media="only screen and (max-width: 640px)">
    <link rel="stylesheet" type="text/css" href="../desktop.css" media="screen and (min-width: 641px)">
    <script src="../jquery-2.1.1.min.js"></script>
    <script src="../sk_uicode.js"></script>
</head>
<body>

<header>
    <h1><a href=".">西鉄車両運用一覧</a></h1>
</header>

<?php
    // 初期化部
    $train_type = array(
        "普通"     => array("普通", "LOCAL", ""),
        "急行"     => array("急行", "EXPRESS", " express"),
        "特急"     => array("特急", "LTD. EXP.", " ltdexp"),
        "ワンマン" => array("ﾜﾝﾏﾝ", ""),
        "回送"     => array("回　　送", "OUT OF SERVICE", " outofservice")
    );
    
    $destination = array(
        "福岡(天神)"   => array("福岡<span class=\"tenjin\">(天神)", "FUKUOKA <span class=\"tenjin\">(TENJIN)"),
        "二日市"       => array("二日市", "FUTSUKAICHI"),
        "太宰府"       => array("太宰府", "DAZAIFU"),
        "筑紫"         => array("筑　紫", "CHIKUSHI"),
        "小郡"         => array("小　郡", "OGORI"),
        "久留米"       => array("久留米", "KURUME"),
        "花畑"         => array("花　畑", "HANABATAKE"),
        "津福"         => array("津　福", "TSUBUKU"),
        "大善寺"       => array("大善寺", "DAIZENJi"),
        "柳川"         => array("柳　川", "YANAGAWA"),
        "大牟田"       => array("大牟田", "OMUTA"),
        "本郷"         => array("本　郷", "HONGOU"),
        "甘木"         => array("甘　木", "AMAGI")
    );
    
    // 一覧ファイル読み込み
    $fp = fopen("trainlist.txt", "r");
    while(!feof($fp)) {
        $formation_code = rtrim(fgets($fp));   // 1行目：運用コード
        $formation_title = rtrim(fgets($fp));  // 2行目：運用名称
        $car_type = rtrim(fgets($fp));         // 3行目：充当形式両数
?>


<div id="<?php echo $formation_code ?>">
    <div class="formation_controlinfo">
        <h2 class="title"><?php echo $formation_title ?></h2>
        <p><?php echo $car_type ?></p>
    </div>
    <div class="trainlist">
        <table>
        <colgroup span="1" class="base">
        <colgroup span="1" class="base">
        <colgroup span="2" class="typedest">
        <colgroup span="1" class="base">
        
<?php
        // ファイル4行目～：CSV形式
        // 列車番号,種別,始発駅,始発時刻,終着駅,終着時刻,備考
        $buf = fgetcsv($fp);
        
        while($buf[0] != null) {
            if ($buf[0] == "回送") {
                echo <<<ROW2
        <tr><td rowspan="2">$buf[1]</td><td rowspan="2">$buf[2]<br>$buf[3]</td><td colspan="2" class="type outofservice">{$train_type[$buf[0]][0]}</td><td rowspan="2">$buf[4]<br>$buf[5]</td></tr>
        <tr class="roman_dest"><td colspan="2" class="type{$train_type[$buf[0]][2]}">{$train_type[$buf[0]][1]}</td></tr>\n
ROW2;

            } else {
                if (preg_match('/(.+)\[(.+)\]/' ,$buf[4], $matches) === 0) { 
                    $matches[1] = $buf[4];
                    $matches[2] = $buf[4];
                }
                echo <<<ROW
        <tr><td rowspan="2">$buf[1]</td><td rowspan="2">$buf[2]<br>$buf[3]</td><td class="type{$train_type[$buf[0]][2]}">{$train_type[$buf[0]][0]}</td><td class="dest">{$destination[$matches[2]][0]}</td><td rowspan="2">$matches[1]<br>$buf[5]</td></tr>
        <tr class="roman_dest"><td class="type{$train_type[$buf[0]][2]}">{$train_type[$buf[0]][1]}</td><td class="dest">{$destination[$buf[4]][1]}</td></tr>\n
ROW;
            }
            
            if ($buf[6] != "") {
                echo "        <tr class=\"memo\"><td>▲備考</td><td colspan=\"4\">$buf[6]</td></tr>\n";
            }
            
        // ワンマン運用処理時のecho
        //    echo <<<ROW_ONEMAN
        // <tr><td rowspan="2">$buf[1]</td><td rowspan="2">$buf[2]<br>$buf[3]</td><td rowspan="2" class="type oneman">ﾜﾝﾏﾝ</td><td class="dest">{$destination[$buf[4]][0]}</td><td rowspan="2">$buf[4]<br>$buf[5]</td></tr>
        // <tr class="roman_dest"><td class="dest">{$destination[$buf[4]][1]}</span></td></tr>
// ROW_ONEMAN;
            $buf = fgetcsv($fp);
        }
?>
        </table>
    </div>
</div>
<?php
    }
    fclose($fp);
?>
</body>
</html>
