<?php
# vim: set tabstop=2 shiftwidth=2 expandtab:
$bot_name = "quadnite_bot";
$bot_api = require('api_key.php');

// Checks whether the given command is the same as the entered command
function check_command($command) {
  global $bot_name;
  global $decoded;
  $command_list = explode(" ", $decoded->{"message"}->{"text"});
  if ($command_list[0] == $command || $command_list[0] == $command . "@" . $bot_name) {
    return True;
  }
  else {
    return False;
  }
}

// Send code back to the sender.
function send_code($post_message, $reply=false) {
  global $decoded;
  global $bot_api;
  global $chat_id;
  $url = 'https://api.telegram.org/bot' . $bot_api . '/sendMessage';
  $post_msg = array('chat_id' => $chat_id, 'text' => '```\n ' . $post_message . '```', 'parse_mode' => 'markdown' );
  if ($reply != false) {
    if ($reply === true){
      $post_msg['reply_to_message_id'] = $decoded->{'message'}->{'message_id'};
    }
    else {
      $post_msg['reply_to_message_id'] = $reply;
    }
  }
  $options = array(
    'http' => array(
      'header' => "Content-type: application/x-www-form-urlencoded\r\n",
      'method' => 'POST',
      'content' => http_build_query($post_msg)
    )
  );
  $context = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
}

// Send text back to the sender.
function send_text($post_message, $reply=false) {
  global $decoded;
  global $bot_api;
  global $chat_id;
  $url = 'https://api.telegram.org/bot' . $bot_api . '/sendMessage';
  $post_msg = array('chat_id' => $chat_id, 'text' =>$post_message );
  if ($reply != false) {
    if ($reply === true){
      $post_msg['reply_to_message_id'] = $decoded->{'message'}->{'message_id'};
    }
    else {
      $post_msg['reply_to_message_id'] = $reply;
    }
  }
  $options = array(
    'http' => array(
      'header' => "Content-type: application/x-www-form-urlencoded\r\n",
      'method' => 'POST',
      'content' => http_build_query($post_msg)
    )
  );
  $context = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
}

// Send html back to the sender.
function send_html($post_message, $reply=false) {
  global $decoded;
  global $bot_api;
  global $chat_id;
  $url = 'https://api.telegram.org/bot' . $bot_api . '/sendMessage';
  $post_msg = array('chat_id' => $chat_id, 'text' =>$post_message, 'parse_mode' => 'html');
  if ($reply != false) {
    if ($reply === true){
      $post_msg['reply_to_message_id'] = $decoded->{'message'}->{'message_id'};
    }
    else {
      $post_msg['reply_to_message_id'] = $reply;
    }
  }
  $options = array(
    'http' => array(
      'header' => "Content-type: application/x-www-form-urlencoded\r\n",
      'method' => 'POST',
      'content' => http_build_query($post_msg)
    )
  );
  $context = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
}

// Send gif back to the sender.
function send_gif($gif_url, $reply=false) {
  global $decoded;
  global $bot_api;
  global $chat_id;
  $url = 'https://api.telegram.org/bot' . $bot_api . '/sendAnimation';
  $post_msg = array('chat_id' => $chat_id, 'animation' => $gif_url );
  if ($reply != false) {
    if ($reply === true){
      $post_msg['reply_to_message_id'] = $decoded->{'message'}->{'message_id'};
    }
    else {
      $post_msg['reply_to_message_id'] = $reply;
    }
  }
  $options = array(
    'http' => array(
      'header' => "Content-type: application/x-www-form-urlencoded\r\n",
      'method' => 'POST',
      'content' => http_build_query($post_msg)
    )
  );
  $context = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  return $result;
}

function forward_message($to_chat_id) {
  global $decoded;
  global $bot_api;
  global $chat_id;
  $message_id = $decoded->{"message"}->{"message_id"};
  $url = 'https://api.telegram.org/bot' . $bot_api . '/forwardMessage';
  $post_msg = array('chat_id' => $to_chat_id, 'message_id' => $message_id, 'from_chat_id' => $chat_id);
  $options = array(
    'http' => array(
      'header' => "Content-type: application/x-www-form-urlencoded\r\n",
      'method' => 'POST',
      'content' => http_build_query($post_msg)
    )
  );
  $context = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
}

// Generates random words
function rand_words($onewordmode) {
  global $command_list;
  if($onewordmode == 1){
    $num = 1;
  }
  else {
    if (isset($command_list[1])) {
      $num = $command_list[1];
    }
    else {
      $num = 10;
    }
  }
  $num++;
  $words = array();
  if (is_integer($num)) {
    if ($num > 50) {
      send_text("Too many words.", true);
      return;
    }
    $wordlist = file("/usr/share/dict/words");
    for ($word=1; $word < $num; $word++) {
      $words[] = $wordlist[rand(0,count($wordlist))];
    }
    send_text(implode(' ', $words));
  }
  else {
    send_text("Ever heard of numbers?", true);
  }
}

function rand_question() {
  $questions = file('rand_questions.txt');
  $question = $questions[rand(0,count($questions))];
  send_text($question);
}

function media_wiki($base_url) {
  global $command_list;
  $search_query = "";
  for ($i=1; $i < count($command_list); $i++) {
    $search_query .= $command_list[$i];
    if ($i < count($command_list) - 1) {
      $search_query .= " ";
    }
  }
  if (preg_match('/^\s*$/', $search_query)) {
    send_text('Missing search query');
    return;
  }
  $url = $base_url . "?action=opensearch&format=json&search=" . urlencode($search_query);
  $a = json_decode(file_get_contents($url));
  $names = $a[1];
  $urls = $a[3];
  if (count($names) == 0) {
    send_text("No result found", true);
    return false;
  }
  $text = "Results\n";
  for ($i = 0 ; $i < count($names) ; $i++){
    $text .= "<a href='" . $urls[$i] . "'>" . $names[$i] . "</a>\n";
  }
  send_html($text);
}

function coin() {
  $random = rand(0,1);
  if ($random == 1) {
    send_text('Heads', true);
  }
  else {
    send_text('Tails', true);
  }
}

function get_gif($force) {
  if ($force){
    $param = "yes";
  }
  else {
    $param = "no";
  }
  $url = "https://yesno.wtf/api?force=" . $param;
  $result = file_get_contents($url);
  $json = json_decode($result);
  $image_url = $json->{"image"};
  return $image_url;
}

function yes_or_no() {
  global $command_list;
  if (!isset($command_list[1])){
    send_text('You know, you also have to ask the question.', true);
    return false;
  }
  $yes_replies = array("Yes", "Yep", "Yeah", "Yus", "Ja", "Ya", "Aye", "Ay", "Oui");
  $no_replies = array("No", "Nopes", "Nu", "Nah", "Nein", "Naw", "Nay", "Yesn't");
  $random = rand(0,1);
  $random2 = rand(0, 10);
  if ($random == 1) {
    $yes = $yes_replies[array_rand($yes_replies)];
    send_text($yes, true);
    if ($random2 == 5){
      send_gif(get_gif(True));
    }
  }
  else {
    $no = $no_replies[array_rand($no_replies)];
    send_text($no, true);
    if ($random2 == 33){
      send_gif(get_gif(False));
    }
  }
}

// Kill yourself
function kys() {
  global $decoded;
  global $bot_name;
  global $command_list;
  $kys = file('kys.txt');
  $random_kys = $kys[rand(0,count($kys)-1)];
  if ($decoded->{'message'}->{'reply_to_message'}->{'from'}->{'username'} == $bot_name){
    send_text("I can't be killed.", true);
    return;
  }
  if (isset($decoded->{'message'}->{'reply_to_message'})) {
    if (isset($decoded->{'message'}->{'reply_to_message'}->{'from'}->{'username'})){
      $username = '@' . $decoded->{'message'}->{'reply_to_message'}->{'from'}->{'username'};
      $random_kys = preg_replace('/##name##/', $username, $random_kys);
    }
    else {
      $first_name = $decoded->{'message'}->{'reply_to_message'}->{'from'}->{'first_name'};
      $random_kys = preg_replace('/##name##/', $first_name, $random_kys);
    }
    send_text($random_kys);
  }
  elseif (isset($command_list[1])){
    $username = $command_list[1];
    if ($username == "@quadnite_bot" || $username == "Quadnite" || $username == "quadnite"){
      send_text("I can't be killed.", true);
    }
    else {
      $random_kys = preg_replace('/##name##/', $username, $random_kys);
      send_text($random_kys);
    }
  }
  else {
    send_text("Do you want to kill yourself?\nIf no, reply to someone with /kys to kill them or run /kys username/name.\nYou can suggest more /kys replies using /feedback", true);
  }
}

// Insult
function send_insult() {
  global $decoded;
  global $bot_name;
  global $command_list;
  $insults = file('insults.txt');
  $random_insult = $insults[rand(0,count($insults)-1)];
  if ($decoded->{'message'}->{'reply_to_message'}->{'from'}->{'username'} == $bot_name){
    send_text("Watch who you talk to.", true);
    return;
  }
  if (isset($decoded->{'message'}->{'reply_to_message'})) {
    if (isset($decoded->{'message'}->{'reply_to_message'}->{'from'}->{'username'})){
      $username = '@' . $decoded->{'message'}->{'reply_to_message'}->{'from'}->{'username'};
      $random_insult = preg_replace('/##name##/', $username, $random_insult);
    }
    else {
      $first_name = $decoded->{'message'}->{'reply_to_message'}->{'from'}->{'first_name'};
      $random_insult = preg_replace('/##name##/', $first_name, $random_insult);
    }
    send_text($random_insult);
  }
  elseif (isset($command_list[1])){
    $username = $command_list[1];
    if ($username == "@quadnite_bot" || $username == "Quadnite" || $username == "quadnite"){
      send_text("Watch who you talk to.", true);
    }
    else {
      $random_insult = preg_replace('/##name##/', $username, $random_insult);
      send_text($random_insult);
    }
  }
  else {
    send_text("Do you want to insult yourself?\nIf no, reply to someone with /insult to insult them or run /insult username/name.\nYou can suggest more /kys replies using /feedback", true);
  }
}

function feedback(){
  global $command_list;
  if (isset($command_list[1])){
    forward_message(-1001168939936);
    send_text("Thank you for the feedback");
  }
  else {
    send_text("To send feedback type in /feedback followed by the feedback", true);
  }
}

// Sends back JSON
function json() {
  global $var;
  $pretty_json = json_encode(json_decode($var), JSON_PRETTY_PRINT);
  send_text($pretty_json);
}
// Start Message
function start() {
  send_text('Hi, I am Quadnite. If you are chatting with me in private, you are most likely doing it wrong. Add me to a group for fun. To give feedback, use /feedback');
}

function rate() {
  send_html('<a href="https://t.me/tgdrbot?start=quadnite_bot">Vote for me on Telegram Directory!</a>');
}

function help() {
  send_html('You can either check /commands for a short overview or check the <a href="https://t.me/quadnite/9">Help Page</a>.');
}

function commands() {
  send_text(file_get_contents('command_list.txt'));
}

function weebify() {
  global $decoded;
  global $command_list;
  if(count($command_list) <= 1){
    send_text("Need text to weebify. Send /weebify text", true);
    return;
  }
  $letters = array(
    "a" => "卂",
    "b" => "乃",
    "c" => "匚",
    "d" => "刀",
    "e" => "乇",
    "f" => "下",
    "g" => "厶",
    "h" => "卄",
    "i" => "工",
    "j" => "丁",
    "k" => "长",
    "l" => "乚",
    "m" => "从",
    "n" => "𠘨",
    "o" => "口",
    "p" => "尸",
    "q" => "㔿",
    "r" => "尺",
    "s" => "丂",
    "t" => "丅",
    "u" => "凵",
    "v" => "リ",
    "w" => "山",
    "x" => "乂",
    "y" => "丫",
    "z" => "乙"
  );
  $chars = str_split(preg_replace('/^\/[^ ]+ /', '', strtolower($decoded->{"message"}->{"text"})));
  $text = "";
  foreach($chars as $char){
    if(key_exists($char, $letters)) {
      $text .= $letters[$char];
    }
    else {
      $text .= $char;
    }
  }
  send_text($text, true);
}

// Get JSON from post, store it and decode it.
$var = file_get_contents('php://input');
$decoded = json_decode($var);

// Store the chat ID
$chat_id = $decoded->{"message"}->{"chat"}->{"id"};

$modules = array(
  array(
    "command" => "/start",
    "function" => "start();"
  ),
  array(
    "command" => "/word",
    "function" => "rand_words(1);"
  ),
  array(
    "command" => "/words",
    "function" => "rand_words(0);"
  ),
  array(
    "command" => "/question",
    "function" => "rand_question();"
  ),
  array(
    "command" => "/arch_wiki",
    "function" => "media_wiki('https://wiki.archlinux.org/api.php');"
  ),
  array(
    "command" => "/wiki",
    "function" => "media_wiki('https://en.wikipedia.org/w/api.php');"
  ),
  array(
    "command" => "/coin",
    "function" => "coin();"
  ),
  array(
    "command" => "/is",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/are",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/can",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/will",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/shall",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/was",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/does",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/did",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/should",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/do",
    "function" => "yes_or_no();"
  ),
  array(
    "command" => "/kys",
    "function" => "kys();"
  ),
  array(
    "command" => "/json",
    "function" => "json();"
  ),
  array(
    "command" => "/help",
    "function" => "help();"
  ),
  array(
    "command" => "/insult",
    "function" => "send_insult();"
  ),
  array(
    "command" => "/feedback",
    "function" => "feedback();"
  ),
  array(
    "command" => "/rate",
    "function" => "rate();"
  ),
  array(
    "command" => "/commands",
    "function" => "commands();"
  ),
  array(
    "command" => "/weebify",
    "function" => "weebify();"
  )
);

if (!isset($decoded->{"message"}->{"text"})){
  exit();
}

if (isset($decoded->{"message"}->{"pinned_message"})){
  exit();
}

$command_list = explode(" ", $decoded->{"message"}->{"text"});

foreach ($modules as $module ) {
  if (check_command($module["command"])) {
    eval($module["function"]);
    exit();
  }
}
?>
