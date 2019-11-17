<!-- 1問ごとにかかった時間を計算し、timediffテーブルにINSERTしていく
課題が終わったことを伝えるメッセージを入れる -->

<!-- データベースへの接続 -->
<?php
  $dsn = 'mysql:dbname=FM01;host=localhost';
  $user = 'root';
  $password='';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->query('SET NAMES utf8');
?>

<!-- ボタンが押された時間を取得する -->
<?php
$dif_sql = "SELECT * FROM `pushtime`";
    $dif_stmt = $dbh->query($dif_sql);
    foreach ($dif_stmt as $result_time) {
        $time[] = $result_time["pushtime"];
    }
?>

<!-- 正解不正解の数を計算する -->
<?php
$a=0;
$b=0;
$result_sql = "SELECT * FROM `results`";
    $result_stmt = $dbh->query($result_sql);
    foreach ($result_stmt as $result) {
        $result_ans[] = $result["result"];
        if ($result["result"] === "正解") {
            $a++;
        } else {
            $b++;
        }
    }
?>

<?php
//時分秒の差を返す関数
 function time_diff($d1, $d2)
 {

//初期化
     $diffTime = array();

     //タイムスタンプ
     $timeStamp1 = strtotime($d1);
     $timeStamp2 = strtotime($d2);

     //タイムスタンプの差を計算
     $difSeconds = $timeStamp2 - $timeStamp1;

     $diffTime['all'] = $difSeconds;

     //秒の差を取得
     $diffTime['seconds'] = $difSeconds % 60;

     //分の差を取得
     $difMinutes = ($difSeconds - ($difSeconds % 60)) / 60;
     $diffTime['minutes'] = $difMinutes % 60;

     //時の差を取得
     $difHours = ($difMinutes - ($difMinutes % 60)) / 60;
     $diffTime['hours'] = $difHours;

     //結果を返す
     return $diffTime;
 }
?>

<!-- 結果の出力 -->
<?php
// 全て解き終わるのにかかった時間の計算
//差を求める日時
$dateTimeFirst = $time[0];
$dateTimeLast = $time[53];
//初期化
$ElapsedTime = array();

//関数実行
$ElapsedTime = time_diff($dateTimeFirst, $dateTimeLast);

//日時の差を表示
echo "【結果】<br>";
echo "正解数：".$a."/54<br>不正解数：".$b."<br>";
echo "全て解き終わるまでの時間：".$ElapsedTime['hours'].'時間'.$ElapsedTime['minutes'].'分'.$ElapsedTime['seconds'].'秒<br/>';
for ($j=1; $j<=53;) {
    $i=$j-1;
    //差を求める日時
    $dateTime1 = $time[$i];
    $dateTime2 = $time[$j];
    //初期化
    $diffTimeOutPut = array();

    //関数実行
    $diffTimeOutPut = time_diff($dateTime1, $dateTime2);

    //日時の差を表示
    echo "【".$j."問目でかかった時間】"."<br>";
    echo $diffTimeOutPut['hours'].'時間'.$diffTimeOutPut['minutes'].'分'.$diffTimeOutPut['seconds'].'秒<br/>';

    // LAPタイム（秒）のINSERT
    $lap_sql = "INSERT INTO `lap` SET `lap`=?";
    $lap_data = array($diffTimeOutPut['all']);
    $lap_stmt = $dbh->prepare($lap_sql);
    $lap_stmt->execute($lap_data);

    $j++;
}
