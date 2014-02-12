<?php
/**
 * @author Vasiliy Makogon, makogon.vs@gmail.com
 * @link http://www.phpinfo.su/
 *
 * ������ ��� �������� mysqli_result.
 */
class Krugozor_Database_Mysql_Statement
{
    /**
     * ��������� SQL-�������� � ���� ������� mysqli_result.
     *
     * @var mysqli_result
     */
    private $mysqli_result = null;

    public function __construct(mysqli_result $mysqli_result)
    {
        $this->mysqli_result = $mysqli_result;
    }

    /**
     * ��������� �������������� ��� � ���� �������������� �������.
     *
     * @see mysqli_fetch_assoc
     * @param void
     * @return array
     */

    public function fetch_assoc()
    {
        return mysqli_fetch_assoc($this->mysqli_result);
    }

    /**
     * ��������� �������������� ��� � ���� �������.
     *
     * @see mysqli_fetch_row
     * @param void
     * @return array
     */
    public function fetch_row()
    {
        return mysqli_fetch_row($this->mysqli_result);
    }

    /**
     * ��������� �������������� ��� � ���� �������.
     *
     * @see mysqli_fetch_object
     * @param void
     * @return stdClass
     */
    public function fetch_object()
    {
        return mysqli_fetch_object($this->mysqli_result);
    }

    /**
     * ���������� ��������� � ���� ����������� ������� ������������� ��������.
     *
     * @param void
     * @return array
     */
    public function fetch_assoc_array()
    {
        $array = array();

        while($row = mysqli_fetch_assoc($this->mysqli_result))
        {
            $array[] = $row;
        }

        return $array;
    }

    /**
     * ���������� ��������� � ���� ����������� ������� ��������.
     *
     * @param void
     * @return array
     */
    public function fetch_row_array()
    {
        $array = array();

        while($row = mysqli_fetch_row($this->mysqli_result))
        {
            $array[] = $row;
        }

        return $array;
    }

    /**
     * ���������� ��������� � ���� ������� ��������.
     *
     * @param void
     * @return array
     */
    public function fetch_object_array()
    {
        $array = array();

        while($row = mysqli_fetch_object($this->mysqli_result))
        {
            $array[] = $row;
        }

        return $array;
    }

    /**
     * ���������� �������� ������� ���� �������������� �������.
     *
     * @param void
     * @return string
     */
    public function getOne()
    {
        $row = mysqli_fetch_row($this->mysqli_result);

        return $row[0];
    }

    /**
     * ���������� ���������� ����� � ����������.
     * ��� ������� ����� ������ ��� ���������� SELECT.
     *
     * @param void
     * @return int
     */
    public function getNumRows()
    {
        return mysqli_num_rows($this->mysqli_result);
    }

    /**
     * ���������� ������ ���������� mysqli_result.
     *
     * @param void
     * @return mysqli_result
     */
    public function getResult()
    {
        return $this->mysqli_result;
    }

    /**
     * ����������� ������ ������� ������������ �������.
     *
     * @param void
     * @return void
     */
    public function free()
    {
        mysqli_free_result($this->mysqli_result);
    }

    public function __destruct()
    {
        $this->free();
    }
}