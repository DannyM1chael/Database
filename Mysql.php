<?php
/**
 * @author Vasiliy Makogon, makogon.vs@gmail.com
 * @link http://www.phpinfo.su/
 *
 *
 * ---------------------------------------------------------------------------------
 * ����� ��� ������� � ���������� ������ � ���� MySql �� ���� ���������� PHP mysqli.
 * ---------------------------------------------------------------------------------
 *
 * ������ ����� ���������� ���������� placeholders - ��� ������������ ���������� SQL-��������, � ������ ������� ������
 * �������� ������� ����������� �������������� ������� - �.�. "�����������", � ���� ������ ���������� "�����", � ��������
 * ����������� ���������� ��������� ������, ������������ SQL-������ - Krugozor_Database_Mysql::query($sql [, $arg, $...]):
 *
 *     $db->query('SELECT * FROM `table` WHERE `field_1` = "?s" AND `field_2` = ?i', $_POST['name'], $_POST['age']);
 *
 * ��������� SQL-�������, ��������� ����� ������� placeholders ������� ������, ������������ ������������ ���������
 * �������������, � ����������� �� ���� ������������. �.�. ��� ������ ��� ������������� ��������� ���������� � �������
 * ������������� ���� mysqli_real_escape_string($value) ��� ��������� �� � ��������� ���� ����� (int)$value.
 *
 *
 * ----------------------------------------------------------------------------------
 *    ������ ������.
 * ----------------------------------------------------------------------------------
 *
 * ���������� ��� ������ ������ ������:
 * Krugozor_Database_Mysql::MODE_STRICT    - ������� ����� ������������ ���� ����������� � ���� ���������.
 * Krugozor_Database_Mysql::MODE_TRANSFORM - ����� �������������� ��������� � ���� ����������� ��� ������������
 *                                           ���� ����������� � ���� ���������.
 *
 * ����� Krugozor_Database_Mysql::MODE_TRANSFORM ���������� �� ��������� � �������� �������� ��� ����������� ����������.
 * �� ��������� ���� �����, ���� ��� �� ����� ������� ��������� ���������� � ��������.
 *
 *
 *     MODE_STRICT
 *
 * � "�������" ������ MODE_STRICT ���������, ������������ � �������� �����
 * Krugozor_Database_Mysql::query(), ������ � �������� ��������������� ���� �����������.
 * �������� �������:
 *
 * $db->query('SELECT * FROM `table` WHERE `field` = ?i', '����'); - � ������ ������ ����� ��������� ����������
 *     "������� �������� ��� int �������� ���� ���� string � ������� ...", �.�.
 * ������ ��� ����������� ?i (int - ����� �����), � � �������� ��������� ���������� ������ '����'.
 *
 * $db->query('SELECT * FROM `table` WHERE `field` = "?s"', 123); - ����� ��������� ����������
 *     "������� �������� ��� string �������� 123 ���� integer � ������� ...", �.�.
 * ������ ��� ����������� ?s (string - ������), � � �������� ��������� ���������� ����� 123.
 *
 * $db->query('SELECT * FROM `table` WHERE `field` IN (?as)', array(null, 123, true, 'string')); - ����� ��������� ����������
 *     "������� �������� ��� string �������� ���� NULL � ������� ...", �.�. ����������� ��������� ?as �������,
 * ��� ��� �������� �������-�������� ����� ���� s (string - ������), �� �� ���� ��� �������� ������� ������������ �����
 * ������ ��������� �����. ������ ��������� ������ �� ������ �������������� ���� ����������� � ���� ��������� - ��
 * �������� ������� �� ��������� null.
 *
 *
 *     MODE_TRANSFORM
 *
 * ����� MODE_TRANSFORM �������� "�������" ������� � ��� �������������� ���� ����������� � ��������� �� ����������
 * ����������, � �������� ������������� �������� � ������� ���� ����������� � ������������ � ��������� ��������������
 * ����� � PHP.
 *
 * ����������� ��������� ��������������:
 *
 * � ���������� ���� ���������� ������ ���� boolean, numeric, NULL:
 *     - �������� boolean TRUE ������������� � ������ "1", � �������� FALSE ������������� � "" (������ ������).
 *     - �������� ���� numeric ������������� � ������ �������� �������� ��������������, ������������ ������.
 *     - NULL ������������� � ������ ������.
 * ��� ��������, �������� � �������� �������������� �� �����������.
 *
 * ������ ���������:
 *     $db->query('SELECT * FROM `table` WHERE f1 = "?s", f2 = "?s", f3 = "?s"', null, 123, true);
 * ��������� ��������������:
 *     SELECT * FROM `table` WHERE f1 = "", f2 = "123", f3 = "1"
 *
 * � �������������� ���� ���������� ������ ���� boolean, string, NULL:
 *     - �������� boolean FALSE ������������� � 0 (����), � TRUE - � 1 (�������).
 *     - �������� ���� string ������������� �������� �������� ��������������, ������������ ������.
 *     - NULL ������������� � 0.
 * ��� ��������, �������� � �������� �������������� �� �����������.
 *
 * ������ ���������:
 *     $db->query('SELECT * FROM `table` WHERE f1 = ?i, f2 = ?i, f3 = ?i, f4 = ?i', null, '123abc', 'abc', true);
 * ��������� ��������������:
 *     SELECT * FROM `table` WHERE f1 = 0, f2 = 123, f3 = 0, f4 = 1
 *
 * NULL ��� �������� �������� ��� ������ ���� ������.
 *
 * ������ ���������:
 *     $db->query('INSERT INTO `table` VALUES (?n, ?n, ?n, ?n)', 123, '123', 'string', 1.74);
 * ��������� ��������������:
 *     INSERT INTO `table` VALUES (NULL, NULL, NULL, NULL)
 *
 *
 *    ���� ��������-������������
 *
 * ?f - ����������� ����� ������� ��� ����.
 *      ������ ����������� ������������ ��� �������, ����� ��� ������� ��� ���� ���������� � ������� ����� ����������:
 *      $db->query('SELECT * FROM ?f WHERE ?f = ?i', $table_name, $field_name, $value);
 *
 * ?i - ����������� ������ �����.
 *      � ������ MODE_TRANSFORM ����� ��������� ������ ������������� ���������� � ���� integer
 *      �������� �������� �������������� � ���� integer � PHP.
 *
 * ?p - ����������� ����� � ��������� ������.
 *      � ������ MODE_TRANSFORM ����� ��������� ������ ������������� ���������� � ���� float
 *      �������� �������� �������������� � ���� float � PHP.
 *
 * ?s - ����������� ���������� ����.
 *      � ������ MODE_TRANSFORM ����� ��������� ������ ������������� ���������� � ���� string
 *      �������� �������� �������������� � ���� string � PHP
 *      � ������������ � ������� ������� PHP mysqli_real_escape_string().
 *
 * ?S - ����������� ���������� ���� ��� ����������� � SQL-�������� LIKE.
 *      � ������ MODE_TRANSFORM ����� ��������� ������ ������������� ���������� � ���� string
 *      �������� �������� �������������� � ���� string � PHP
 *      � ������������ � ������� ������� PHP mysqli_real_escape_string() + ������������� ������������,
 *      ������������ � ��������� LIKE (%_).
 *
 * ?n - ����������� NULL ����.
 *      � ������ MODE_TRANSFORM ����� ������ ������������, ����������� ���������� �� ������ `NULL` � SQL �������.
 *
 * ?A* - ����������� �������������� ��������� �� �������������� �������, ������������ ������������������
 *       ��� ���� => ��������.
 *       ������: "key_1" = "val_1", "key_2" = "val_2", ...
 *
 * ?a* - ����������� ��������� �� �������� (��� ����� ��������������) �������, ������������ ������������������
 *       ��������.
 *       ������: "val_1", "val_2", ...
 *
 *       ��� * ����� ����������� - ���� �� �����:
 *       - i (int)
 *       - s (string)
 *       ������� �������������� � ������������� ����� ��, ��� � ��� ��������� ��������� ����� (��. ����).
 *
 * ?A[?n, ?s, ?i] - ����������� �������������� ��������� � ����� ��������� ���� � ���������� ����������,
 *                  ������������ ������������������ ��� ���� => ��������.
 *                  ������: "key_1" = "val_1", "key_2" => "val_2", ...
 *
 * ?a[?n, ?s, ?i] - ����������� ��������� � ����� ��������� ���� � ���������� ����������, ������������ ������������������
 *                  ��������.
 *                  ������: "val_1", "val_2", ...
 *
 *
 *    �������������� ������
 *
 * ������ ����� ��� ������������ SQL-������� �� ���������� ������������� �������������� ������� ��� ���������
 * ������������ ���������� ����, ����� ��� ?i � ?s. ��� ������� �� �������������� ������������, ��������������� �������
 * ����� ����� ������������ ��� ������������ SQL.
 * ��������, ���������
 *     $db->query('SELECT "Total: ?s"', '200');
 * ����� ������
 *     'Total: 200'
 * ���� �� �������, �������������� ��������� �������, ��������� �� �������������,
 * �� �������������� ������� ������� �� ������
 *     'Total: "200"'
 * ��� ���� �� �� ��������� ����������.
 *
 * ��� �� �����, ��� ������������ ?as, ?ai, ?As � ?Ai �������������� ������� �������� �������������, �.�.
 * ������������ ������ ������������ � ��������, ��� ������� ������ ����������� ��� �� ������ ����:
 *
 *    $db->query('INSERT INTO test SET ?As', array('name' => '����', 'age' => '23', 'adress' => '������'));
 *    -> INSERT INTO test SET `name` = "����", `age` = "23", `adress` = "������"
 *
 *    $db->query('SELECT * FROM table WHERE field IN (?as)', array('55', '12', '132'));
 *    -> SELECT * FROM table WHERE field IN ("55", "12", "132")
 *
 * ����� ���������� ���������� ����������� ���� ?f, ��������������� ��� �������� � ������ ���� ������ � �����.
 * �������� ����������� ?f ������ ����������� ��������� ��������� (`):
 *
 *    $db->query('SELECT ?f FROM ?f', 'my_field', 'my_table');
 *    -> SELECT `my_field` FROM `my_table`
 */
class Krugozor_Database_Mysql
{
    /**
     * ������� ����� ���������.
     * ���� ��� ����������� �� ��������� � ����� ���������, �� ����� ��������� ����������.
     * ������ ����� ��������:
     *
     * $db->query('SELECT * FROM `table` WHERE `id` = ?i', '2+�����');
     *
     * - � ������ �������� ��� ����������� ?i - ����� ��� �������� ������,
     *   � � �������� ��������� ��������� ������ '2+�����' �� ���������� �� ������, �� �������� �������.
     *
     * @var int
     */
    const MODE_STRICT = 1;

    /**
     * ����� ��������������.
     * ���� ��� ����������� �� ��������� � ����� ���������, �������� ������������� ����� �������
     * � ������� ���� - � ���� �����������.
     * ������ ����� ��������:
     *
     * $db->query('SELECT * FROM `table` WHERE `id` = ?i', '2+�����');
     *
     * - � ������ �������� ��� ����������� ?i - ����� ��� �������� ������,
     *   � � �������� ��������� ��������� ������ '2+�����' �� ���������� �� ������, �� �������� �������.
     *   ������ '2+�����' ����� ������������� ��������� � ���� int �������� �������� �������������� ����� � PHP.
     *
     * @var int
     */
    const MODE_TRANSFORM = 2;

    /**
     * ����� ������. ��. �������� �������� self::MODE_STRICT � self::MODE_TRANSFORM.
     *
     * @var int
     */
    protected $type_mode = self::MODE_TRANSFORM;

    protected $server;

    protected $user;

    protected $password;

    protected $port;

    protected $socket;

    /**
     * ��� ������� ��.
     *
     * @var string
     */
    protected $database_name;

    /**
     * ����������� ������ ���������� �������� MySQL.
     *
     * @var mysqli
     */
    protected $mysqli;

    /**
     * ������ ���������� SQL-������� �� ��������������.
     *
     * @var string
     */
    private $original_query;

    /**
     * ������ ���������� SQL-������� ����� ��������������.
     *
     * @var string
     */
    private $query;

    /**
     * ������ �� ����� ���������, ������� ���� ��������� ��������.
     * ����� - SQL ����� ��������������, �������� - SQL �� ��������������.
     *
     * @var array
     */
    private static $queries = array();

    /**
     * ������� ������� ������� ������.
     *
     * @param string $server ��� �������
     * @param string $username ��� ������������
     * @param string $password ������
     * @param string $port ����
     * @param string $socket �����
     */
    public static function create($server, $username, $password, $port=null, $socket=null)
    {
        return new self($server, $username, $password, $port, $socket);
    }

    /**
     * ������ ����� �������� �� ���������.
     *
     * @param string $charset
     * @return Krugozor_Database_Mysql
     */
    public function setCharset($charset)
    {
        if (!$this->mysqli->set_charset($charset))
        {
            throw new Krugozor_Database_Mysql_Exception(__METHOD__ . ': ' . $this->mysqli->error);
        }

        return $this;
    }

    /**
     * ���������� ��������� �� ���������, ������������� ��� ���������� � ��
     *
     * @param void
     * @return string
     */
    public function getCharset()
    {
        return $this->mysqli->character_set_name();
    }

    /**
     * ������������� ��� ������������ ����.
     *
     * @param string ��� ���� ������
     * @return Krugozor_Database_Mysql
     */
    public function setDatabaseName($database_name)
    {
        if (!$database_name)
        {
            throw new Krugozor_Database_Mysql_Exception(__METHOD__ . ': �� ������� ��� ���� ������');
        }

        $this->database_name = $database_name;

        if (!$this->mysqli->select_db($this->database_name))
        {
            throw new Krugozor_Database_Mysql_Exception(__METHOD__ . ': ' . $this->mysqli->error);
        }

        return $this;
    }

    /**
     * ���������� ��� ������� ��.
     *
     * @param void
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->database_name;
    }

    /**
     * ������������� ����� ��������� ��� ������������ ���� ����������� � ���������.
     *
     * @param $value int
     * @return Krugozor_Database_Mysql
     */
    public function setTypeMode($value)
    {
        if (!in_array($value, array(self::MODE_STRICT, self::MODE_TRANSFORM)))
        {
            throw new Krugozor_Database_Mysql_Exception('������ ����������� ��� ������');
        }

        $this->type_mode = $value;

        return $this;
    }

    /**
     * ��������� SQL-������.
     * ��������� ������������ �������� - SQL-������ �, � ������ �������,
     * ����� ���������� ���������� - �������� ������������.
     *
     * @param string ������ SQL-�������
     * @param mixed ��������� ��� ������������
     * @return bool|Krugozor_Database_Mysql_Statement
     */
    public function query()
    {
        if (!func_num_args())
        {
            return false;
        }

        $args = func_get_args();

        $query = $this->original_query = array_shift($args);

        $this->query = $this->parse($query, $args);

        $result = $this->mysqli->query($this->query);

        self::$queries[$this->query] = $this->original_query;

        if ($result === false)
        {
            throw new Krugozor_Database_Mysql_Exception(__METHOD__ . ': ' . $this->mysqli->error . '; SQL: ' . $this->query);
        }

        if (is_object($result) && $result instanceof mysqli_result)
        {
            return new Krugozor_Database_Mysql_Statement($result);
        }

        return $result;
    }

    /**
     * ��������� ���������� ������ self::query(), ������ ����� ��������� ������ ��� ��������� -
     * SQL ������ $query � ������ ���������� $arguments, ������� � ����� �������� �� ���������� � ���
     * ������������������, � ������� ��� ������������� � ������� $arguments.
     *
     * @param string
     * @param array
     * @return bool|Krugozor_Database_Mysql_Statement
     */
    public function queryArguments($query, array $arguments=array())
    {
        array_unshift($arguments, $query);

        return call_user_func_array(array($this, 'query'), $arguments);
    }

    /**
     * ������ ��� ������� $this->parse().
     * ����������� ��� �������, ����� SQL-������ ����������� ������� � ����� ������� �������� ����������� � ��������.
     * ������:
     * echo $db->prepare('?s ?ai', '"�����"', array(1, 2));
     * > \"�����\" "1", "2"
     *
     * @param string SQL-������ ��� ��� �����
     * @param mixed ��������� ������������
     * @return boolean|string
     */
    public function prepare()
    {
        if (!func_num_args())
        {
            return false;
        }

        $args = func_get_args();
        $query = array_shift($args);

        return $this->parse($query, $args);
    }

    /**
     * �������� ���������� �����, ��������������� � ���������� MySQL-��������.
     * ���������� ���������� �����, ��������������� � ��������� ������� INSERT, UPDATE ��� DELETE.
     * ���� ��������� �������� ��� DELETE ��� ��������� WHERE,
     * ��� ������ ������� ����� �������, �� ������� ��������� ����.
     *
     * @see mysqli_affected_rows
     * @param void
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->mysqli->affected_rows;
    }

    /**
     * ���������� ��������� ������������ SQL-������ �� ��������������.
     *
     * @param void
     * @return string
     */
    public function getOriginalQueryString()
    {
        return $this->original_query;
    }

    /**
     * ���������� ��������� ����������� MySQL-������.
     *
     * @param void
     * @return string
     */
    public function getQueryString()
    {
        return $this->query;
    }

    /**
     * ���������� ������ �� ����� ������������ SQL-��������� � ������ �������� �������.
     *
     * @param void
     * @return array
     */
    public function getQueries()
    {
        return self::$queries;
    }

    /**
     * ���������� id, ��������������� ���������� ��������� INSERT.
     *
     * @param void
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->mysqli->insert_id;;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @param string $server
     * @param string $username
     * @param string $password
     * @param string $port
     * @param string $socket
     * @return void
     */
    private function __construct($server, $user, $password, $port, $socket)
    {
        $this->server   = $server;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
        $this->socket = $socket;

        $this->connect();
    }

    /**
     * ������������� ���������� � ����� ������.
     *
     * @param void
     * @return void
     */
    private function connect()
    {
        if (!is_object($this->mysqli) || !$this->mysqli instanceof mysqli)
        {
        	$this->mysqli = @new mysqli($this->server, $this->user, $this->password, null, $this->port, $this->socket);

        	if ($this->mysqli->connect_error)
            {
                throw new Krugozor_Database_Mysql_Exception(__METHOD__ . ': ' . $this->mysqli->connect_error);
            }
        }
    }

    /**
     * ��������� MySQL-����������.
     *
     * @param void
     * @return Krugozor_Database_Mysql
     */
    private function close()
    {
        if (is_object($this->mysqli) && $this->mysqli instanceof mysqli)
        {
            @$this->mysqli->close();
        }

        return $this;
    }

    /**
     * ���������� �������������� ������ ��� placeholder-� ������ LIKE.
     *
     * @param string $var ������ � ������� ���������� ������������ ����. �������
     * @param string $chars ����� ��������, ������� ��� �� ���������� ������������.
     *                      �� ��������� ������������ ��������� �������: `'"%_`.
     * @return string
     */
    private function escape_like($var, $chars = "%_")
    {
        $var = str_replace('\\', '\\\\', $var);
        $var = $this->mysqlRealEscapeString($var);

        if ($chars)
        {
            $var = addCslashes($var, $chars);
        }

        return $var;
    }

    /**
     * ���������� ����������� ������� � ������ ��� ������������� � SQL ���������,
     * ��������� ������� ����� �������� ����������
     *
     * @see mysqli_real_escape_string
     * @param string
     * @return string
     */
    private function mysqlRealEscapeString($value)
    {
        return $this->mysqli->real_escape_string($value);
    }

    /**
     * ���������� ������ �������� ������ ��� ������������ ����� ������������ � ����������.
     *
     * @param string $type ��� �����������
     * @param mixed $value �������� ���������
     * @param string $original_query ������������ SQL-������
     * @return string
     */
    private function createErrorMessage($type, $value, $original_query)
    {
        return __CLASS__ . ': ������� �������� ��� ' . $type . ' �������� "' . print_r($value, true) . '" ���� ' .
               gettype($value) . ' � ������� ' . $original_query;
    }

    /**
     * ������ ������ $query � ����������� � ���� ��������� �� $args.
     *
     * @param string $query SQL ������ ��� ��� ����� (� ������ �������� ������� � ������� [])
     * @param array $args ��������� ������������
     * @param string $original_query "������������", ������ SQL-������
     * @return string SQL ������ ��� ����������
     */
    private function parse($query, array $args, $original_query=null)
    {
        $original_query = $original_query ? $original_query : $query;

        $offset = 0;

        while (($posQM = strpos($query, '?', $offset)) !== false)
        {
            $offset = $posQM;

            if (!isset($query[$posQM + 1]))
            {
                continue;
            }
            else
            {
                // ���� ������ ������ ���� ?, ������ ������ ������.
                if (!in_array($query[$posQM + 1], array('i', 'p', 's', 'S', 'n', 'A', 'a', 'f')))
                {
                    $offset += 1;
                    continue;
                }
            }

            if (!$args)
            {
                throw new Krugozor_Database_Mysql_Exception(__METHOD__ . ': ���������� ������������ � ������� ' . $original_query .
                                    ' �� ������������� ����������� ���������� ����������');
            }

            $value = array_shift($args);

            switch ($query[$posQM + 1])
            {
                // `LIKE` search escaping
                case 'S':
                    $is_like_escaping = true;

                // Simple string escaping
                // � ������ ��������� MODE_TRANSFORM ������, �������������� ���������� �������� �������� php ���������
                // http://php.net/manual/ru/language.types.string.php#language.types.string.casting
                // ��� bool, null � numeric ����.
                case 's':
                    $value = $this->getValueStringType($value, $original_query);
                    $value = !empty($is_like_escaping) ? $this->escape_like($value) : $this->mysqlRealEscapeString($value);
                    $query = substr_replace($query, $value, $posQM, 2);
                    $offset += strlen($value);
                    break;

                // Integer
                // � ������ ��������� MODE_TRANSFORM ������, �������������� ���������� �������� �������� php ���������
                // http://php.net/manual/ru/language.types.integer.php#language.types.integer.casting
                // ��� bool, null � string ����.
                case 'i':
                    $value = $this->getValueIntType($value, $original_query);
                    $query = substr_replace($query, $value, $posQM, 2);
                    $offset += strlen($value);
                    break;

                // Floating point
                case 'p':
                    $value = $this->getValueFloatType($value, $original_query);
                    $query = substr_replace($query, $value, $posQM, 2);
                    $offset += strlen($value);
                    break;

                // NULL insert
                case 'n':
                    $value = $this->getValueNullType($value, $original_query);
                    $query = substr_replace($query, $value, $posQM, 2);
                    $offset += strlen($value);
                    break;

                // field or table name
                case 'f':
                    $value = '`' . $this->escapeFieldName($value) . '`';
                    $query = substr_replace($query, $value, $posQM, 2);
                    $offset += strlen($value);
                    break;

                // ������� ��������.

                // Associative array
                case 'A':
                    $is_associative_array = true;

                // Simple array
                case 'a':
                    $value = $this->getValueArrayType($value, $original_query);

                    if (isset($query[$posQM+2]) && preg_match('#[sip\[]#', $query[$posQM+2], $matches))
                    {
                        // ������ ��������� ���� ?a[?i, "?s", "?s"]
                        if ($query[$posQM+2] == '[' and ($close = strpos($query, ']', $posQM+3)) !== false)
                        {
                            // ��������� ����� �������� [ � ]
                            $array_parse = substr($query, $posQM+3, $close - ($posQM+3));
                            $array_parse = trim($array_parse);
                            $placeholders = array_map('trim', explode(',', $array_parse));

                            if (count($value) != count($placeholders))
                            {
                                throw new Krugozor_Database_Mysql_Exception('������������ ���������� ���������� � ������������ � �������, ������ ' . $original_query);
                            }

                            reset($value);
                            reset($placeholders);

                            $replacements = array();

                            foreach ($placeholders as $placeholder)
                            {
                                list($key, $val) = each($value);
                                $replacements[$key] = $this->parse($placeholder, array($val), $original_query);
                            }

                            if (!empty($is_associative_array))
                            {
                                foreach ($replacements as $key => $val)
                                {
                                    $values[] = ' `' . $this->escapeFieldName($key) . '` = ' . $val;
                                }

                                $value = implode(',', $values);
                            }
                            else
                            {
                                $value = implode(', ', $replacements);
                            }

                            $query = substr_replace($query, $value, $posQM, 4 + strlen($array_parse));
                            $offset += strlen($value);
                        }
                        // ��������� ���� ?ai, ?as, ?ap
                        else if (preg_match('#[sip]#', $query[$posQM+2], $matches))
                        {
                            $sql = '';
                            $parts = array();

                            foreach ($value as $key => $val)
                            {
                                switch ($matches[0])
                                {
                                    case 's':
                                        $val = $this->getValueStringType($val, $original_query);
                                        $val = $this->mysqlRealEscapeString($val);
                                        break;
                                    case 'i':
                                        $val = $this->getValueIntType($val, $original_query);
                                        break;
                                    case 'p':
                                        $val = $this->getValueFloatType($val, $original_query);
                                        break;
                                }

                                if (!empty($is_associative_array))
                                {
                                    $parts[] = ' `' . $this->escapeFieldName($key) . '` = "' . $val . '"';
                                }
                                else
                                {
                                    $parts[] = '"' . $val . '"';
                                }
                            }

                            $value = implode(', ', $parts);
                            $query = substr_replace($query, $value, $posQM, 3);
                            $offset += strlen($value);
                        }
                    }
                    else
                    {
                        throw new Krugozor_Database_Mysql_Exception('������� ��������������� ������������ ������� ��� �������� ���� ������ ��� ���������');
                    }
                    break;
            }
        }

        return $query;
    }

    /**
     * � ����������� �� ���� ������ ���������� ���� ��������� �������� $value,
     * ���� ������ ����������.
     *
     * @param mixed $value
     * @param string $original_query ������������ SQL ������
     * @throws Exception
     * @return string
     */
    private function getValueStringType($value, $original_query)
    {
        if (!is_string($value))
        {
            if ($this->type_mode == self::MODE_STRICT)
            {
                throw new Krugozor_Database_Mysql_Exception($this->createErrorMessage('string', $value, $original_query));
            }
            else if ($this->type_mode == self::MODE_TRANSFORM)
            {
                if (is_numeric($value) || is_null($value) || is_bool($value))
                {
                    $value = (string)$value;
                }
                else
                {
                    throw new Krugozor_Database_Mysql_Exception($this->createErrorMessage('string', $value, $original_query));
                }
            }
        }

        return $value;
    }

    /**
     * � ����������� �� ���� ������ ���������� ���� ��������� �������� ����� $value,
     * ���� ������ ����������.
     *
     * @param mixed $value
     * @param string $original_query ������������ SQL ������
     * @throws Exception
     * @return string
     */
    private function getValueIntType($value, $original_query)
    {
        if (!is_numeric($value))
        {
            if ($this->type_mode == self::MODE_STRICT)
            {
                throw new Krugozor_Database_Mysql_Exception($this->createErrorMessage('int', $value, $original_query));
            }
            else if ($this->type_mode == self::MODE_TRANSFORM)
            {
                if (is_string($value) || is_null($value) || is_bool($value))
                {
                    $value = (int)$value;
                }
                else
                {
                    throw new Krugozor_Database_Mysql_Exception($this->createErrorMessage('int', $value, $original_query));
                }
            }
        }

        return (string)$value;
    }

    /**
     * � ����������� �� ���� ������ ���������� ���� ��������� �������� ����� $value,
     * ���� ������ ����������.
     *
     * @param mixed $value
     * @param string $original_query ������������ SQL ������
     * @throws Exception
     * @return string
     */
    private function getValueFloatType($value, $original_query)
    {
        if (!is_numeric($value))
        {
            if ($this->type_mode == self::MODE_STRICT)
            {
                throw new Krugozor_Database_Mysql_Exception($this->createErrorMessage('float', $value, $original_query));
            }
            else if ($this->type_mode == self::MODE_TRANSFORM)
            {
                if (is_string($value) || is_null($value) || is_bool($value))
                {
                    $value = (float)$value;
                }
                else
                {
                    throw new Krugozor_Database_Mysql_Exception($this->createErrorMessage('float', $value, $original_query));
                }
            }
        }

        return (string)$value;
    }

    /**
     * � ����������� �� ���� ������ ���������� ���� ��������� �������� 'NULL',
     * ���� ������ ����������.
     *
     * @param mixed $value
     * @param string $original_query ������������ SQL ������
     * @throws Exception
     * @return string
     */
    private function getValueNullType($value, $original_query)
    {
        if ($value !== null)
        {
            if ($this->type_mode == self::MODE_STRICT)
            {
                throw new Krugozor_Database_Mysql_Exception($this->createErrorMessage('NULL', $value, $original_query));
            }
        }

        return 'NULL';
    }

    /**
     * ������ ���������� ����������, ���� $value �� �������� ��������.
     * ������������� ���� ���� � ������ self::MODE_TRANSFORM ��������� � ���� array
     * ��������� ������, �� �� ������ ������ � ������ ��� �������� ������������ ��� ��������,
     * ������� ����� ������������ ������ �����.
     *
     * @param mixed $value
     * @param string $original_query
     * @throws Exception
     * @return array
     */
    private function getValueArrayType($value, $original_query)
    {
        if (!is_array($value))
        {
            throw new Krugozor_Database_Mysql_Exception($this->createErrorMessage('array', $value, $original_query));
        }

        return $value;
    }

    /**
     * ���������� ��� ���� ������� � ������ ������������� �������� ���������.
     *
     * @param string $value
     * @return string $value
     */
    private function escapeFieldName($value)
    {
        return str_replace("`", "``", $value);
    }
}