<?php
error_reporting(0);
define("APIKEY", "PASTEKAN_DISINI_APIKEY_NYA"); // Get Api -> https://panel.apiwha.com/
function color($color = "default" , $text){
	$arrayColor = array(
		'grey' 		=> '1;30',
		'red' 		=> '1;31',
		'green' 	=> '1;32',
		'yellow' 	=> '1;33',
		'blue' 		=> '1;34',
		'purple' 	=> '1;35',
		'nevy' 		=> '1;36',
		'white' 	=> '1;0',
	);	
	echo "\033[".$arrayColor[$color]."m".$text."\033[0m";
}
function memuat(){
	$get = ambil_pesan();
	if (!$get) {
		color("red"," [Failed]"); 
		color("white","Connecttion Timeout\n");
	}
	foreach ($get as $data) {
	$dari = $data['from'];
	$pesan = utf8_decode($data['text']);
	$dikirim = $data['creation_date'];
	$type = $data['type'];
	$penerima = $data['to'];
		if($type=="IN"){
		color("green"," [Dikirim ({$dikirim})] Pesan : {$pesan} Dari {$dari}\n");
		} else {
		color("purple"," [Terkirim ({$dikirim})] Pesan : {$pesan} Ke {$penerima}\n");
		}
	}
}
function kirim_pesan(){
	color("nevy"," Nomor Tujuan (62): ");
	$nomor = trim(fgets(STDIN));
	color("nevy"," Pesan : ");
	$pesan = trim(fgets(STDIN));
	$data = array(
		"apikey" => APIKEY,
		"number" => $nomor,
		"text" => $pesan
	);
	$respon = json_decode(file_get_contents("http://panel.apiwha.com/send_message.php?".http_build_query($data)),1);
	if(!$respon){
		color("red"," [Failed]"); 
		color("white","Connecttion Timeout\n");
	}
	$pesanx = $respon['description'];
	if($respon['result_code']==0){
		color("green"," [Sukses] {$pesanx} \n");
	} else {
		color("red"," [Gagal] {$pesanx}\n");
	}
}
function help(){
	color("while"," Cara menggunakan -> \n    --show ( Menampilkan Pesan )\n    --kirim ( Kirim Pesan )\n    --exit ( Keluar )\n");
}
function banner(){
	$kernel = php_uname();
	color("nevy","

▓█████ ▒██   ██▒ █     █░ ▄▄▄      
▓█   ▀ ▒▒ █ █ ▒░▓█░ █ ░█░▒████▄    
▒███   ░░  █   ░▒█░ █ ░█ ▒██  ▀█▄  
▒▓█  ▄  ░ █ █ ▒ ░█░ █ ░█ ░██▄▄▄▄██ 
░▒████▒▒██▒ ▒██▒░░██▒██▓  ▓█   ▓██▒
░░ ▒░ ░▒▒ ░ ░▓ ░░ ▓░▒ ▒   ▒▒   ▓▒█░
 ░ ░  ░░░   ░▒ ░  ▒ ░ ░    ▒   ▒▒ ░
   ░    ░    ░    ░   ░    ░   ▒   
   ░  ░ ░    ░      ░          ░  ░
                                   
");
color
("green","--------- WhatsApp CLI with API                                
Run At : {$kernel}
Donate : paypal.me/dalpan
Usage : --show (show message)
	--kirim (send message)
-------------------------------\n");
}

function ambil_pesan(){
	$get = json_decode(file_get_contents("http://panel.apiwha.com/get_messages.php?apikey=".APIKEY),1);
	return $get;
}

banner ();
while (true) {
	color("nevy","[ExWA]-> ");
	$command = trim(fgets(STDIN));
	if(strstr("--show", $command) or strtr("show", $command)) {
		memuat();
	} elseif(strstr("--kirim", $command) or strtr("kirim", $command)) {
		kirim_pesan();
	} else {
		echo "Gk iso moco ? /n";;
	}
}
