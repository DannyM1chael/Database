Rus

����� ��� ������� ������ � ���� MySql � �������������� ���������� PHP mysqli.

������ ����� ���������� ���������� placeholders - ��� ������������ ���������� SQL-��������, � ������ ������� ������ �������� ������� ����������� �������������� ������� - �����������, � ���� ������ ���������� "�����", � �������� ����������� ���������� ��������� ������, ������������ SQL-������ Krugozor_Database_Mysql::query():

$db->query('SELECT * FROM `table` WHERE `field_1` = "?s" AND `field_2` = ?i', '����', 30);

������, ��������� ����� ������� placeholders, ������������ ������������ ��������� �������������, � ����������� �� ���� ������������. �.�. ��� ��� ������������� ��������� ���������� � ������� ������������� ���� mysqli_real_escape_string($value) ��� ��������� �� � ��������� ���� ����� (int)$value.

��������� �������� ��. � ����� ./Mysql.php
����� � ��������� �������� ������������� ��. � ����� ./Mysql/test.php

Eng

Database - class for develop with database mysql, use adapter PHP mysqli and use placeholder, when each literal (argument) in string SQL query escaping without specific PHP function.
