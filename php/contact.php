<?php
// Envío SMTP nativo (sin librerías ni servicios externos). Requiere hosting con PHP.
header('Content-Type: application/json; charset=utf-8');
function out($ok){echo json_encode(['ok'=>$ok]);exit;}
if($_SERVER['REQUEST_METHOD']!=='POST'||!empty($_POST['website']))out(false); // honeypot
$clean=fn($s)=>trim(str_replace(["\r","\n"],' ',strip_tags($s??'')));
$name=$clean($_POST['name']??'');$email=trim($_POST['email']??'');
$service=$clean($_POST['service']??'');$msg=trim(strip_tags($_POST['message']??''));
if(!$name||!$msg||!filter_var($email,FILTER_VALIDATE_EMAIL))out(false);
$c=require __DIR__.'/config.php';
$fp=@stream_socket_client($c['host'].':'.$c['port'],$en,$es,15);
if(!$fp)out(false);
$rd=function()use($fp){$r='';while($l=fgets($fp,515)){$r.=$l;if($l[3]===' ')break;}return $r;};
$cmd=function($s,$ok)use($fp,$rd){fwrite($fp,$s."\r\n");return str_starts_with($rd(),$ok);};
$rd();
$subject='=?UTF-8?B?'.base64_encode("Nuevo contacto web: $service").'?=';
$body="Nombre: $name\nCorreo: $email\nServicio: $service\n\n$msg";
$data="From: {$c['name']} <{$c['user']}>\r\nTo: {$c['to']}\r\nReply-To: $name <$email>\r\nSubject: $subject\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode($body));
$ok=$cmd('EHLO '.($_SERVER['SERVER_NAME']??'localhost'),'250')
 && $cmd('AUTH LOGIN','334') && $cmd(base64_encode($c['user']),'334') && $cmd(base64_encode($c['pass']),'235')
 && $cmd("MAIL FROM:<{$c['user']}>",'250') && $cmd("RCPT TO:<{$c['to']}>",'250') && $cmd('DATA','354')
 && $cmd($data."\r\n.",'250');
@fwrite($fp,"QUIT\r\n");fclose($fp);
out($ok);
