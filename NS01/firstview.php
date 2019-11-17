<!-- view1
スタートボタンのみ
ページ番号をPOST送信
押した時間1をデータベースにインサート -->

<h3>【無音条件01】計算問題</h3>

<p>今から計算問題を解いてもらいます。全問解き終わった時点で終了です。<br>
  以下注意事項になります。</p>
<ul>
  <li>合図が出るまで始めてはいけません</li>
  <li>出来る限り正確に問題を解いてください</li>
  <li>戻るボタンを使用してはいけません</li>
  <li>全部の問題が終了したら手をあげてください</li>
</ul>
<form action="question.php" method="post">
  <input type="hidden" name="time"
    value="<?php echo '現在時刻は：'.date('Y/m/d H:i:s'); ?>"
    size="60">
  <input type="hidden" name="pagenum" value="0">
  <input type="submit" value="問題を開始する">
</form>