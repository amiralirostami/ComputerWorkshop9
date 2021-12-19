<?php
$messages = explode("\n", file_get_contents('./messages.txt'));
$answers = json_decode(file_get_contents('./answers.json'), true);
$options = file_get_contents('people.json');
$options = json_decode($options, true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $en_name = $_POST['person'];
    echo $en_name;
    $fa_name = $options[$en_name];
    if (!str_starts_with($question, 'آیا') || (!str_ends_with($question, '?') && !str_ends_with($question, '؟'))) {
        $msg = 'سوال درستی پرسیده نشده';
    } else {
        if (array_key_exists($en_name, $answers)) {
            if (array_key_exists($question, $answers[$en_name])) {
                $msg = $answers[$en_name][$question];
            } else {
                $msg = $messages[array_rand($messages)];
                $answers[$en_name][$question] = $msg;
            }
        } else {
            $msg = $messages[array_rand($messages)];
            $answers[$en_name] = [];
            $answers[$en_name][$question] = $msg;
        }
    }
    file_put_contents('./answers.json', json_encode($answers));
} else {
    $data = '';
    file_put_contents('./answers.json', json_encode([]));
    $question = '...';
    $msg = 'سوال خود را بپرس';
    $en_name = array_rand($options);
    $fa_name = $options[$en_name];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label">پرسش:</span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php
                $option_values = array_keys($options);
                for ($i = 0; $i < count($options); $i++) { ?>
                    <option value="<?php echo $option_values[$i] ?>"
                    <?php  if ($option_values[$i] == $en_name) echo "selected" ?>><?php echo $options[$option_values[$i]] ?></option>
                <?php
                }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>