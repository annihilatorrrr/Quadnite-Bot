<?php
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

// Returns Insults
function get_insults($username) {
  global $decoded;
  if ($decoded->{"message"}->{"from"}->{"id"} == 394312580){
    return "Sorry master, I am unable to do that.";
  }
  else {
    $insults = file('insults.txt');
    return $insults[rand(0,count($insults)-1)];
  }
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
    $wordlist = file("/usr/share/dict/words");
    for ($word=1; $word < $num; $word++) {
      $words[] = $wordlist[rand(0,count($wordlist))];
    }
    send_text(implode(' ', $words));
  }
  else {
    send_text(get_insults());
  }
}

function rand_question()
{
  $questions = file('rand_questions.txt');
  $question = $questions[rand(0,count($questions))];
  send_text($question);
}

function media_wiki($base_url)
{
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

function coin()
{
   $random = rand(0,1);
   if ($random == 1) {
      send_text('Heads', true);
   }
   else {
      send_text('Tails', true);
   }
}

function yes_or_no()
{
  global $command_list;
  if (!isset($command_list[1])){
    send_text('You know, you also have to ask the question.', true);
    return false;
  }
   $random = rand(0,1);
   if ($random == 1) {
      send_text('Yes', true);
   }
   else {
      send_text('No', true);
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
    send_text("Do you want to kill yourself?\n\nIf no, reply to someone with /kys to kill them.", true);
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
  send_text('Hi, I am Quadnite. If you are chatting with me in private, you are most likely doing it wrong. Add me to a group for fun. Do not promote me to an admin or I WILL hate you.');
}

function help() {
  send_html('<a href="https://t.me/quadnite/9">Help Page</a>');
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

// If message is a reply, exit
if (isset($decoded->{"message"}->{"reply_to_message"})) {
  exit();
}
?>
