<?php
$chat_name = "��� :: ULTIMATE ������ �� ������� (����)";
$time_date = 0; // �������� ����� � ����. 1-��, 0-���
$server_time = 0; // ������������� � ������� �������� +/- ����, ��������, ���� ������� ����� 18:07, �� ��� -1 ��� ����� ���������� 17:07
$look_main_f = 0; // ���������� �� ������� ��������, ��� ������ ������� � ����
$sleep_period = 60*3; // 3 ����� ����� �� �������� ��������� � ���������� � ����
$kick_period = 60*1; // 1 ������ ������ ����� � ��� ��������, �������� kick-����
$lines_on_main_frame = 55; // ���-�� ����� � ������� ������
$max_strlen = 700; // ������������ ����� ��������� ��� �������
$max_stat_len = 50; // ������������ ����� �������
$users_per_column = 5;
$max_smiles_per_message = 10; // �������� 10 ��������� � ���������

	$allowed_tags = array("b", "u", "i", "sub", "sup", "marquee", "strike");
	$allowed_colors = array("red", "green", "blue", "yellow", "white", "black");

	$max_upload_file_size = 50*1024;
	$max_upload_file_width = 150;
	$max_upload_file_height = 150;

#����� ��� ������ �����������
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

# ���� ��� ������������� ��������� ��� ������������
$my_bg_c="#f0f0ff";

# ���� ���� (��� ��������� ����� ��������� � ����� data/lang/)
$sp_lang="rus.txt";

# ������ ���������� ������
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

//	$act_array = array("�������", "������", "����������", "������, ���", "���������", "��������", "*��������", "��������", "���", "����", "�������", "100 ������", "�����", "�������", "�� ����", "����"); # ��������
	$ref_array = array(5, 10, 15, 20, 30, 40, 60); # ����� ������� ��� ����������

	$sex_color = array("m"=>$man_color,"w"=>$woman_color,"u"=>$u_color);
	$stat_color = array("guest"=>$guest_color,"guard"=>$guard_color,"admin"=>$admin_color);


	$bgcolor = "#CCFFCC";
?>