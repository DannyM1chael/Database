<?php
/**
 * @author Vasiliy Makogon, makogon.vs@gmail.com
 * @link http://www.phpinfo.su/
 *
 * ������ ����������. 
 */
class Krugozor_Database_Mysql_Statement
{
    /**
     * ��������� SQL-�������� � ���� ������� mysqli_result.
     *
     * @var mysqli_result
     */
    private $result;

    public function __construct(mysqli_result $result)
    {
        $this->result = $result;
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
        return mysqli_fetch_assoc($this->result);
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
        return mysqli_fetch_row($this->result);
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
    	return mysqli_fetch_object($this->result);
    }
    
    /**
     * ���������� ��������� SQL ������� � ���� ������� ������������� ��������.
     * 
     * @param void
     * @return array
     */
    public function fetch_assoc_array()
    {
        $array = array();

        while($row = mysqli_fetch_assoc($this->result))
        {
            $array[] = $row;
        }

        return $array;
    }
    
    /**
     * ���������� ��������� SQL ������� � ���� ������� ��������.
     * 
     * @param void
     * @return array
     */
    public function fetch_row_array()
    {
        $array = array();

        while($row = mysqli_fetch_row($this->result))
        {
            $array[] = $row;
        }

        return $array;
    }

    /**
     * ���������� ��������� SQL ������� � ���� ������� ��������.
     * 
     * @param void
     * @return array
     */
    public function fetch_object_array()
    {
        $array = array();

        while($row = mysqli_fetch_object($this->result))
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
        $row = mysqli_fetch_row($this->result);

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
        return mysqli_num_rows($this->result);
    }
    
    /**
     * ���������� ������ ���������� mysqli_result.
     * 
     * @param void
     * @return mysqli_result
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * ����������� ������ ������� ������������ ������� 
     * 
     * @param void
     * @return void
     */
    public function free()
    {
        mysqli_free_result($this->result);
    }

    public function __destruct()
    {
        $this->free();
    }
}