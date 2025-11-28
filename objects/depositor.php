<?php

class Depositor
{
    private $conn;
    private $table_vkladchik = "vkladchik"; 
    private $table_procent = "procent";    

    public $nomer_vklada;
    public $nazvanie_vklada;
    public $fio_vkladchika;
    public $summa_vklada;
    public $data_vlozheniya;
    public $procent_nachisleniya;
    public $obshchaya_summa_s_nachisleniyami;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT
                    `номер вклада`,
                    `название вклада`,
                    `ФИО вкладчика`,
                    `сумма вклада`,
                    `дата вложения`,
                    `процент начисления`,
                    `общая сумма с начислениями`
                FROM
                    " . $this->table_vkladchik . "
                ORDER BY
                    `название вклада` ASC, `ФИО вкладчика` ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getTotalInterestSum()
    {
        $query = "SELECT
                    SUM(`общая сумма с начислениями` - `сумма вклада`) AS total_interest
                FROM
                    " . $this->table_vkladchik;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_interest'];
    }
}
?>