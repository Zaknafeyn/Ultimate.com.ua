<?php
$chat_name = "Чат :: ULTIMATE ФРИЗБИ НА УКРАИНЕ (КИЕВ)";
$time_date = 0; // Выводить время и дату. 1-да, 0-нет
$server_time = 0; // Синхронизация с местным временем +/- часы, например, если местное время 18:07, то при -1 чат будет показывать 17:07
$look_main_f = 0; // Показывать на главной странице, что сейчас говорят в чате
$sleep_period = 60*3; // 3 минут можно не подавать признаков и оставаться в чате
$kick_period = 60*1; // 1 минуты нельзя зайти в чат человеку, которого kick-нули
$lines_on_main_frame = 55; // Кол-во строк в главном фрейме
$max_strlen = 700; // максимальная длина сообщения или привата
$max_stat_len = 50; // максимальная длина статуса
$users_per_column = 5;
$max_smiles_per_message = 10; // максимум 10 смайликов в сообщении

	$allowed_tags = array("b", "u", "i", "sub", "sup", "marquee", "strike");
	$allowed_colors = array("red", "green", "blue", "yellow", "white", "black");

	$max_upload_file_size = 50*1024;
	$max_upload_file_width = 150;
	$max_upload_file_height = 150;

#цвета для фрейма посетителей
$admin_color="#ff8866";
$guard_color="#00ff00";
$guest_color="black";
$man_color="black";
$woman_color="coral";
$u_color="green";
$act_ask_color = "green";
$act_send_color = "lightblue";
$act_think_color = "cornflowerblue";
$act_do_color = "lightsalmon";
$act_gift_color = "green";
$act_tea_color = "darkred";
$act_meet_color = "mediumaquamarine";
$act_100b_color = "lime";
$act_coffee_color = "brown";
$act_song_color = "lightskyblue";
$act_naezd_color = "crimson";
$act_teeth_color = "green";
$act_phone_color = "darkslateblue";
$act_ears_color = "darkseagreen";

# цвет для подкрашивания сообщений для пользователя
$my_bg_c="#f0f0ff";

# язык чата (все доступные языки находятся в папке data/lang/)
$sp_lang="rus.txt";

# массив допустимых цветов
	$color_array = array(
	  array("blue", "blueviolet", "brown", "cornflowerblue",
	  "chocolate", "crimson", "darkorange", "darkslateblue", "fuchsia", "gainsboro",
	  "gray", "green", "hotpink", "indigo", "lightgreen", "lightslategray",
	  "lime", "orangered", "pink", "red", "tomato", "black"),
	  array("0000FF","8A2BE2","A52A2A","6495ED",
	  "D2691E", "DC143C", "FF8C00", "483D8B", "FF00FF", "DCDCDC",
	  "808080", "008000", "FF69B4", "4B0082", "90EE90", "778899",
	  "00FF00", "FF4500", "FFC0CB", "FF0000", "FF6347", "000000")
	);

//	$act_array = array("сказать", "приват", "спрашивает", "думает, что", "позвонить", "посылает", "*действие", "подарить", "чай", "кофе", "стрелка", "100 баксов", "спеть", "наехать", "по ушам", "зубы"); # действия
	$ref_array = array(5, 10, 15, 20, 30, 40, 60); # время рефреша при перегрузке

	$sex_color = array("m"=>$man_color,"w"=>$woman_color,"u"=>$u_color);
	$stat_color = array("guest"=>$guest_color,"guard"=>$guard_color,"admin"=>$admin_color);


	$bgcolor = "#CCFFCC";
?>