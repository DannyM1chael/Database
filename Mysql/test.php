<?php
//header('Content-type: text/plain; charset=utf-8');
header('Content-type: text/plain; charset=windows-1251');

error_reporting(E_ALL|E_STRICT);

include('../Mysql.php');
include('Exception.php');
include('Statement.php');

try
{
    $db = Krugozor_Database_Mysql::create('localhost', 'root', '')
          ->setCharset('cp1251')
          ->setDatabaseName('test');

    $db->query('DROP TABLE IF EXISTS ?f', 'test');
    echo $db->getQueryString() . "\n\n";

    $db->query('CREATE TABLE test(
    id int unsigned not null primary key auto_increment,
    name varchar(255),
    age tinyint,
    adress varchar(255)
    )');

    $db->query('INSERT INTO `test` VALUES (?n, "?s", "?s", "?s")', null, '����', '25', '������, ��. ������, ��� "���� � ������"');
    getAffectedInfo($db);

    $user = array('name' => '�������', 'age' => '30', 'adress' => '������, ��. �������, 20');
    $db->query('INSERT INTO `test` SET ?As', $user);
    getAffectedInfo($db);

    $user = array('id' => null, 'name' => 'ϸ��', 'age' => '19', 'adress' => '������, ��. ��������������, 2�');
    $db->query('INSERT INTO `test` SET ?A[?n, "?s", "?s", "?s"]', $user);
    getAffectedInfo($db);

    $user = array('id' => null, 'name' => '%�����%', 'age' => '17', 'adress' => '������, ��. ����������, 12');
    $db->query('INSERT INTO `test` VALUES (?a[?n, "?s", "?s", "?s"])', $user);
    getAffectedInfo($db);

    // LIKE-����� ������, ���������� � ���� `name` ��������� ������ % (�������)
    $result = $db->query('SELECT * FROM `test` WHERE `name` LIKE "%?S%"', '%');
    getSelectInfo($db, $result);

    $result = $db->query('SELECT * FROM `test` WHERE `id` = ?i', 1);
    getSelectInfo($db, $result);

    // ����� ������ �� ������� ����� - ?i, �� � ��������� �� �������� ������ '2+�����'.
    $result = $db->query('SELECT * FROM `test` WHERE `id` = ?i', '2+�����');
    getSelectInfo($db, $result);

    // �������� ������ � �������� ��������� �� ������ �������.
    $result = $db->query('SELECT * FROM `test` WHERE `name` IN (?a["?s", "?s", "?s"])', array('����', '����', '�����'));
    getSelectInfo($db, $result);

    // ���� �����, �� ������������ � ����������� � ����������� ������ ���������� ���������� �� �����.
    // �������� ���������� ����� ��������� � "�������" ������.
    $result = $db->query('SELECT * FROM `test` WHERE `name` IN (?as) OR `name` IN (?as)',
                         array('ϸ��', '����', '�����', '�������'),
                         array('����', 'Ը���', '����')
                        );
    getSelectInfo($db, $result);

    // �������� NULL � �������� ��������
    $db->query('INSERT INTO `test` VALUES (?n, ?n, ?n, ?n)', NULL, NULL, NULL, NULL);
    getSelectInfo($db, $result);

    // ���������� ������ queryArguments()
    $sql = 'SELECT * FROM `test` WHERE `name` IN (?as)';
    $arguments[] = array('ϸ��', '����', '�����');
    $sql .= ' OR `name` IN (?as)';
    $arguments[] = array('ϸ��', '����', '����');
    $result = $db->queryArguments($sql, $arguments);
    getSelectInfo($db, $result);

    // �������� ��� ������� �������� ����������:
    print_r($db->getQueries());
    echo "\n\n";

    // �������� ��� � �������
    $res = $db->query('SELECT * FROM test');
    while ($data = $res->fetch_assoc()) {
        print_r($data);
        echo "\n";
    }
    echo "\n\n";

    // �� ������
    $db->query('DELETE FROM `test`');
    getAffectedInfo($db);
}
catch (Krugozor_Database_Mysql_Exception $e)
{
    echo $e->getMessage();
}

/**
 * �������� ���������� ����� INSERT, UPDATE ��� DELETE.
 *
 * @param $db Krugozor_Database_Mysql
 */
function getAffectedInfo($db)
{
    echo "Original query: " . $db->getOriginalQueryString();
    echo "\n";
    echo "SQL: " . $db->getQueryString();
    echo "\n";
    echo '��������� �����: ' . $db->getAffectedRows();
    if ($id = $db->getLastInsertId()) {
           echo "\n";
        echo 'Last insert ID: ' . $db->getLastInsertId();
    }
    echo "\n\n";
}

/**
 * �������� ���������� ����� SELECT.
 *
 * @param $db Krugozor_Database_Mysql
 * @param $result Krugozor_Database_Mysql_Statement
 */
function getSelectInfo($db, $result)
{
    echo "SQL: " . $db->getQueryString();
    echo "\n";
    echo '�������� �������: ' . $result->getNumRows();
    echo "\n\n";
}